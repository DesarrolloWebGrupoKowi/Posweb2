@props([
    'id' => null,
    'url' => '#',
    'title' => 'Ver registro',
    'icon' => 'eye',
    'label' => 'Ver',
    'class' => 'btn-table-edit',
    'target' => '_self',
])

<a
    href="{{ $id ? $url . '/' . $id : $url }}"
    {{ $attributes->merge([
        'class' => 'btn-table-action ' . $class,
        'title' => $title,
        'target' => $target,
    ]) }}
>
    <i class="bi bi-{{ $icon }}"></i> {{ $label }}
</a>
