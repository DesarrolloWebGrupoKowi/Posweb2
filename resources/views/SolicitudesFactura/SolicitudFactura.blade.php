@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud Factura ' . $solicitud->IdSolicitudFactura)
@section('dashboardWidth', 'width-general')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/styleSolicitudesFactura.css') }}">
@endsection

@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
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
                <div class="gap-2 d-flex">
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

        <div class="p-4 border-0 content-table content-table-flex-none content-table-full card" style="border-radius: 10px">
            <div class="row g-3 grid-inputs">
                <div class="col-md-3">
                    <label>Id:</label>
                    <div>
                        <span>{{ $solicitud->IdSolicitudFactura }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Id Cliente Cloud: </label>
                    <div>
                        <span>
                            {{ $solicitud->IdClienteCloud ? $solicitud->IdClienteCloud : 'Sin dato' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Id Usuario Cliente: </label>
                    <div>
                        <span>
                            {{ $solicitud->IdUsuarioCliente ? $solicitud->IdUsuarioCliente : 'Sin dato' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Fecha: </label>
                    <div>
                        <span>
                            {{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Nombre Cliente: </label>
                    <div>
                        <span>{{ $solicitud->NomCliente }}</span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->NomCliente != $solicitud->NomCliente)
                            <span class="tags-red w-100">Oracle: {{ $cliente->NomCliente }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Teléfono:</label>
                    <div>
                        <span> {{ $solicitud->Telefono ? $solicitud->Telefono : 'Sin dato' }}
                        </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->Telefono != $solicitud->Telefono)
                            <span class="tags-red w-100">Oracle: {{ $cliente->Telefono }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Correo:</label>
                    <div>
                        <span> {{ $solicitud->Email }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @foreach ($cliente->CorreoCliente as $emails)
                            @if ($emails->Email != $solicitud->Email)
                                <span class="tags-red w-100">Oracle: {{ $cliente->Email }}</span>
                            @endif
                        @endforeach
                        @if (count($cliente->CorreoCliente) == 0)
                            <span class="tags-red w-100">Oracle: Sin correo</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>RFC:</label>
                    <div>
                        <span> {{ $solicitud->RFC }} </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Tipo Persona:</label>
                    <div>
                        <span> {{ $solicitud->TipoPersona }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->TipoPersona != $solicitud->TipoPersona)
                            <span class="tags-red w-100">Oracle: {{ $cliente->TipoPersona }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Calle:</label>
                    <div>
                        <span> {{ $solicitud->Calle }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if (strtolower($cliente->Calle) != strtolower($solicitud->Calle))
                            <span class="tags-red w-100">Oracle: {{ $cliente->Calle }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Número Exterior:</label>
                    <div>
                        <span> {{ $solicitud->NumExt }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->NumExt != $solicitud->NumExt)
                            <span class="tags-red w-100">Oracle: {{ $cliente->NumExt }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Número Interior:</label>
                    <div>
                        <span> {{ $solicitud->NumInt ? $solicitud->NumInt : 'Sin dato' }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->NumInt != $solicitud->NumInt)
                            <span class="tags-red w-100">Oracle: {{ $cliente->NumInt }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Colonia:</label>
                    <div>
                        <span> {{ $solicitud->Colonia }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->Colonia != $solicitud->Colonia)
                            <span class="tags-red w-100">Oracle: {{ $cliente->Colonia }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Codigo Postal:</label>
                    <div>
                        <span> {{ $solicitud->CodigoPostal }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if ($cliente->CodigoPostal != $solicitud->CodigoPostal)
                            <span class="tags-red w-100">Oracle: {{ $cliente->CodigoPostal }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Municipio:</label>
                    <div>
                        <span> {{ $solicitud->Municipio }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if (strtolower($cliente->Municipio) != strtolower($solicitud->Municipio))
                            <span class="tags-red w-100">Oracle: {{ $cliente->Municipio }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Ciudad:</label>
                    <div>
                        <span> {{ $solicitud->Ciudad }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if (strtolower($cliente->Ciudad) != strtolower($solicitud->Ciudad))
                            <span class="tags-red w-100">Oracle: {{ $cliente->Ciudad }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Estado:</label>
                    <div>
                        <span> {{ $solicitud->Estado }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if (strtolower($cliente->Estado) != strtolower($solicitud->Estado))
                            <span class="tags-red w-100">Oracle: {{ $cliente->Estado }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>País:</label>
                    <div>
                        <span> {{ $solicitud->Pais }} </span>
                    </div>
                    @foreach ($clienteSolicitud as $cliente)
                        @if (strtolower($cliente->Pais) != strtolower($solicitud->Pais))
                            <span class="tags-red w-100">Oracle: {{ $cliente->Pais }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-3">
                    <label>Acción:</label>
                    <div>
                        <span> {{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }} </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="p-4 border-0 content-table content-table-flex-none content-table-full card"
            style="border-radius: 10px">
        </div> --}}

        <div class="p-4 border-0 content-table content-table-flex-none content-table-full card" style="border-radius: 10px">
            {{-- <h2>Detalle de la Solicitud </h2> --}}
            <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Detalle de la Solicitud
            </span>
            <div class="row g-3 grid-inputs">
                <div class="col-md-3">
                    <label>Tienda:</label>
                    <div>
                        <span>{{ $solicitud->NomTienda }}</span>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <label>Tipo De Pago:</label>
                    <div>
                        <span>{{ $solicitud->NomTipoPago }}</span>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <label>Banco:</label>
                    <div>
                        <span>{{ $solicitud->NomBanco ? $solicitud->NomBanco : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <label>Numero Tarjeta:</label>
                    <div>
                        <span>{{ $solicitud->NumTarjeta ? $solicitud->NumTarjeta : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Bill To:</label>
                    <div>
                        <span>{{ $solicitud->Bill_To ? $solicitud->Bill_To : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Uso CFDI:</label>
                    <div>
                        <span>{{ $solicitud->UsoCFDI ? $solicitud->UsoCFDI : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Metódo Pago:</label>
                    <div>
                        <span>{{ $solicitud->MetodoPago ? $solicitud->MetodoPago : 'Sin dato' }}</span>
                    </div>
                </div>
            </div>

            {{-- <div class="p-4 mt-4 card"> --}}
            <div class="mt-4 content-table content-table-full card">
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
