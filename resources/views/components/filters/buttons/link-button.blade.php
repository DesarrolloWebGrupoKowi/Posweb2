@props([
    'href' => '#',
    'text' => 'Link',
    'icon' => null,
    'color' => 'dark', // dark, primary, secondary, success, danger, warning, info, light
    'size' => 'sm', // sm, md, lg
    'outline' => true,
    'title' => null,
    'target' => '_self',
    'class' => '',
])

@php
    // Clases base del botón
    $btnClass = 'btn btn-' . ($outline ? 'outline-' : '') . $color;
    $btnClass .= ' btn-' . $size;
    $btnClass .= ' ' . $class;

    // Título por defecto si no se proporciona
    $titleAttr = $title ?? $text;
@endphp

<a
    href="{{ $href }}"
    class="{{ $btnClass }}"
    title="{{ $titleAttr }}"
    target="{{ $target }}"
    {{ $attributes }}
>
    <span class="d-flex align-items-center gap-2">
        @if ($icon)
            @include($icon)
        @endif
        {{ $text }}
    </span>
</a>
