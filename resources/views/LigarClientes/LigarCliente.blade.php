@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ligar cliente')
@section('dashboardWidth', 'width-general')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/styleSolicitudesFactura.css') }}">
@endsection

@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">
        <div class="card border border-5 p-4" style="border-radius: 10px; margin: 0 auto; width: 100%; max-width: 1440px;">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Ligar Solicitud de Factura con Cliente Oracle',
                    'options' => [
                        [
                            'name' => 'Ligar clientes por solicitud',
                            'value' => '/ClientesNuevos',
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
                        <a href="{{ '/ClientesNuevos/Finalizar/' . $solicitud->Id }}" type="button"
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

        <div class="content-table content-table-flex-none card border border-5 p-4" style="border-radius: 10px">
            <div>
                <h6 class="d-inline-block col-auto bg-warning p-1 rounded">Datos En Solicitud</h6>
            </div>
            {{-- <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tienda</th>
                        <th></th>
                        <th>Cliente</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Calle</th>
                        <th>NumExt</th>
                        <th>NumInt</th>
                        <th>C.P.</th>
                        <th>Colonia</th>
                        <th>Ciudad</th>
                        <th>Municipio</th>
                        <th>Estado</th>
                        <th>Pais</th>
                        <th>Telefono</th>
                        <th>Correo Electronico</th>
                        <th></th>
                        <th>Metodo de pago</th>
                        <th>Banco</th>
                        <th class="rounded-end">Cuenta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $solicitud->IdSolicitudFactura }}</td>
                        <td>{{ $solicitud->NomTienda }}</td>
                        <td></td>
                        <td>{{ $solicitud->TipoPersona }}</td>
                        <td>{{ $solicitud->NomCliente }}</td>
                        <td>{{ $solicitud->RFC }}</td>
                        <td>{{ $solicitud->Calle }}</td>
                        <td>{{ $solicitud->NumExt }}</td>
                        <td>{{ $solicitud->NumInt }}</td>
                        <td>{{ $solicitud->CodigoPostal }}</td>
                        <td>{{ $solicitud->Colonia }}</td>
                        <td>{{ $solicitud->Ciudad }}</td>
                        <td>{{ $solicitud->Municipio }}</td>
                        <td>{{ $solicitud->Estado }}</td>
                        <td>{{ $solicitud->Pais }}</td>
                        <td>{{ $solicitud->Telefono }}</td>
                        <td>{{ $solicitud->Email }}</td>
                        <td></td>
                        <td>{{ $solicitud->NomTipoPago }}</td>
                        <td>{{ $solicitud->NomBanco }}</td>
                        <td>{{ $solicitud->NumTarjeta }}</td>
                    </tr>
                </tbody>
            </table> --}}

            <div class="row g-3 grid-inputs">
                <div class="col-sm-6 col-md-3">
                    <label>Id:</label>
                    <div>
                        <span>{{ $solicitud->IdSolicitudFactura }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Id Cliente Cloud: </label>
                    <div>
                        <span>
                            {{ $solicitud->IdClienteCloud ? $solicitud->IdClienteCloud : 'Sin dato' }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Id Usuario Cliente: </label>
                    <div>
                        <span>
                            {{ $solicitud->IdUsuarioCliente ? $solicitud->IdUsuarioCliente : 'Sin dato' }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Fecha: </label>
                    <div>
                        <span>
                            {{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
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
                <div class="col-sm-6 col-md-3">
                    <label>Teléfono:</label>
                    <div>
                        <span> {{ $solicitud->Telefono ? $solicitud->Telefono : 'Sin dato' }}
                        </span>
                    </div>
                    @php
                        $telefonosOracle = collect($clienteSolicitud)->pluck('Telefono')->unique();
                    @endphp
                    @foreach ($telefonosOracle as $telefono)
                        @if ($telefono != $solicitud->Telefono)
                            <span class="tags-red w-100">Oracle: {{ $telefono }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Correo:</label>
                    <div>
                        <span> {{ $solicitud->Email }} </span>
                    </div>
                    @isset($cliente)
                        @php
                            $correos = $cliente->CorreoCliente->pluck('Email')->unique();
                        @endphp
                        @foreach ($correos as $email)
                            @if ($email != $solicitud->Email)
                                <span class="tags-red w-100">Oracle: {{ $email }}</span>
                            @endif
                        @endforeach
                        @if (count($cliente->CorreoCliente) == 0)
                            <span class="tags-red w-100">Oracle: Sin correo</span>
                        @endif
                    @endisset
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>RFC:</label>
                    <div>
                        <span> {{ $solicitud->RFC }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
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
                <div class="col-sm-6 col-md-3">
                    <label>Calle:</label>
                    <div>
                        <span> {{ $solicitud->Calle }} </span>
                    </div>
                    @php
                        $callesUnicas = $clienteSolicitud->pluck('Calle')->unique();
                    @endphp
                    @foreach ($callesUnicas as $calle)
                        @if (strtolower($calle) != strtolower($solicitud->Calle))
                            <span class="tags-red w-100">Oracle: {{ $calle }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Número Exterior:</label>
                    <div>
                        <span> {{ $solicitud->NumExt }} </span>
                    </div>
                    @php
                        $NumExtUnicas = $clienteSolicitud->pluck('NumExt')->unique();
                    @endphp
                    @foreach ($NumExtUnicas as $numExt)
                        @if ($numExt != $solicitud->NumExt)
                            <span class="tags-red w-100">Oracle: {{ $numExt }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Número Interior:</label>
                    <div>
                        <span> {{ $solicitud->NumInt ? $solicitud->NumInt : 'Sin dato' }} </span>
                    </div>
                    @php
                        $NumIntUnicas = $clienteSolicitud->pluck('NumInt')->unique();
                    @endphp
                    @foreach ($NumIntUnicas as $numInt)
                        @if ($numInt != $solicitud->NumInt)
                            <span class="tags-red w-100">Oracle: {{ $numInt }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Colonia:</label>
                    <div>
                        <span> {{ $solicitud->Colonia }} </span>
                    </div>
                    @php
                        $coloniaUnicas = $clienteSolicitud->pluck('Colonia')->unique();
                    @endphp
                    @foreach ($coloniaUnicas as $col)
                        @if ($col != $solicitud->Colonia)
                            <span class="tags-red w-100">Oracle: {{ $col }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Codigo Postal:</label>
                    <div>
                        <span> {{ $solicitud->CodigoPostal }} </span>
                    </div>
                    @php
                        $CodigoPostalUnicas = $clienteSolicitud->pluck('CodigoPostal')->unique();
                    @endphp
                    @foreach ($CodigoPostalUnicas as $CodigoPostal)
                        @if ($CodigoPostal != $solicitud->CodigoPostal)
                            <span class="tags-red w-100">Oracle: {{ $CodigoPostal }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Municipio:</label>
                    <div>
                        <span> {{ $solicitud->Municipio }} </span>
                    </div>
                    @php
                        $MunicipioUnicas = $clienteSolicitud->pluck('Municipio')->unique();
                    @endphp
                    @foreach ($MunicipioUnicas as $Municipio)
                        @if (strtolower($Municipio) != strtolower($solicitud->Municipio))
                            <span class="tags-red w-100">Oracle: {{ $Municipio }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Ciudad:</label>
                    <div>
                        <span> {{ $solicitud->Ciudad }} </span>
                    </div>
                    @php
                        $CiudadUnicas = $clienteSolicitud->pluck('Ciudad')->unique();
                    @endphp
                    @foreach ($CiudadUnicas as $Ciudad)
                        @if (strtolower($Ciudad) != strtolower($solicitud->Ciudad))
                            <span class="tags-red w-100"> Oracle: {{ $Ciudad }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Estado:</label>
                    <div>
                        <span> {{ $solicitud->Estado }} </span>
                    </div>
                    @php
                        $EstadoUnicas = $clienteSolicitud->pluck('Estado')->unique();
                    @endphp
                    @foreach ($EstadoUnicas as $Estado)
                        @if (strtolower($Estado) != strtolower($solicitud->Estado))
                            <span class="tags-red w-100">Oracle: {{ $Estado }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>País:</label>
                    <div>
                        <span> {{ $solicitud->Pais }} </span>
                    </div>
                    @php
                        $PaisUnicas = $clienteSolicitud->pluck('Pais')->unique();
                    @endphp
                    @foreach ($PaisUnicas as $Pais)
                        @if (strtolower($Pais) != strtolower($solicitud->Pais))
                            <span class="tags-red w-100">Oracle: {{ $Pais }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Acción:</label>
                    <div>
                        <span> {{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border border-5 content-table content-table-flex-none content-table-full card"
            style="border-radius: 10px">
            <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Detalle de la Solicitud
            </span>
            <div class="row g-3 grid-inputs">
                <div class="col-sm-6 col-md-3">
                    <label>Tienda:</label>
                    <div>
                        <span>{{ $solicitud->NomTienda }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Tipo De Pago:</label>
                    <div>
                        <span>{{ $solicitud->NomTipoPago }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Banco:</label>
                    <div>
                        <span>{{ $solicitud->NomBanco ? $solicitud->NomBanco : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Numero Tarjeta:</label>
                    <div>
                        <span>{{ $solicitud->NumTarjeta ? $solicitud->NumTarjeta : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Bill To:</label>
                    <div>
                        <span>{{ $solicitud->Bill_To ? $solicitud->Bill_To : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Uso CFDI:</label>
                    <div>
                        <span>{{ $solicitud->UsoCFDI ? $solicitud->UsoCFDI : 'Sin dato' }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Metódo Pago:</label>
                    <div>
                        <span>{{ $solicitud->MetodoPago ? $solicitud->MetodoPago : 'Sin dato' }}</span>
                    </div>
                </div>
            </div>

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
                                        {{-- <a href="{{ '/SolicitudesFactura/Relacionar/' . $solicitud->Id . '/' . $cliente->Bill_To }}"
                                            class="btn-table">
                                            Relacionar
                                        </a> --}}
                                        <button class="btn-table btn-table-show" data-bs-toggle="modal"
                                            data-bs-target="#ModalRelacionarCliente{{ $cliente->IdCatCliente }}"
                                            title="Relacionar">
                                            Relacionar
                                            {{-- @include('components.icons.delete') --}}
                                        </button>
                                        @include('LigarClientes.ModalRelacionarCliente')
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
