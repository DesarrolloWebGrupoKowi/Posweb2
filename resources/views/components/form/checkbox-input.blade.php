@props(['name', 'label', 'icon' => 'eye', 'checked' => false, 'col' => 'col-md-2'])

<div class="{{ $col }}">
    <label
        class="form-label fw-medium mb-2"
        style="color: var(--text-secondary); font-size: 0.85rem;"
    >
        <i class="bi bi-{{ $icon }} me-1"></i>{{ $label }}
    </label>
    <div
        class="d-flex align-items-center gap-2 p-2"
        style="border: 1px solid var(--border-input); border-radius: 8px; height: 42px; cursor: pointer;"
        onclick="document.getElementById('{{ $name }}').click()"
    >
        <input
            class="form-check-input m-0"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            style="cursor: pointer; width: 16px; height: 16px; border: 2px solid var(--border-medium); border-radius: 3px; accent-color: var(--btn-blue-text);"
        >
        <label
            class="form-check-label m-0"
            for="{{ $name }}"
            style="color: var(--text-secondary); font-size: 0.85rem; cursor: pointer; user-select: none;"
        >
            {{ $checked ? 'Activado' : 'Desactivado' }}
        </label>
    </div>
</div>
