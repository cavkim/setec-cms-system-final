@props(['items' => []])
<nav aria-label="Breadcrumb" class="text-xs text-slate-400">
    @foreach($items as $item)
        <span>{{ $item }}</span>@if(!$loop->last) <span>/</span> @endif
    @endforeach
</nav>
