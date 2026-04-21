@props([
    'items' => [],
    'columns' => 4,
    'gap' => 4,
])

<div class="row g-{{ $gap }}">
    {{ $slot }}
</div>
