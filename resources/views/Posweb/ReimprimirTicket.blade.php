@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reimprimir Ticket')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Reimprimir Ticket - ' . $tienda->NomTienda])
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form action="/ImprimirTicket">
            <div class="container col-md-4 card p-4" style="place-items: center; border-radius: 20px">
                <div class="mb-3">
                    <div>
                        <label class="fw-bold text-secondary pb-1">Fecha ticket</label>
                        <input class="form-control" type="date" name="txtFecha" id="txtFecha"
                            value="{!! empty($fecha) ? $fechaHoy : $fecha !!}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div>
                        <label class="fw-bold text-secondary pb-1">Id ticket</label>
                        <input style="text-align: center" class="form-control" type="text" id="txtIdTicket"
                            name="txtIdTicket" placeholder="Ticket" size="4" value="{{ $idTicket }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div>
                        <button class="btn btn-dark-outline">
                            <span class="material-icons">print</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
