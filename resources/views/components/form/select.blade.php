@props([
    'name',
    'label',
    'icon' => null,
    'options' => [],
    'placeholder' => 'Seleccione',
    'value' => null,
    'id' => null,
    'col' => 'col-md-3',
    'required' => false,
])

@php
    // Prioridad: old() > request() > value prop
    $selectedValue = old($name, request($name, $value));
@endphp

<div class="{{ $col }}">
    <label class="form-label" for="{{ $id ?? $name }}">
        @if ($icon)<i class="bi bi-{{ $icon }} me-1"></i>@endif
        {{ $label }}
        @if ($required)<span class="text-danger">*</span>@endif
    </label>
    <select
        class="form-select form-input"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        @if ($required) required @endif
        {{ $attributes }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                {{ $selectedValue == $optionValue ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
