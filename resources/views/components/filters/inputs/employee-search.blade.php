@props([
    'checkboxName' => 'chkNomina',
    'numberName' => 'numNomina',
    'checkboxChecked' => false,
    'numberValue' => null,
    'compact' => false,
])

<div class="col-12 col-md-6 col-lg-3">
    <div
        class="input-group {{ $compact ? 'input-group-sm' : '' }}"
        style="width: 100%;"
    >
        <span class="input-group-text border-gray-300 bg-gray-100">Buscar Empleado</span>
        <span class="input-group-text">
            <input
                {{ $checkboxChecked ? 'checked' : '' }}
                class="form-check-input mt-0"
                type="checkbox"
                name="{{ $checkboxName }}"
                id="{{ $checkboxName }}"
            >
        </span>
        <input
            {{ !$checkboxChecked ? 'disabled' : '' }}
            class="form-control form-control-sm border-gray-300"
            type="number"
            name="{{ $numberName }}"
            id="{{ $numberName }}"
            value="{{ $numberValue }}"
            placeholder="# Nómina"
        >
    </div>
</div>
