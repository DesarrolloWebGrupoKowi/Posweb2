@props([
    'name',
    'label',
    'icon' => null,
    'placeholder' => '',
    'value' => null,
    'id' => null,
    'col' => 'col-md-3',
    'autofocus' => false,
    'required' => false,
])

<div class="{{ $col }}">
    <label class="form-label" for="{{ $id ?? $name }}">
        @if ($icon)<i class="bi bi-{{ $icon }} me-1"></i>@endif
        {{ $label }}
        @if ($required)<span class="text-danger">*</span>@endif
    </label>
    <input
        type="text"
        class="form-control form-input"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ $value ?? request($name) }}"
        placeholder="{{ $placeholder }}"
        @if ($autofocus) autofocus @endif
        @if ($required) required @endif
        {{ $attributes }}
    >
</div>
