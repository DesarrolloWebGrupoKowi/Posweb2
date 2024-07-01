@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud Factura ' . $solicitud->IdSolicitudFactura)
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Solicitud Factura',
                    'options' => [
                        [
                            'name' => 'Solicitudes Factura',
                            'value' => '/SolicitudesFactura',
                        ],
                    ],
                ])
                <div class="d-flex gap-2">
                    @if ($solicitud['ConstanciaSituacionFiscal'] != null)
                        <a href="{{ '/VerConstanciaCliente/' . $solicitud->IdSolicitudFactura }}" type="button"
                            class="btn btn-sm btn-dark" target="_blank" title="Finalizar solicitud">
                            <i class="fa fa-book"></i> Ver constancia
                        </a>
                    @endif
                    @if ($solicitud->Bill_To != null)
                        <a href="{{ '/SolicitudesFactura/Finalizar/' . $solicitud->Id }}" type="button"
                            class="btn btn-sm btn-dark" title="Finalizar solicitud">
                            <i class="fa fa-check" aria-hidden="true"></i> Finalizar solicitud
                        </a>
                    @endif
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-flex-none content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Id</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->IdSolicitudFactura }}"
                        disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Id Cliente Cloud </label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->IdClienteCloud ? $solicitud->IdClienteCloud : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Id Usuario Cliente </label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->IdUsuarioCliente ? $solicitud->IdUsuarioCliente : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Fecha</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->FechaSolicitud }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Nombre Cliente </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NomCliente }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Teléfono</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Telefono }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Correo</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Email }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">RFC</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->RFC }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Tipo Persona </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->TipoPersona }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Calle</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Calle }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Número Exterior </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NumExt }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Número Interior </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NumInt }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Colonia</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Colonia }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Codigo Postal </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->CodigoPostal }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Municipio </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Municipio }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Ciudad</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Ciudad }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Estado</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Estado }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">País</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->Pais }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Acción</label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }}" disabled>
                </div>
            </div>
        </div>

        {{-- <div class="content-table content-table-flex-none content-table-full card border-0 p-4"
            style="border-radius: 10px">
        </div> --}}

        <div class="content-table content-table-flex-none content-table-full card border-0 p-4"
            style="border-radius: 10px">
            {{-- <h2>Detalle de la Solicitud </h2> --}}
            <span class="fs-5 text-sm mb-2" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Detalle de la Solicitud
            </span>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Tienda</label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NomTienda }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Tipo De Pago </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NomTipoPago }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Banco </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NomBanco }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Numero Tarjeta </label>
                    <input type="text" class="form-control rounded" value="{{ $solicitud->NumTarjeta }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Bill To </label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->Bill_To ? $solicitud->Bill_To : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Uso CFDI </label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->UsoCFDI ? $solicitud->UsoCFDI : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary m-0" style="font-weight:500">Metódo Pago </label>
                    <input type="text" class="form-control rounded"
                        value="{{ $solicitud->MetodoPago ? $solicitud->MetodoPago : 'Sin dato' }}" disabled>
                </div>
            </div>

            {{-- <div class="card p-4 mt-4"> --}}
            <div class="content-table mt-4 content-table-full card">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Sitio</th>
                            <th>NomCliente</th>
                            <th>IdClienteCloud</th>
                            <th>RFC</th>
                            <th>Ship_To</th>
                            <th>Bill_To</th>
                            <th>Locacion</th>
                            <th>Ciudad</th>
                            <th class="rounded-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($clientes) <= 0)
                            <td colspan="8">No Hay Coincidencias!</td>
                        @else
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->Sitio }}</td>
                                    <td>{{ $cliente->NomCliente }}</td>
                                    <td>{{ $cliente->IdClienteCloud }}</td>
                                    <td>{{ $cliente->RFC }}</td>
                                    <td>{{ $cliente->Ship_To }}</td>
                                    <td>{{ $cliente->Bill_To }}</td>
                                    <td>{{ $cliente->Locacion }}</td>
                                    <td>{{ $cliente->Ciudad }}</td>
                                    <td>
                                        <a href="{{ '/SolicitudesFactura/Relacionar/' . $solicitud->Id . '/' . $cliente->Bill_To }}"
                                            class="btn-table">
                                            Relacionar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- </div> --}}

        </div>

    </div>
@endsection
