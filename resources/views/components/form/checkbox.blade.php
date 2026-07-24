@props([
    'name',
    'label',
    'checked' => false,
    'id' => null,
    'col' => 'col-md-3',
    'helperText' => null,
])

<div class="{{ $col }}">
    <div class="d-flex align-items-center gap-3" style="padding-bottom: 1px;">
        <input
            type="checkbox"
            class="form-check-input"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            {{ $checked ? 'checked' : '' }}
            {{ $attributes }}
        >
        <label class="form-label mb-0" for="{{ $id ?? $name }}">
            {{ $label }}
        </label>
        @if ($helperText)
            <small class="text-muted">{{ $helperText }}</small>
        @endif
    </div>
</div>
