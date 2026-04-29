{{-- <!-- Básico -->
<x-filters.inputs.number-input
    name="id_movimiento"
    label="ID Movimiento"
    placeholder="Tipo de movimiento"
/>

<!-- Con valor mínimo y máximo -->
<x-filters.inputs.number-input
    name="id_usuario"
    label="ID Usuario"
    placeholder="ID del usuario"
    :min="1"
    :max="9999"
/>

<!-- Con step personalizado (decimales) -->
<x-filters.inputs.number-input
    name="cantidad"
    label="Cantidad"
    placeholder="Cantidad mínima"
    step="0.01"
    :min="0"
/>

<!-- Con valor por defecto -->
<x-filters.inputs.number-input
    name="id_caja"
    label="ID Caja"
    placeholder="ID de caja"
    :value="1"
/>

<!-- Versión compacta -->
<x-filters.inputs.number-input
    name="id_movimiento"
    label="ID Movimiento"
    compact="true"
/>

<!-- Con atributos adicionales -->
<x-filters.inputs.number-input
    name="id_movimiento"
    label="ID Movimiento"
    placeholder="Tipo de movimiento"
    required
    disabled
/> --}}
@props([
    'name',
    'label',
    'placeholder' => '',
    'value' => null,
    'id' => null,
    'compact' => false,
    'min' => null,
    'max' => null,
    'step' => '1',
])

<div class="col-12 col-md-6 col-lg-3">
    <div
        class="input-group {{ $compact ? 'input-group-sm' : '' }}"
        style="width: 100%;"
    >
        <span class="input-group-text border-gray-300 bg-gray-100">
            {{ $label }}
        </span>
        <input
            type="number"
            class="form-control form-control-sm border-gray-300"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            value="{{ $value ?? request($name) }}"
            placeholder="{{ $placeholder }}"
            @if ($min) min="{{ $min }}" @endif
            @if ($max) max="{{ $max }}" @endif
            step="{{ $step }}"
            {{ $attributes }}
        >
    </div>
</div>
