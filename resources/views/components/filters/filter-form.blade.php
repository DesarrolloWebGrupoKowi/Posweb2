@props([
    'action' => null,
    'method' => 'GET',
    'id' => 'filtrosForm',
])

<form
    {{-- class="d-flex flex-column flex-xl-row align-items-start justify-content-between gap-2" --}}
    class="row g-2"
    action="{{ $action ?? url()->current() }}"
    method="{{ $method }}"
    id="{{ $id }}"
>
    <div class="col">
        {{ $slot }}
    </div>
    <div class="col-12 col-xxl-auto">
        <div class="d-flex gap-2">
            {{ $buttons ?? '' }}
        </div>
    </div>
</form>
