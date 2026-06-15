@props([
    'id',
    'modal' => 'modalCambiarPassword',
    'title' => 'Cambiar contraseña',
    'icon' => 'key',
    'label' => null,
    'class' => 'btn-table-password',
])

<x-table.buttons.modal-button
    :id="$id"
    :modal="$modal"
    :title="$title"
    :icon="$icon"
    :label="$label"
    class="btn-table-action {{ $class }}"
    {{ $attributes }}
/>
