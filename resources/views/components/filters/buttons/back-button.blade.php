@props([
    'fallbackUrl' => null,
])

<div>
    <button
        type="button"
        class="btn btn-sm btn-outline-dark"
        onclick="goBack()"
        title="Regresar a la página anterior"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <line
                x1="19"
                y1="12"
                x2="5"
                y2="12"
            ></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span class="ms-1">Regresar</span>
    </button>

    <script>
        function goBack() {
            // Si hay una URL de fallback especificada y no hay historial previo
            if (document.referrer === '' && '{{ $fallbackUrl }}') {
                window.location.href = '{{ $fallbackUrl }}';
            } else {
                window.history.back();
            }
        }
    </script>
</div>
