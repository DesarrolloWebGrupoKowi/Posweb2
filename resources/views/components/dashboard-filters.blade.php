@props([
    'tiendas' => collect(), // Colección de tiendas
    // 'showReporte' => false, // Mostrar campo de reporte
    'showTodasTiendas' => true, // Mostrar opción "TODAS LAS TIENDAS"
    'tiendaSeleccionada' => null, // ID de tienda seleccionada
    'fechaDefault' => null, // Fecha por defecto
    'formId' => 'dateForm', // ID del formulario
    'tiendaSelectId' => 'tiendaSelect', // ID del select de tiendas
    'reporteSeleccionado' => null, // ID de reporte seleccionado
    'reporteOptions' => [
        // Opciones del campo reporte
        ['value' => 1, 'label' => 'CORTE TIENDA'],
        ['value' => 2, 'label' => 'CORTE DETALLADO'],
    ],
])

<form id="{{ $formId }}"
    action=""
    method="GET"
    class="col-12 col-lg d-lg-flex justify-content-end">
    <div class="row">
        {{-- Input hidden para las graficas de ventas diarias --}}
        <input type="hidden" name="tienda_id" value="{{ $tiendaSeleccionada }}">

        {{-- Campo de Tienda --}}
        <div class="col-12 col-md col-lg mb-2">
            <div class="input-group"
                style="width: 100%;">
                <span class="input-group-text bg-gray-100 border-gray-300"
                    style="width: 100px">Tienda</span>
                <select class="form-control form-control-sm border-gray-300"
                    id="{{ $tiendaSelectId }}"
                    name="tienda_id">
                    @if ($showTodasTiendas)
                        <option value="-1">TODAS LAS TIENDAS</option>
                    @endif
                    @foreach ($tiendas as $tienda)
                        <option value="{{ $tienda->IdTienda }}"
                            {{ request()->get('tienda_id') == $tienda->IdTienda || $tiendaSeleccionada == $tienda->IdTienda ? 'selected' : '' }}>
                            {{ $tienda->NomTienda }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Campo de Reporte (opcional) --}}
        <div class="col-12 col-md col-lg mb-2">
            <div class="input-group"
                style="width: 100%;">
                <span class="input-group-text bg-gray-100 border-gray-300"
                    style="width: 100px">Reporte</span>
                <select class="form-control form-control-sm border-gray-300"
                    id="reporte"
                    name="reporte">
                    @foreach ($reporteOptions as $option)
                        <option value="{{ $option['value'] }}"
                            {{ request()->get('reporte') == $option['value'] || $reporteSeleccionado == $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Campo de Fecha --}}
        <div class="col-12 col-md col-lg mb-2">
            <div class="input-group"
                style="width: 100%;">
                <span class="input-group-text bg-gray-100 border-gray-300"
                    style="width: 100px">Fecha</span>
                <input type="date"
                    class="form-control form-control-sm border-gray-300"
                    id="fecha_fin"
                    name="fecha_fin"
                    value="{{ request()->get('fecha_fin', $fechaDefault ?? date('Y-m-d')) }}"
                    autofocus>
            </div>
        </div>

        {{-- Botón de Búsqueda --}}
        <div class="col-12 col-md-auto">
            <div>
                <button type="submit"
                    class="btn btn-outline-dark bg-dark text-white w-100"
                    title="Buscar">
                    @include('components.icons.search')
                </button>
            </div>
        </div>
    </div>
</form>
