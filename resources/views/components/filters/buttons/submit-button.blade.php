@props(['text' => 'Buscar', 'color' => 'dark'])

<div class="col-12 col-md-auto">
    <button
        type="submit"
        class="btn btn-outline-{{ $color }} bg-{{ $color }} w-100 text-white"
        title="{{ $text }}"
    >
        <span class="d-flex align-items-center gap-2">
            @include('components.icons.search')
            {{ $text }}
        </span>
    </button>
</div>
