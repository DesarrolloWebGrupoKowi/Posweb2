@extends('plantillaBase.masterblade')
@section('title', 'Reimprimir Ticket')
@section('contenido')
    <div class="container card mb-3">
        <h2 class="titulo">Reimprimir Ticket - {{ $tienda->NomTienda }}</h2>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <form action="/ImprimirTicket">
        <div style="place-items: center" class="container col-md-4 card p-2 shadow">
            <div class="mb-3">
                <div>
                    <input class="form-control" type="date" name="txtFecha" id="txtFecha" value="{!! empty($fecha) ? $fechaHoy : $fecha !!}" required>
                </div>
            </div>
            <div class="mb-3">
                <div>
                    <input style="text-align: center" class="form-control" type="text" id="txtIdTicket" name="txtIdTicket" placeholder="Ticket" size="4" value="{{ $idTicket }}" required>
                </div>
            </div>
            <div class="mb-3">
                <div>
                    <button class="btn shadow">
                        <span class="material-icons">print</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection