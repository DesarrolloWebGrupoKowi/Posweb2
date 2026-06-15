@props([
    'id',
    'modal' => 'ModalEliminar',
    'title' => 'Eliminar registro',
    'icon' => 'trash',
    'label' => null,
    'class' => 'btn-table-delete',
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
