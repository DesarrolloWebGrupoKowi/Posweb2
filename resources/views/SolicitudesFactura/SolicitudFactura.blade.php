@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud Factura ' . $solicitud->IdSolicitudFactura)
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', [
                'titulo' => 'Solicitud Factura',
                'options' => [
                    [
                        'name' => 'Solicitudes Factura',
                        'value' => '/SolicitudesFactura',
                    ],
                ],
            ])
            @if ($solicitud->Bill_To != null)
                <div class="">
                    <a href="{{ '/SolicitudesFactura/Finalizar/' . $solicitud->Id }}" type="button"
                        class="btn btn-sm btn-dark" title="Finalizar solicitud">
                        <i class="fa fa-check" aria-hidden="true"></i> Finalizar solicitud
                    </a>
                </div>
            @endif
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card p-4 mb-4" style="border-radius: 20px">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold m-0 text-secondary">Id</label>
                    <input type="text" class="form-control" value="{{ $solicitud->IdSolicitudFactura }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label for="inputPassword4" class="form-label fw-bold text-secondary m-0">Fecha</label>
                    <input type="text" class="form-control" value="{{ $solicitud->FechaSolicitud }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Nombre Cliente </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NomCliente }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Teléfono</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Telefono }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Correo</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Email }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">RFC</label>
                    <input type="text" class="form-control" value="{{ $solicitud->RFC }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Tipo Persona </label>
                    <input type="text" class="form-control" value="{{ $solicitud->TipoPersona }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Calle</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Calle }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Número Exterior </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NumExt }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Número Interior </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NumInt }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Colonia</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Colonia }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Codigo Postal </label>
                    <input type="text" class="form-control" value="{{ $solicitud->CodigoPostal }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Municipio </label>
                    <input type="text" class="form-control" value="{{ $solicitud->Municipio }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Ciudad</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Ciudad }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Estado</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Estado }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">País</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Pais }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Acción</label>
                    <input type="text" class="form-control" value="{{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }}"
                        disabled>
                </div>
            </div>
        </div>

        <div>
            <h2>Detalle de la Solicitud </h2>

        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold m-0 text-secondary">Tienda</label>
                    <input type="text" class="form-control" value="{{ $solicitud->NomTienda }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label for="inputPassword4" class="form-label fw-bold text-secondary m-0">Tipo De Pago </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NomTipoPago }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label for="inputPassword4" class="form-label fw-bold text-secondary m-0">Banco </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NomBanco }}" disabled>
                </div>
                <div class="col-md-3 ">
                    <label for="inputPassword4" class="form-label fw-bold text-secondary m-0">Numero Tarjeta </label>
                    <input type="text" class="form-control" value="{{ $solicitud->NumTarjeta }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Id Cliente Cloud </label>
                    <input type="text" class="form-control"
                        value="{{ $solicitud->IdClienteCloud ? $solicitud->IdClienteCloud : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Id Usuario Cliente </label>
                    <input type="text" class="form-control"
                        value="{{ $solicitud->IdUsuarioCliente ? $solicitud->IdUsuarioCliente : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Uso CFDI </label>
                    <input type="text" class="form-control"
                        value="{{ $solicitud->UsoCFDI ? $solicitud->UsoCFDI : 'Sin dato' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label text-secondary fw-bold m-0">Bill To </label>
                    <input type="text" class="form-control"
                        value="{{ $solicitud->Bill_To ? $solicitud->Bill_To : 'Sin dato' }}" disabled>
                </div>
            </div>

            {{-- <div class="card p-4 mt-4"> --}}
            <div class="content-table mt-4 content-table-full card p-4">
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
                                        {{-- <a href="/" class= "btn btn-dark-outline">Relacionar</a> --}}
                                        <div class="input-group-append">
                                            <a href="{{ '/SolicitudesFactura/Relacionar/' . $solicitud->Id . '/' . $cliente->Bill_To }}"
                                                class="input-group-text text-decoration-none">
                                                Relacionar
                                            </a>
                                        </div>
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
