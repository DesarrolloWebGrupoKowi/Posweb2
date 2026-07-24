@props([
    'url' => null,
    'text' => 'Limpiar',
    'icon' => 'x-circle',
])

<a
    href="{{ $url ?? url()->current() }}"
    class="btn-light-ghost"
    {{ $attributes }}
>
    @if ($icon)
        <i class="bi bi-{{ $icon }}"></i>
    @endif
    {{ $text }}
</a>
