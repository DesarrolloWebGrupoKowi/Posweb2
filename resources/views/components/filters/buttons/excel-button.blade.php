@props(['route', 'params' => []])

<form
    action="{{ $route }}"
    method="GET"
>
    @foreach ($params as $key => $value)
        <input
            type="hidden"
            name="{{ $key }}"
            value="{{ $value }}"
        >
    @endforeach
    <button class="btn btn-sm btn-outline-dark btn-outline-dark-green">
        <span class="d-flex align-items-center gap-2">
            @include('components.icons.excel')
            Descargar
        </span>
    </button>
</form>
