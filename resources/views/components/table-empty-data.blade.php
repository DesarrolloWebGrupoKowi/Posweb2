@props([
    'colspan' => 7,
    'title' => 'Sin datos disponibles',
    'message' => 'No se encontraron registros con los filtros seleccionados',
    'icon' => 'inbox',
    'action' => false,
    'actionText' => 'Limpiar filtros',
    'actionUrl' => '#',
    'actionIcon' => 'x-circle',
])

<tr>
    <td
        colspan="{{ $colspan }}"
        class="py-5 text-center"
    >
        <div class="d-flex align-items-center justify-content-center empty-state-icon mx-auto mb-3">
            <i
                class="bi bi-{{ $icon }}"
                style="font-size: 28px; color: #94a3b8;"
            ></i>
        </div>
        <h6
            class="fw-semibold mb-1"
            style="color: #475569;"
        >{{ $title }}</h6>
        <p
            class="text-muted mb-3"
            style="font-size: 0.85rem;"
        >{{ $message }}</p>
        @if ($action)
            <a
                href="{{ $actionUrl }}"
                class="btn-clean"
            >
                <i class="bi bi-{{ $actionIcon }} me-1"></i>{{ $actionText }}
            </a>
        @endif
    </td>
</tr>
