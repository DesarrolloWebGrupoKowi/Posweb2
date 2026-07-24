@props([
    'id',
    'modal' => 'modalActivarUsuario',
    'title' => 'Activar registro',
    'icon' => 'toggle-on',
    'label' => null,
    'class' => 'btn-table-activate',
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
