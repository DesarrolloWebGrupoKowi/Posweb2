@props([
    'status' => true,
    'activeText' => 'Activo',
    'inactiveText' => 'Inactivo',
    'activeIcon' => 'bi-check-circle',
    'inactiveIcon' => 'bi-x-circle',
])

@if ($status)
    <span class="badge-status badge-active">
        <i class="{{ $activeIcon }} me-1"></i>{{ $activeText }}
    </span>
@else
    <span class="badge-status badge-inactive">
        <i class="{{ $inactiveIcon }} me-1"></i>{{ $inactiveText }}
    </span>
@endif
