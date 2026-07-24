@props([
    'active' => false,
    'buttonId' => 'btnFiltrosAvanzados',
    'panelId' => 'filaFiltrosAvanzados',
])

<div class="d-flex align-items-center gap-2">
    <x-form.submit
        text="Filtrar"
        icon="funnel"
        class="flex-grow-1"
    />
    <x-form.clear url="{{ url()->current() }}" />
    <button
        type="button"
        id="{{ $buttonId }}"
        class="btn btn-sm d-flex align-items-center btn-animated gap-1"
        style="background: {{ $active ? '#e2e8f0' : '#f1f5f9' }}; color: #475569; border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; white-space: nowrap;"
        onclick="togglePanel('{{ $panelId }}', '{{ $buttonId }}')"
        title="Filtros avanzados"
    >
        <i class="bi bi-sliders"></i>
        @if ($active)
            <span
                style="background: #3b82f6; color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px;">●</span>
        @endif
    </button>
</div>

{{ $slot }}
