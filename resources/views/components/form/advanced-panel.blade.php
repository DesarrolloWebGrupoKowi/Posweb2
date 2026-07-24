@props([
    'active' => false,
    'id' => 'filaFiltrosAvanzados',
])

<div
    id="{{ $id }}"
    class="row g-3 align-items-end {{ $active ? '' : 'd-none' }} mt-3"
>
    {{ $slot }}
</div>
