@extends('plantillaBase.masterblade')
@section('title','Ligar Clientes por Solicitud')
<style>
    i {
        cursor: pointer;
    }

    #constancia{
        font-size: 22px;
        color: black;
    }

    #constancia:hover{
        color: orange;
    }
</style>
@section('contenido')
<div class="container-fluid">
    <div class="row mb-3 d-flex justify-content-end">
        <button class="col-auto btn btn-warning me-5 position-relative shadow" data-bs-toggle="modal" data-bs-target="#Notificaciones">
            <i class="fa fa-bell"></i> Notificaciones
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $notificaciones }}
            </span>
        </button>
    </div>
</div>
<div class="container mb-3">
    <div class="row d-flex justify-content-center">
        <h2 class=" col-auto card shadow">Ligar Clientes PosWeb2</h2>
    </div>
</div>
<div class="container">
    @include('Alertas.Alertas')
</div>
<div class="container">
    <table class="table table-light table-striped table-responsive table-sm shadow">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Tienda</th>
                <th>Cliente</th>
                <th>RFC</th>
                <th style="text-align: center">Solicitud</th>
                <th style="text-align: center">Constancia</th>
            </tr>
        </thead>
        <tbody>
            @if (empty($clientesPorLigar))
            <tr>
                <td colspan="5">No Hay Clientes Por Ligar!</td>
            </tr>
            @else
            @foreach ($clientesPorLigar as $cliente)
            <tr>
                <td>{{ strftime("%d %B %Y, %H:%M", strtotime($cliente->FechaSolicitud)) }}</td>
                <td>{{ $cliente->NomTienda }}</td>
                <td>{{ $cliente->NomCliente }}</td>
                <td>{{ $cliente->RFC }}</td>
                <td style="font-size: 20px; text-align:center">
                    <i class="fa fa-address-book" data-bs-toggle="modal"
                        data-bs-target="#ModalDatosFacturacion{{ $cliente->IdSolicitudFactura }}"></i>
                    @include('LigarClientes.ModalDatosFacturacion')
                </td>
                <td style="text-align: center">
                    @if (empty($cliente->NomConstancia))
                        No Tiene Constancia
                    @else
                    <a id="constancia" href="/VerConstanciaCliente/{{ $cliente->IdSolicitudFactura }}" target="_blank"><i class="fa fa-book"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@include('LigarClientes.Notificaciones')
@endsection