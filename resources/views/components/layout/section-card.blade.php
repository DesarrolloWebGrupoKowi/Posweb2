@props(['padding' => 'p-4', 'class' => ''])

<div
    class="card {{ $padding }} {{ $class }} border-0"
    style="border-radius: 10px"
>
    {{ $slot }}
</div>
