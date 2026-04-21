@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'width' => '100px',
    'placeholder' => 'Seleccione',
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
            style="width: {{ $width }}"
        >
            {{ $label }}
        </span>
        <select
            class="form-control form-control-sm border-gray-300"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            {{ $attributes }}
        >
            <option value="">{{ $placeholder }}</option>
            @foreach ($options as $optionValue => $optionLabel)
                <option
                    value="{{ $optionValue }}"
                    {{ request($name) == $optionValue ? 'selected' : '' }}
                >
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
</div>
