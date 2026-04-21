@props([
    'name',
    'label',
    'value' => null,
    'width' => '100px',
    'id' => null,
    'required' => false,
    'compact' => false,
    'autofocus' => false,
])

<div class="col-12 col-md-6 col-lg-3">
    <div
        class="input-group {{ $compact ? 'input-group-sm' : '' }}"
        style="width: 100%;"
    >
        <span
            class="input-group-text border-gray-300 bg-gray-100"
            style="width: {{ $width }}"
        >
            {{ $label }}
        </span>
        <input
            type="date"
            class="form-control form-control-sm border-gray-300"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            value="{{ $value ?? request($name) }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $attributes }}
        >
    </div>
</div>
