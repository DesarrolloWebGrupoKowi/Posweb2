@props(['name', 'label', 'checked' => false, 'col' => 'col-md-2'])

<div class="{{ $col }}">
    <div class="form-check">
        <input
            class="form-check-input"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            style="cursor: pointer; border-color: #94a3b8;"
        >
        <label
            class="form-check-label"
            for="{{ $name }}"
            style="color: #475569; font-size: 0.82rem; cursor: pointer;"
        >
            {{ $label }}
        </label>
    </div>
</div>
