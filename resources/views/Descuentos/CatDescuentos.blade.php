@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Craer de Descuento')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Craer de Descuento',
                    'options' => [['name' => 'CATÁLOGO DE DESCUENTOS', 'value' => '/VerDescuentos']],
                ])
                <div>
                    <a href="/VerDescuentos" class="btn btn-sm btn-dark" title="Ver descuentos">
                        Ver descuentos @include('components.icons.list')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>


        <div class="w-auto card border-0 p-4" style="border-radius: 10px">
            <form id="formPaquete" action="/GuardarDescuento" method="POST">
                @csrf
                <div class="d-flex flex-wrap gap-2 gap-md-3">
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Nombre del descuento</label>
                        <input class="form-control rounded" style="line-height: 18px" type="text" name="nomDescuento"
                            id="nomDescuento" placeholder="Nombre de descuento" required value="{{ old('nomDescuento') }}"
                            autofocus>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Tipo descuento</label>
                        <select class="form-select rounded" style="line-height: 18px" name="tipoDescuento"
                            id="tipoDescuento" required value="{{ old('tipoDescuento') }}">
                            <option value="">Seleccione tipo descuento</option>
                            @foreach ($tiposdescuentos as $td)
                                <option value="{{ $td->IdTipoDescuento }}">{{ $td->NomTipoDescuento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Tiendas</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda"
                            value="{{ old('idTienda') }}">
                            <option value="">Seleccione una tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Plazas</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idPlaza" id="idPlaza"
                            value="{{ old('idPlaza') }}">
                            <option value="">Seleccione una plaza</option>
                            @foreach ($plazas as $plaza)
                                <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Fecha inicio</label>
                        <input class="form-control rounded" style="line-height: 18px" type="date" name="fechaInicio"
                            id="fechaInicio"required value="{{ old('fechaInicio') }}">
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Fecha fin</label>
                        <input class="form-control rounded" style="line-height: 18px" type="date" name="fechaFin"
                            id="fechaFin" required value="{{ old('fechaFin') }}">
                    </div>
                </div>
                {{-- <div class="col-11 d-flex justify-content-end">
                    <button id="btnGenerarObject" class="btn btn-warning">
                        <i class="fa fa-save"></i> Generar Descuento
                    </button>
                </div> --}}
                <div class="d-flex mt-4 justify-content-end">
                    <button id="btnGenerarObject" class="btn btn-warning">
                        Generar Descuento
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection
