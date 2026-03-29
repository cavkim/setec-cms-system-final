<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $hasColumns = Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'type');

        $notifications = collect();
        $stats = ['unread' => 0, 'total' => 0, 'read' => 0];

        if ($hasColumns) {
            $notifications = DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->where('notifiable_id', auth()->id())
                ->orderByDesc('created_at')
                ->paginate(15);

            $stats = [
                'unread' => DB::table('notifications')
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', auth()->id())
                    ->whereNull('read_at')->count(),
                'total' => DB::table('notifications')
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', auth()->id())->count(),
                'read' => DB::table('notifications')
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', auth()->id())
                    ->whereNotNull('read_at')->count(),
            ];
        }

        return view('notifications.notification-page', compact('notifications', 'stats', 'hasColumns'));
    }

    public function readAll(Request $request)
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'read_at')) {
            DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->where('notifiable_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return redirect()->route('notifications.index')->with('success', 'All marked as read!');
    }
}
