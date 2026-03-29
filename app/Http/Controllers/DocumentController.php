<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $hasColumns = Schema::hasTable('documents') && Schema::hasColumn('documents', 'project_id');

        $documents = collect();
        $stats = ['total' => 0, 'contracts' => 0, 'blueprints' => 0, 'permits' => 0, 'photos' => 0];

        if ($hasColumns) {
            $query = DB::table('documents')
                ->join('projects', 'projects.id', '=', 'documents.project_id')
                ->leftJoin('users', 'users.id', '=', 'documents.uploaded_by')
                ->select('documents.*', 'projects.project_name', 'users.name as uploader');

            if ($request->type && $request->type !== 'all') {
                $query->where('documents.document_type', $request->type);
            }
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('documents.document_name', 'like', '%' . $request->search . '%')
                        ->orWhere('projects.project_name', 'like', '%' . $request->search . '%');
                });
            }

            $documents = $query->orderByDesc('documents.created_at')->paginate(12);

            $stats = [
                'total' => DB::table('documents')->count(),
                'contracts' => DB::table('documents')->where('document_type', 'contract')->count(),
                'blueprints' => DB::table('documents')->where('document_type', 'blueprint')->count(),
                'permits' => DB::table('documents')->where('document_type', 'permit')->count(),
                'photos' => DB::table('documents')->where('document_type', 'photo')->count(),
            ];
        }

        $projects = DB::table('projects')->orderBy('project_name')->get();

        return view('documents.index', compact('documents', 'stats', 'projects', 'hasColumns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'document_name' => 'required|string|max:200',
            'document_type' => 'required|in:contract,permit,blueprint,report,photo,inspection,other',
            'file' => 'required|file|max:51200|mimes:pdf,doc,docx,dwg,jpg,jpeg,png,xlsx,zip',
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        DB::table('documents')->insert([
            'project_id' => $request->project_id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'file_url' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'version_number' => 1,
            'is_latest' => true,
            'uploaded_by' => auth()->id(),
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded!');
    }

    public function download($id)
    {
        abort_unless(auth()->user()->can('view documents'), 403);

        $doc = DB::table('documents')->where('id', $id)->first();
        abort_unless($doc, 404);

        $name = $doc->document_name;
        if ($doc->file_extension) {
            $name .= '.' . ltrim($doc->file_extension, '.');
        }

        return Storage::disk('public')->download($doc->file_url, $name);
    }

    public function destroy($id)
    {
        $doc = DB::table('documents')->where('id', $id)->first();
        abort_unless($doc, 404);

        Storage::disk('public')->delete($doc->file_url);
        DB::table('documents')->where('id', $id)->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted.');
    }
}