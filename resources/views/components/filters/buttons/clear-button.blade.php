@props(['url' => null, 'title' => 'Borrar filtros'])

<div class="col-auto">
    <a
        class="btn btn-outline-danger"
        href="{{ $url ?? url()->current() }}"
        title="{{ $title }}"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <line
                x1="18"
                y1="6"
                x2="6"
                y2="18"
            />
            <line
                x1="6"
                y1="6"
                x2="18"
                y2="18"
            />
        </svg>
    </a>
</div>
