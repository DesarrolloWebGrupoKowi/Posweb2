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
    'listId' => null,
])

@php
    // Prioridad: old() > request() > value prop
    $selectedValue = old($name, request($name, $value));
    $datalistId = $listId ?? $name . '_list';
@endphp

<div class="{{ $col }}">
    <label
        class="form-label"
        for="{{ $id ?? $name }}"
    >
        @if ($icon)
            <i class="bi bi-{{ $icon }} me-1"></i>
        @endif
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input
        class="form-control form-input"
        type="text"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        list="{{ $datalistId }}"
        value="{{ $selectedValue }}"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        @if ($required) required @endif
        {{ $attributes->whereDoesntStartWith('class') }}
    >
    <datalist id="{{ $datalistId }}">
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
    </datalist>
</div>
