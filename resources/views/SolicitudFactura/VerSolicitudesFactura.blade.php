@extends('plantillaBase.masterblade')
@section('title', 'Ver Solicitudes de Factura')
<style>
    #constancia{
        font-size: 20px;
        color: black;
    }

    #constancia:hover{
        color: orange;
    }
    i{
        cursor: pointer;
    }

    i:hover{
        color: orange;
    }
</style>
@section('contenido')
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-auto">
            <h2 class="titulo card shadow p-1">Solicitudes de Factura</h2>
        </div>
    </div>
    <form action="/VerSolicitudesFactura">
        <div class="row mb-1">
            <div class="col-3">
                <input type="date" class="form-control" name="fechaSolicitud" value="{{ $fechaSolicitud }}" required>
            </div>
            <div class="col-2">
                <button class="btn card shadow">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </div>
    </form>
    <table class="table table-responsive table-striped shadow">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>RFC</th>
                <th>Correo</th>
                <th style="text-align: center">Constancia</th>
                <th style="text-align: center">Estado de Facturación</th>
                <th>Subir Constancia</th>
            </tr>
        </thead>
        <tbody>
            @if ($solicitudesFactura->count() > 0)
            @foreach ($solicitudesFactura as $solicitudFactura)
                <tr>
                    <td>{{ strftime("%d %B %Y, %H:%M", strtotime($solicitudFactura->FechaSolicitud)) }}</td>
                    <td>{{ $solicitudFactura->NomCliente }}</td>
                    <td>{{ $solicitudFactura->RFC }}</td>
                    <td>{{ $solicitudFactura->Email }}</td>
                    <td style="text-align: center">
                        @if (empty($solicitudFactura->ConstanciaSituacionFiscal))
                            No Tiene Constancia
                        @else
                        <a id="constancia" href="/VerConstanciaCliente/{{ $solicitudFactura->IdSolicitudFactura }}" target="_blank"><i class="fa fa-book"></i></a>
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if (empty($solicitudFactura->IdClienteCloud))
                        <strong><i style="color: red" class="fa fa-exclamation-triangle"></i> Falta Ligar Cliente</strong>
                        @else
                        <strong><i class="fa fa-clock-o"></i> En Proceso de Facturación</strong>
                        @endif
                    </td>
                    <td>
                        @if (empty($solicitudFactura->ConstanciaSituacionFiscal) && empty($solicitudFactura->IdClienteCloud) && empty($solicitudFactura->Bill_To))
                        <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEditarSolicitud{{ $solicitudFactura->IdSolicitudFactura }}">
                            <i style="font-size: 20px;" class="fa fa-upload"></i>
                        </button>
                        @include('SolicitudFactura.ModalEditarSolicitud')
                        @endif
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="7">No Hay Solicitudes!</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection