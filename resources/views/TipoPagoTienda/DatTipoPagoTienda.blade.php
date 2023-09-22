@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Tipo de Pago por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Tipos de Pago por Tienda'])
            <form class="d-flex align-items-center justify-content-end" id="formTipoPagoTienda" action="/DatTipoPagoTienda">
                <div class="form-group" style="min-width: 400px">
                    <label class="fw-bold text-secondary">Selecciona una tienda</label>
                    <select name="idTienda" id="idTienda" class="form-select">
                        @foreach ($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="accordion" id="accordionExample">
            @foreach ($tiposPagoTienda as $tipoPagoTienda)
                <div class="accordion-item" style="border-radius: 20px; overflow: hidden;">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            {{ $tipoPagoTienda->NomTienda }}
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show shadow" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="row">
                            <div class="col-6">
                                <div class="accordion-body">
                                    <form action="/RemoverDatTipoPagoTienda">
                                        <input type="hidden" name="idTienda" value="{{ $idTienda }}">
                                        <ul class="list-group">
                                            <li class="list-group-item bg-dark text-white">Tipos de Pago</li>
                                            @if ($tipoPagoTienda->TiposPago->count() == 0)
                                                <li class="list-group-item">
                                                    <i class="fa fa-plus"></i> Agrega un Tipo de Pago
                                                </li>
                                            @else
                                                @foreach ($tipoPagoTienda->TiposPago as $tPago)
                                                    <li class="list-group-item">
                                                        <input class="form-check-input me-1" type="checkbox"
                                                            name="chkIdTipoPagoRemove[]" value="{{ $tPago->IdTipoPago }}">
                                                        {{ $tPago->NomTipoPago }}
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="mt-2 d-flex justify-content-center">
                                            <button {!! $tipoPagoTienda->TiposPago->count() == 0 ? 'hidden' : '' !!}
                                                class="btn
                                        btn-danger">
                                                <i class="fa fa-minus-circle"></i> Remover
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="accordion-body">
                                    <form action="/AgregarDatTipoPagoTienda">
                                        <input type="hidden" name="idTienda" value="{{ $idTienda }}">
                                        <ul class="list-group">
                                            <li class="list-group-item bg-dark text-white">Agregar Tipos de Pago</li>
                                            @if ($tiposPagoFaltantes->count() == 0)
                                                <li class="list-group-item">
                                                    <i class="fa fa-check"></i> No Hay Tipos de Pago por Agregar
                                                </li>
                                            @else
                                                @foreach ($tiposPagoFaltantes as $tPagoFaltante)
                                                    <li class="list-group-item">
                                                        <input class="form-check-input me-1" type="checkbox"
                                                            name="chkIdTipoPagoAdd[]"
                                                            value="{{ $tPagoFaltante->IdTipoPago }}">
                                                        {{ $tPagoFaltante->NomTipoPago }}
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="mt-2 d-flex justify-content-center">
                                            <button {!! $tiposPagoFaltantes->count() == 0 ? 'hidden' : '' !!}
                                                class="btn
                                        btn-warning">
                                                <i class="fa fa-plus-circle"></i> Agregar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const formTipoPagoTienda = document.getElementById('formTipoPagoTienda');
        const idTienda = document.getElementById('idTienda');

        idTienda.addEventListener('change', function() {
            formTipoPagoTienda.submit();
        });
    </script>
@endsection
