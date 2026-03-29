<!-- @extends('layouts.app')
@section('title', 'Create Project')
@section('page-title', 'Create Project')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container rounded-2xl p-8 shadow-lg">
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-primary text-3xl"
                    style="font-variation-settings:'FILL' 1;">info</span>
                <h2 class="text-2xl font-bold text-white">Create Project</h2>
            </div>
            <p class="text-on-surface-variant text-sm">Projects are created and managed using an intuitive drawer interface for better workflow.</p>
        </div>

        <div class="bg-primary/10 border border-primary/30 rounded-xl p-6 mb-6">
            <p class="text-sm text-primary mb-4">
                <span class="font-semibold">To create a new project:</span>
            </p>
            <ol class="text-sm text-on-surface-variant space-y-2 list-decimal list-inside">
                <li>Go to the <a href="{{ route('projects.index') }}" class="text-primary hover:underline">Projects page</a></li>
                <li>Click the <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-sm"
                            style="font-varying-settings:'FILL' 1;">add_circle</span> <span class="text-primary">Plus button</span></span> in the bottom right</li>
                <li>Fill in the project details in the drawer</li>
                <li>Click "Create Project" to save</li>
            </ol>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('projects.index') }}" class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl text-center hover:bg-primary/90 transition-colors">
                Go to Projects
            </a>
            <button class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors"
                onclick="window.history.back()">
                Go Back
            </button>
        </div>

        <div class="mt-8 pt-8 border-t border-white/10">
            <p class="text-xs text-on-surface-variant">
                <span class="font-semibold">Note:</span> The project creation interface has been redesigned to provide a better user experience. All project management (create, edit, delete) is now handled through the drawer interface on the Projects page.
            </p>
        </div>
    </div>
</div>
@endsection -->