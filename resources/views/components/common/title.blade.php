@props(['titulo' => 'Reporte', 'icon' => null])

<div class="d-flex align-items-center gap-2">
    @if ($icon)
        <span class="fs-5">{{ $icon }}</span>
    @endif
    <h5 class="fw-semibold mb-0 text-gray-800">{{ $titulo }}</h5>
</div>
