@props(['id', 'modal', 'title' => '', 'icon' => '', 'label' => null, 'class' => 'btn-table-action'])

<button
    {{ $attributes->merge(['class' => $class]) }}
    data-bs-toggle="modal"
    data-bs-target="#{{ $modal }}{{ $id }}"
    title="{{ $title }}"
>
    @if ($icon)
        <i class="bi bi-{{ $icon }}"></i>
    @endif
    {{ $label }}
</button>
