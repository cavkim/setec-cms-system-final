@extends('layouts.app')
@section('title', 'Documents')
@section('page-title', 'Documents')

@section('styles')
    <style>
        #doc-drawer {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #doc-drawer.open {
            transform: translateX(0);
        }

        #doc-drawer-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #doc-drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .doc-row-active {
            background-color: rgba(173, 198, 255, 0.08) !important;
            box-shadow: inset 2px 0 0 #adc6ff;
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'))</script>
    @endif

    {{-- Header --}}

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <div class="bg-surface-container-high p-6 rounded-xl border-l-4 border-primary shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">folder</span>
                <span class="text-[0.6rem] font-black uppercase text-on-surface-variant tracking-widest">Total</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['total'] }}</p>
            <p class="text-xs text-primary mt-1 font-medium">All files</p>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-secondary bg-secondary/10 p-2 rounded-lg">description</span>
                <span class="text-[0.6rem] font-black uppercase text-on-surface-variant tracking-widest">Contracts</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['contracts'] }}</p>
            <p class="text-xs text-secondary mt-1 font-medium">Legal documents</p>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-tertiary bg-tertiary/10 p-2 rounded-lg">architecture</span>
                <span class="text-[0.6rem] font-black uppercase text-on-surface-variant tracking-widest">Blueprints</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['blueprints'] }}</p>
            <p class="text-xs text-tertiary mt-1 font-medium">Design files</p>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-error bg-error/10 p-2 rounded-lg">gavel</span>
                <span class="text-[0.6rem] font-black uppercase text-on-surface-variant tracking-widest">Permits</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['permits'] }}</p>
            <p class="text-xs text-error mt-1 font-medium">Government permits</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-surface-container-low rounded-xl p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 flex-wrap">
            @foreach(['all' => 'All types', 'contract' => 'Contracts', 'blueprint' => 'Blueprints', 'permit' => 'Permits', 'report' => 'Reports', 'photo' => 'Photos', 'other' => 'Other'] as $v => $l)
                <a href="{{ route('documents.index', ['type' => $v, 'search' => request('search')]) }}"
                    class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all
                                  {{ request('type', 'all') === $v ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant hover:text-on-surface' }}">{{ $l }}</a>
            @endforeach
        </div>
        <div class="relative">
            <form method="GET" action="{{ route('documents.index') }}">
                <input type="hidden" name="type" value="{{ request('type', 'all') }}">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input
                    class="bg-surface-container-lowest border border-white/5 rounded-xl pl-10 pr-4 py-2 text-sm text-on-surface w-64 focus:outline-none focus:border-primary placeholder:text-on-surface-variant"
                    placeholder="Search documents or projects..." type="text" name="search" value="{{ request('search') }}"
                    oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)">
            </form>
        </div>
    </div>

    {{-- Document list --}}
    @if($hasColumns)
        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl">
            {{-- Column headers --}}
            <div
                class="grid grid-cols-12 px-6 py-3 text-[0.6875rem] font-black uppercase tracking-widest text-on-surface-variant bg-surface-container-high/30 border-b border-white/5">
                <div class="col-span-5">Document Name</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-2">Upload Details</div>
                <div class="col-span-1">Size</div>
                <div class="col-span-1 text-center">Version</div>
                <div class="col-span-1 text-right">Actions</div>
            </div>

            <div class="divide-y divide-white/5">
                @forelse($documents as $doc)
                    @php
                        $typeMap = [
                            'contract' => ['color' => 'text-tertiary', 'bg' => 'bg-surface-variant text-on-surface-variant', 'icon' => 'description'],
                            'blueprint' => ['color' => 'text-primary', 'bg' => 'bg-surface-variant text-primary', 'icon' => 'architecture'],
                            'permit' => ['color' => 'text-secondary', 'bg' => 'bg-secondary-container/20 text-secondary', 'icon' => 'verified'],
                            'report' => ['color' => 'text-error', 'bg' => 'bg-tertiary-container/20 text-tertiary-container', 'icon' => 'construction'],
                            'photo' => ['color' => 'text-primary', 'bg' => 'bg-surface-variant text-primary', 'icon' => 'image'],
                            'inspection' => ['color' => 'text-secondary', 'bg' => 'bg-secondary-container/20 text-secondary', 'icon' => 'fact_check'],
                            'other' => ['color' => 'text-on-surface-variant', 'bg' => 'bg-surface-variant text-on-surface-variant', 'icon' => 'folder'],
                        ];
                        $tm = $typeMap[$doc->document_type] ?? $typeMap['other'];
                        $sizeLabel = $doc->file_size ? ($doc->file_size < 1048576 ? round($doc->file_size / 1024) . ' KB' : round($doc->file_size / 1048576, 1) . ' MB') : '—';
                    @endphp
                    <div class="grid grid-cols-12 items-center px-6 py-5 hover:bg-surface-container-high transition-all duration-200 group relative cursor-pointer doc-row"
                        onclick="openDocDrawer(this, {{ $doc->id }}, '{{ addslashes($doc->document_name) }}', '{{ $doc->document_type }}', '{{ addslashes($doc->project_name) }}', '{{ addslashes($doc->uploader ?? 'Unknown') }}', '{{ \Carbon\Carbon::parse($doc->created_at)->format('M d, Y') }}', '{{ $sizeLabel }}', {{ $doc->version_number ?? 1 }})">
                        <div class="col-span-5 flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-surface-container-lowest rounded-lg flex items-center justify-center border border-outline-variant/10 flex-shrink-0">
                                <span class="material-symbols-outlined {{ $tm['color'] }} text-2xl"
                                    style="font-variation-settings:'FILL' 1;">{{ $tm['icon'] }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface mb-0.5">{{ $doc->document_name }}</p>
                                <p class="text-[0.6875rem] text-on-surface-variant">{{ $doc->project_name }}</p>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span
                                class="px-3 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-wider {{ $tm['bg'] }}">{{ ucfirst($doc->document_type) }}</span>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-semibold text-on-surface">
                                {{ \Carbon\Carbon::parse($doc->created_at)->format('M d, Y') }}
                            </p>
                            <p class="text-[0.65rem] text-on-surface-variant">By {{ $doc->uploader ?? 'Unknown' }}</p>
                        </div>
                        <div class="col-span-1 text-xs text-on-surface">{{ $sizeLabel }}</div>
                        <div class="col-span-1 text-center">
                            <span
                                class="bg-surface-container-lowest border border-outline-variant/30 px-2 py-0.5 rounded text-[0.65rem] font-bold text-on-surface-variant">v{{ $doc->version_number ?? 1 }}</span>
                        </div>
                        <div class="col-span-1 flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="event.stopPropagation()">
                            <a href="{{ route('documents.download', $doc->id) }}"
                                class="p-2 hover:bg-primary/20 text-primary transition-colors rounded-lg">
                                <span class="material-symbols-outlined text-xl">download</span>
                            </a>
                            @can('delete documents')
                                <form method="POST" action="{{ route('documents.destroy', $doc->id) }}"
                                    onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-error/10 text-error transition-colors rounded-lg">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 block mb-4"
                            style="font-variation-settings:'FILL' 1;">folder_open</span>
                        <p class="text-on-surface-variant text-sm mb-4">No documents uploaded yet.</p>
                        @can('upload documents')
                            <button onclick="openUploadDrawer()"
                                class="px-6 py-3 bg-primary text-on-primary text-sm font-bold rounded-xl active:scale-95 transition-transform">+
                                Upload first document</button>
                        @endcan
                    </div>
                @endforelse
            </div>

            @if($documents instanceof \Illuminate\Pagination\LengthAwarePaginator && $documents->hasPages())
                <div class="px-6 py-4 bg-surface-container-low/50 border-t border-white/5 flex justify-between items-center">
                    <span class="text-xs text-on-surface-variant">Showing {{ $documents->firstItem() }}–{{ $documents->lastItem() }}
                        of {{ $documents->total() }}</span>
                    <div class="flex gap-2">
                        @if(!$documents->onFirstPage())
                            <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md border border-outline-variant/20"
                                href="{{ $documents->previousPageUrl() }}">Previous</a>
                        @endif
                        @if($documents->hasMorePages())
                            <a class="px-3 py-1 text-xs font-bold bg-primary text-on-primary rounded-md"
                                href="{{ $documents->nextPageUrl() }}">Next</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-surface-container rounded-2xl p-14 text-center shadow-lg">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 mb-4 block">folder_off</span>
            <p class="text-base font-bold text-on-surface mb-2">Documents module needs setup</p>
            <p class="text-sm text-on-surface-variant mb-6">Run the migration to activate document uploads.</p>
            <code class="bg-surface-container-highest px-4 py-2 rounded-lg text-sm text-primary">php artisan migrate</code>
        </div>
    @endif

    {{-- FAB --}}
    @if($hasColumns)
        @can('upload documents')
            <div class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 group">
                <span
                    class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0 bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10">Upload
                    Document</span>
                <button onclick="openUploadDrawer()"
                    class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200"
                    aria-label="Upload Document">
                    <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">upload_file</span>
                </button>
            </div>
        @endcan

        {{-- Overlay --}}
        <div id="doc-drawer-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" onclick="closeDocDrawer()"></div>

        {{-- Document Drawer --}}
        <div id="doc-drawer"
            class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] flex flex-col border-l border-white/5">
            {{-- Header --}}
            <div class="px-6 py-6 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary" id="dd-header-icon">article</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-on-surface font-headline leading-tight" id="dd-title">Document Details
                        </h2>
                        <p class="text-[0.65rem] text-on-surface-variant uppercase tracking-widest font-bold" id="dd-subtitle">
                            Details</p>
                    </div>
                </div>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-variant transition-colors text-on-surface-variant"
                    onclick="closeDocDrawer()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- View body --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-8" id="dd-view-body">
                <section>
                    <h3 class="text-[0.65rem] uppercase tracking-[0.2em] font-black text-on-surface-variant mb-4">File Metadata
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-surface-container rounded-lg border border-outline-variant/10">
                            <p class="text-[0.6rem] text-on-surface-variant uppercase font-bold mb-1">Category</p>
                            <p class="text-sm font-semibold text-on-surface" id="dd-type">—</p>
                        </div>
                        <div class="p-3 bg-surface-container rounded-lg border border-outline-variant/10">
                            <p class="text-[0.6rem] text-on-surface-variant uppercase font-bold mb-1">File Size</p>
                            <p class="text-sm font-semibold text-on-surface" id="dd-size">—</p>
                        </div>
                        <div class="p-3 bg-surface-container rounded-lg border border-outline-variant/10">
                            <p class="text-[0.6rem] text-on-surface-variant uppercase font-bold mb-1">Project</p>
                            <p class="text-sm font-semibold text-on-surface" id="dd-project">—</p>
                        </div>
                        <div class="p-3 bg-surface-container rounded-lg border border-outline-variant/10">
                            <p class="text-[0.6rem] text-on-surface-variant uppercase font-bold mb-1">Uploaded</p>
                            <p class="text-sm font-semibold text-on-surface" id="dd-date">—</p>
                        </div>
                        <div class="col-span-2 p-3 bg-surface-container rounded-lg border border-outline-variant/10">
                            <p class="text-[0.6rem] text-on-surface-variant uppercase font-bold mb-1">Uploaded by</p>
                            <p class="text-sm font-semibold text-on-surface" id="dd-uploader">—</p>
                        </div>
                    </div>
                </section>
                <section>
                    <h3 class="text-[0.65rem] uppercase tracking-[0.2em] font-black text-on-surface-variant mb-4">Version</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        <div>
                            <p class="text-xs font-bold text-on-surface">v<span id="dd-version">1</span> — Current Version</p>
                            <p class="text-[0.65rem] text-on-surface-variant">Latest revision</p>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Upload body --}}
            <div class="flex-1 overflow-y-auto p-6 hidden" id="dd-upload-body">
                <form id="dd-upload-form" method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Document
                                Name <span class="text-red-400">*</span></label>
                            <input type="text" name="document_name" required placeholder="e.g. Structural Blueprint Floor 1-10"
                                class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Project
                                    <span class="text-red-400">*</span></label>
                                <select name="project_id" required
                                    class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                                    <option value="">— Select project —</option>
                                    @foreach($projects as $p)<option value="{{ $p->id }}">{{ $p->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Type
                                    <span class="text-red-400">*</span></label>
                                <select name="document_type" required
                                    class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                                    <option value="blueprint">Blueprint</option>
                                    <option value="contract">Contract</option>
                                    <option value="permit">Permit</option>
                                    <option value="report">Report</option>
                                    <option value="photo">Photo</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">File
                                <span class="text-red-400">*</span></label>
                            <label
                                class="flex flex-col items-center justify-center gap-2 p-8 border-2 border-dashed border-white/10 rounded-xl hover:border-primary/40 hover:bg-primary/5 transition-all cursor-pointer">
                                <span class="material-symbols-outlined text-3xl text-on-surface-variant">upload_file</span>
                                <span class="text-xs font-bold text-on-surface-variant">Click to choose file or drag &
                                    drop</span>
                                <span class="text-[10px] text-on-surface-variant/60">PDF, DOC, DWG, images — max 50MB</span>
                                <input type="file" name="file" required accept=".pdf,.doc,.docx,.dwg,.jpg,.jpeg,.png,.xlsx,.zip"
                                    class="hidden">
                            </label>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief description of this document..."
                                class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500 resize-none"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- View footer --}}
            <div class="p-6 bg-surface-container border-t border-white/5 flex gap-3" id="dd-footer-view">
                <a id="dd-download-btn" href="#"
                    class="flex-1 bg-primary text-on-primary py-3 rounded-xl text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:brightness-110 transition-all">
                    <span class="material-symbols-outlined text-sm">download</span> Download
                </a>
                <button onclick="closeDocDrawer()"
                    class="bg-surface-container-highest text-on-surface border border-outline-variant/30 w-12 h-12 rounded-xl flex items-center justify-center hover:bg-surface-bright transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Upload footer --}}
            @can('upload documents')
                <div class="p-6 bg-surface-container border-t border-white/5 flex gap-3 hidden" id="dd-footer-upload">
                    <button type="submit" form="dd-upload-form"
                        class="flex-1 bg-primary text-on-primary py-3 rounded-xl text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">upload</span> Upload Document
                    </button>
                    <button type="button" onclick="closeDocDrawer()"
                        class="bg-surface-container-highest text-on-surface border border-outline-variant/30 w-12 h-12 rounded-xl flex items-center justify-center hover:bg-surface-bright transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            @endcan
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        const docsBase = @json(url('/documents'));
        window.__ddMode = null;
        window.__ddDocId = null;

        function openDocDrawer(row, id, name, type, project, uploader, date, size, version) {
            window.__ddMode = 'view'; window.__ddDocId = id;
            document.querySelectorAll('.doc-row').forEach(r => r.classList.remove('doc-row-active'));
            if (row) row.classList.add('doc-row-active');
            document.getElementById('dd-title').textContent = name;
            document.getElementById('dd-subtitle').textContent = type.toUpperCase() + ' — ' + project;
            document.getElementById('dd-type').textContent = type.charAt(0).toUpperCase() + type.slice(1);
            document.getElementById('dd-project').textContent = project;
            document.getElementById('dd-uploader').textContent = uploader;
            document.getElementById('dd-date').textContent = date;
            document.getElementById('dd-size').textContent = size;
            document.getElementById('dd-version').textContent = version;
            document.getElementById('dd-download-btn').href = docsBase + '/' + id + '/download';
            document.getElementById('dd-view-body').classList.remove('hidden');
            document.getElementById('dd-upload-body').classList.add('hidden');
            document.getElementById('dd-footer-view').classList.remove('hidden');
            document.getElementById('dd-footer-upload')?.classList.add('hidden');
            _openDocDrw();
        }
        function openUploadDrawer() {
            window.__ddMode = 'upload'; window.__ddDocId = null;
            document.querySelectorAll('.doc-row').forEach(r => r.classList.remove('doc-row-active'));
            document.getElementById('dd-title').textContent = 'Upload Document';
            document.getElementById('dd-subtitle').textContent = 'Add a file to the repository';
            document.getElementById('dd-view-body').classList.add('hidden');
            document.getElementById('dd-upload-body').classList.remove('hidden');
            document.getElementById('dd-footer-view').classList.add('hidden');
            document.getElementById('dd-footer-upload')?.classList.remove('hidden');
            _openDocDrw();
        }
        function _openDocDrw() {
            document.getElementById('doc-drawer').classList.add('open');
            document.getElementById('doc-drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeDocDrawer() {
            document.getElementById('doc-drawer').classList.remove('open');
            document.getElementById('doc-drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
            document.querySelectorAll('.doc-row').forEach(r => r.classList.remove('doc-row-active'));
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDocDrawer(); });
    </script>
@endsection