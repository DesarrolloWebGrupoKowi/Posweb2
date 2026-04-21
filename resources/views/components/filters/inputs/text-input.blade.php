@props([
    'name',
    'label',
    'placeholder' => '',
    'value' => null,
    'id' => null,
    'compact' => false,
])

<div class="col-12 col-md-6 col-lg-3">
    <div
        class="input-group {{ $compact ? 'input-group-sm' : '' }}"
        style="width: 100%;"
    >
        <span
            class="input-group-text border-gray-300 bg-gray-100"
        >
            {{ $label }}
        </span>
        <input
            type="text"
            class="form-control form-control-sm border-gray-300"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            value="{{ $value ?? request($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes }}
        >
    </div>
</div>
