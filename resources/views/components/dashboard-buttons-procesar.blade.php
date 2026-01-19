@props([
    'corteTienda' => null, // Tienda actual
    'corteTiendaSolicitudes' => null, // Tienda actual
])

@if (request()->get('fecha_fin', date('Y-m-d')) != date('Y-m-d') &&
        Auth::id() == 11 &&
        !(count($corteTienda) == 0 && count($corteTiendaSolicitudes) == 0))
    <div class="btn-group">
        <a href="/procesarclientescontado/{{ request()->get('fecha_fin', date('Y-m-d')) }}/{{ request()->get('tienda_id') }}/-1"
            type="button"
            class="btn btn-sm btn-outline-dark"
            id="rotateButton">
            <span id="buttonIcon">
                @include('components.icons.cloud-up')
            </span>
            Procesar contado
        </a>
        <a href="/procesarclientesfacturas/{{ request()->get('fecha_fin', date('Y-m-d')) }}/{{ request()->get('tienda_id') }}/-1"
            type="button"
            class="btn btn-sm btn-outline-dark"
            id="rotateButtonFac">
            <span id="buttonIconFac">
                @include('components.icons.cloud-up')
            </span>
            Procesar facturas
        </a>
        {{-- <a href="/procesarclientesfacturas/{{ request()->get('fecha_fin', date('Y-m-d')) }}/{{ request()->get('tienda_id') }}/-1"
            type="button"
            class="btn btn-sm btn-outline-dark"
            id="rotateButtonFac">
            <span id="buttonIconFac">
                @include('components.icons.send')
            </span>
            Enviar ventas
        </a> --}}
    </div>
@endif
