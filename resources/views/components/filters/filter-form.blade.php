@props([
    'action' => null,
    'method' => 'GET',
    'id' => 'filtrosForm',
])

<form
    class="d-flex align-items-start justify-content-between"
    action="{{ $action ?? url()->current() }}"
    method="{{ $method }}"
    id="{{ $id }}"
>
    <div class="w-100">
        {{ $slot }}
    </div>
    <div class="d-flex gap-2">
        {{ $buttons ?? '' }}
    </div>
</form>
