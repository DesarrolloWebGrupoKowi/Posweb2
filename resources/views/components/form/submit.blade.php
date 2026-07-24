@props([
    'text' => 'Buscar',
    'icon' => 'search',
    'class' => '',
])

<button
    type="submit"
    class="btn-gradient {{ $class }}"
    {{ $attributes }}
>
    @if ($icon)<i class="bi bi-{{ $icon }}"></i>@endif
    {{ $text }}
</button>
