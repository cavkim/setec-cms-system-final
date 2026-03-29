@extends('layouts.app')
@section('title', 'Budget Categories')
@section('page-title', 'Budget Categories')

@section('content')
    <div class="w-full">
        {{-- Header with Back Button --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('budget.index') }}"
                class="flex items-center gap-1 text-primary hover:text-primary-container transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="text-sm font-semibold">Back to Budget</span>
            </a>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <h1 class="text-3xl font-bold text-white">Manage Budget Categories</h1>
            @can('create expenses')
                <button onclick="openAddCategoryModal()"
                    class="px-6 py-3 bg-primary text-on-primary text-sm font-bold rounded-xl hover:scale-105 active:scale-95 transition-transform">
                    <span class="material-symbols-outlined align-middle text-lg"
                        style="font-variation-settings:'FILL' 1;">add</span>
                    Add Category
                </button>
            @endcan
        </div>

        @if(session('success'))
            <script>document.addEventListener('DOMContentLoaded', () => toast('{{ session('success') }}', 'success'))</script>
        @endif

        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Name
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Color
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Description</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Created
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($categories as $category)
                            <tr class="hover:bg-white/[0.03] transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-on-surface">{{ $category->category_name }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg border border-white/10"
                                            style="background-color: {{ $category->color_hex }}"></div>
                                        <code
                                            class="text-xs font-mono text-on-surface-variant">{{ $category->color_hex }}</code>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">
                                    {{ $category->description ? \Illuminate\Support\Str::limit($category->description, 40) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">
                                    {{ $category->created_at ? $category->created_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @can('create expenses')
                                            <button
                                                onclick="openEditCategoryModal({{ $category->id }},'{{ addslashes($category->category_name) }}','{{ $category->color_hex }}','{{ addslashes($category->description ?? '') }}')"
                                                class="px-3 py-1 text-xs font-bold bg-primary-container/20 text-primary rounded-lg hover:bg-primary-container/30 transition-colors">
                                                Edit
                                            </button>
                                            <form action="{{ route('budget.categories.destroy', $category) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Delete this category? Expenses with this category will not be affected.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs font-bold bg-error-container/20 text-error rounded-lg hover:bg-error-container/30 transition-colors">
                                                    Archive
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/30 mb-3 block"
                                        style="font-variation-settings:'FILL' 1;">category</span>
                                    <p class="text-on-surface-variant text-sm mb-4">No budget categories yet</p>
                                    @can('create expenses')
                                        <button onclick="openAddCategoryModal()"
                                            class="px-6 py-3 bg-primary text-on-primary text-sm font-bold rounded-lg hover:scale-105 active:scale-95 transition-transform">
                                            Create your first category
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($categories->hasPages())
                <div
                    class="px-6 py-4 bg-surface-container-high/50 border-t border-white/5 flex justify-between items-center gap-4">
                    <div class="text-xs text-on-surface-variant">
                        Showing <span class="font-semibold">{{ $categories->firstItem() }}</span> to
                        <span class="font-semibold">{{ $categories->lastItem() }}</span> of
                        <span class="font-semibold">{{ $categories->total() }}</span> categories
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        @if($categories->onFirstPage())
                            <button disabled
                                class="px-3 py-1 text-xs font-bold bg-surface-container text-on-surface-variant rounded-lg opacity-50 cursor-not-allowed">
                                ← Previous
                            </button>
                        @else
                            <a href="{{ $categories->previousPageUrl() }}"
                                class="px-3 py-1 text-xs font-bold bg-surface-container text-on-surface hover:bg-white/5 rounded-lg transition-colors">
                                ← Previous
                            </a>
                        @endif

                        <div class="flex gap-1">
                            @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                @if($page == $categories->currentPage())
                                    <button
                                        class="px-2 py-1 text-xs font-bold bg-primary text-on-primary rounded-lg">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-2 py-1 text-xs font-bold bg-surface-container text-on-surface hover:bg-white/5 rounded-lg transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($categories->hasMorePages())
                            <a href="{{ $categories->nextPageUrl() }}"
                                class="px-3 py-1 text-xs font-bold bg-primary text-on-primary rounded-lg hover:scale-105 active:scale-95 transition-transform">
                                Next →
                            </a>
                        @else
                            <button disabled
                                class="px-3 py-1 text-xs font-bold bg-surface-container text-on-surface-variant rounded-lg opacity-50 cursor-not-allowed">
                                Next →
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Add/Edit Category Modal --}}
    <div id="category-modal-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden"
        onclick="if(event.target===this)closeCategoryModal()"></div>

    <div id="category-modal"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface-container rounded-2xl shadow-2xl z-50 hidden w-full max-w-md p-6 border border-white/10">

        <div class="flex justify-between items-start mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary">Budget Category</p>
                <h2 class="text-2xl font-bold text-white font-headline mt-1" id="cm-title">Add Category</h2>
            </div>
            <button class="p-2 text-on-surface-variant hover:text-white hover:bg-white/5 rounded-full transition-all"
                onclick="closeCategoryModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="category-form" method="POST" action="{{ route('budget.categories.store') }}">
            @csrf
            <input type="hidden" id="cm-method" value="POST">
            <input type="hidden" id="cm-category-id" value="">

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Category Name</label>
                    <input type="text" id="cm-name" name="category_name" placeholder="e.g., Labor, Materials, Equipment"
                        class="w-full px-4 py-2 bg-surface-container-high border border-white/10 rounded-lg text-on-surface placeholder:text-on-surface-variant focus:border-primary focus:outline-none"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Color</label>
                    <div class="flex gap-3">
                        <input type="color" id="cm-color" name="color_hex" value="#4D8EFF"
                            class="w-16 h-10 rounded-lg cursor-pointer border border-white/10">
                        <input type="text" id="cm-color-text" name="color_hex_text" placeholder="#4D8EFF"
                            class="flex-1 px-4 py-2 bg-surface-container-high border border-white/10 rounded-lg text-on-surface font-mono text-sm focus:border-primary focus:outline-none"
                            readonly>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1">Choose a color for visual categorization</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Description (Optional)</label>
                    <textarea id="cm-desc" name="description" placeholder="Brief description of this category..." rows="3"
                        class="w-full px-4 py-2 bg-surface-container-high border border-white/10 rounded-lg text-on-surface placeholder:text-on-surface-variant focus:border-primary focus:outline-none"></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeCategoryModal()"
                    class="flex-1 px-4 py-2 bg-surface-container-high text-on-surface font-bold rounded-lg hover:bg-white/5 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-primary text-on-primary font-bold rounded-lg hover:scale-102 active:scale-95 transition-transform">
                    Save Category
                </button>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            const colorInput = document.getElementById('cm-color');
            const colorText = document.getElementById('cm-color-text');

            function openAddCategoryModal() {
                document.getElementById('cm-title').textContent = 'Add Category';
                document.getElementById('cm-method').value = 'POST';
                document.getElementById('cm-category-id').value = '';
                document.getElementById('category-form').action = '{{ route("budget.categories.store") }}';
                document.getElementById('category-form').method = 'POST';
                document.getElementById('category-form').innerHTML = '@csrf' + document.getElementById('category-form').innerHTML;

                document.getElementById('cm-name').value = '';
                document.getElementById('cm-color').value = '#4D8EFF';
                document.getElementById('cm-color-text').value = '#4D8EFF';
                document.getElementById('cm-desc').value = '';

                document.getElementById('category-modal-overlay').classList.remove('hidden');
                document.getElementById('category-modal').classList.remove('hidden');
                document.getElementById('cm-name').focus();
            }

            function openEditCategoryModal(id, name, color, desc) {
                document.getElementById('cm-title').textContent = 'Edit Category';
                document.getElementById('cm-method').value = 'PUT';
                document.getElementById('cm-category-id').value = id;
                document.getElementById('category-form').action = `/budget/categories/${id}`;

                // Set form method to PUT
                const form = document.getElementById('category-form');
                form.method = 'POST'; // Laravel requires POST for PUT
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PUT';

                document.getElementById('cm-name').value = name;
                document.getElementById('cm-color').value = color;
                document.getElementById('cm-color-text').value = color;
                document.getElementById('cm-desc').value = desc;

                document.getElementById('category-modal-overlay').classList.remove('hidden');
                document.getElementById('category-modal').classList.remove('hidden');
                document.getElementById('cm-name').focus();
            }

            function closeCategoryModal() {
                document.getElementById('category-modal-overlay').classList.add('hidden');
                document.getElementById('category-modal').classList.add('hidden');
            }

            // Update color text when color picker changes
            colorInput.addEventListener('change', function () {
                colorText.value = this.value.toUpperCase();
            });

            // Close modal on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeCategoryModal();
            });
        </script>
    @endsection
@endsection