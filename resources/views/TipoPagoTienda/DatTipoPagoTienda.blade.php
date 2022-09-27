@extends('plantillaBase.masterblade')
@section('title', 'Tipo de Pago por Tienda')
@section('contenido')
<div class="container mb-3">
    <h2 class="card shadow titulo">Tipos de Pago por Tienda</h2>
</div>
<div class="container">
    @include('Alertas.Alertas')
</div>
<div class="container mb-3">
    <form id="formTipoPagoTienda" action="/DatTipoPagoTienda">
        <div class="row">
            <div class="col-4">
                <select name="idTienda" id="idTienda" class="form-select shadow">
                    @foreach ($tiendas as $tienda)
                    <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{
                        $tienda->NomTienda }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="container">
    <div class="accordion shadow" id="accordionExample">
        @foreach ($tiposPagoTienda as $tipoPagoTienda)
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
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
                                    <button {!! $tipoPagoTienda->TiposPago->count() == 0 ? 'hidden' : '' !!} class="btn
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
                                        <input class="form-check-input me-1" type="checkbox" name="chkIdTipoPagoAdd[]"
                                            value="{{ $tPagoFaltante->IdTipoPago }}">
                                        {{ $tPagoFaltante->NomTipoPago }}
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                                <div class="mt-2 d-flex justify-content-center">
                                    <button {!! $tiposPagoFaltantes->count() == 0 ? 'hidden' : '' !!} class="btn
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

        idTienda.addEventListener('change', function(){
            formTipoPagoTienda.submit();
        });
</script>
@endsection