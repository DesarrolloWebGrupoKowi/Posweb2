@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Descuentos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', [
                'titulo' => 'Catálogo de Descuentos',
                'options' => [['name' => 'Descuentos', 'value' => '/VerDescuentos']],
            ])
            <div>
                <a href="/VerDescuentos" class="btn btn-sm btn-dark" title="Ver descuentos">
                    <i class="fa fa-eye"></i> Ver descuentos
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>


        <div class="content-table content-table-full card p-4 mt-4" style="border-radius: 20px">
            {{-- <div class=""> --}}
            <form class="d-flex flex-wrap align-items-top justify-content-around gap-2" id="formPaquete"
                action="/GuardarDescuento" method="POST">
                @csrf
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Nombre del descuento</label>
                    <input class="form-control" type="text" name="nomDescuento" id="nomDescuento"
                        placeholder="Nombre de descuento" required value="{{ old('nomDescuento') }}" autofocus>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Tipo descuento</label>
                    <select class="form-select" name="tipoDescuento" id="tipoDescuento" required
                        value="{{ old('tipoDescuento') }}">
                        <option value="">Seleccione tipo descuento</option>
                        @foreach ($tiposdescuentos as $td)
                            <option value="{{ $td->IdTipoDescuento }}">{{ $td->NomTipoDescuento }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Tiendas</label>
                    <select class="form-select" name="idTienda" id="idTienda" value="{{ old('idTienda') }}">
                        <option value="">Seleccione una tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Plazas</label>
                    <select class="form-select" name="idPlaza" id="idPlaza" value="{{ old('idPlaza') }}">
                        <option value="">Seleccione una plaza</option>
                        @foreach ($plazas as $plaza)
                            <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Fecha inicio</label>
                    <input class="form-control" type="date" name="fechaInicio" id="fechaInicio"required
                        value="{{ old('fechaInicio') }}">
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Fecha fin</label>
                    <input class="form-control" type="date" name="fechaFin" id="fechaFin" required
                        value="{{ old('fechaFin') }}">
                </div>
                <div class="col-11 d-flex justify-content-end">
                    <button id="btnGenerarObject" class="btn btn-warning">
                        <i class="fa fa-save"></i> Generar Descuento
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection
