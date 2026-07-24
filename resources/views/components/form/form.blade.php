@props([
    'action' => null,
    'method' => 'GET',
    'id' => null,
])
<div class="border-bottom p-4">
    <form
        action="{{ $action ?? url()->current() }}"
        method="{{ $method }}"
        @if ($id) id="{{ $id }}" @endif
    >
        <div class="row g-3 align-items-end">
            {{ $slot }}
            @if (isset($buttons))
                <div class="col-12 col-md-auto">
                    <div class="d-flex gap-2">
                        {{ $buttons }}
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>
