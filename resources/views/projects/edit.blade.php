<!-- @extends('layouts.app')
@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container rounded-2xl p-8 shadow-lg">
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-secondary text-3xl"
                    style="font-variation-settings:'FILL' 1;">info</span>
                <h2 class="text-2xl font-bold text-white">Edit Project</h2>
            </div>
            <p class="text-on-surface-variant text-sm">Projects are created and managed using an intuitive drawer interface for better workflow.</p>
        </div>

        <div class="bg-secondary/10 border border-secondary/30 rounded-xl p-6 mb-6">
            <p class="text-sm text-secondary mb-4">
                <span class="font-semibold">To edit a project:</span>
            </p>
            <ol class="text-sm text-on-surface-variant space-y-2 list-decimal list-inside">
                <li>Go to the <a href="{{ route('projects.index') }}" class="text-secondary hover:underline">Projects page</a></li>
                <li>Click the <span class="material-symbols-outlined text-sm"
                        style="font-variation-settings:'FILL' 1;">edit</span> button next to the project you want to edit</li>
                <li>Or click anywhere on the project row to open it in the drawer</li>
                <li>Update the project details in the drawer</li>
                <li>Click "Save Changes" to save your updates</li>
            </ol>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('projects.index') }}" class="flex-1 bg-secondary text-on-secondary font-bold py-3 rounded-xl text-center hover:bg-secondary/90 transition-colors">
                Go to Projects
            </a>
            <button class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors"
                onclick="window.history.back()">
                Go Back
            </button>
        </div>

        <div class="mt-8 pt-8 border-t border-white/10">
            <p class="text-xs text-on-surface-variant">
                <span class="font-semibold">Note:</span> The project editing interface has been redesigned to provide a better user experience. All project management (create, edit, delete) is now handled through the drawer interface on the Projects page.
            </p>
        </div>
    </div>
</div>
@endsection -->