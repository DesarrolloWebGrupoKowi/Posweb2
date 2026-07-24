@props([
    'id',
    'modal' => 'ModalEditar',
    'title' => 'Editar registro',
    'icon' => 'pencil',
    'label' => 'Ver',
    'class' => 'btn-table-edit',
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
