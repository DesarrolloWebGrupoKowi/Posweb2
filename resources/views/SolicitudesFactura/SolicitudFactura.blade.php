@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud Factura ' . $solicitud->IdSolicitudFactura)
@section('dashboardWidth', 'width-general')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/styleSolicitudesFactura.css') }}">
@endsection

@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border border-5 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-start flex-column  flex-wrap flex-sm-row gap-2">
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
                            class="btn btn-sm btn-dark" style="text-wrap: nowrap;" target="_blank"
                            title="Finalizar solicitud">
                            <i class="fa fa-book"></i> Ver constancia
                        </a>
                    @endif
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-flex-none card p-3 border border-5" style="border-radius: 10px">
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
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Teléfono:</label>
                    <div>
                        <span> {{ $solicitud->Telefono ? $solicitud->Telefono : 'Sin dato' }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Correo:</label>
                    <div>
                        <span> {{ $solicitud->Email }} </span>
                    </div>
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
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Calle:</label>
                    <div>
                        <span> {{ $solicitud->Calle }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Número Exterior:</label>
                    <div>
                        <span> {{ $solicitud->NumExt }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Número Interior:</label>
                    <div>
                        <span> {{ $solicitud->NumInt ? $solicitud->NumInt : 'Sin dato' }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Colonia:</label>
                    <div>
                        <span> {{ $solicitud->Colonia }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Codigo Postal:</label>
                    <div>
                        <span> {{ $solicitud->CodigoPostal }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Municipio:</label>
                    <div>
                        <span> {{ $solicitud->Municipio }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Ciudad:</label>
                    <div>
                        <span> {{ $solicitud->Ciudad }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Estado:</label>
                    <div>
                        <span> {{ $solicitud->Estado }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>País:</label>
                    <div>
                        <span> {{ $solicitud->Pais }} </span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label>Acción:</label>
                    <div>
                        <span> {{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-5">
            <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Detalle de la Solicitud
            </span>
            <div class="content-table content-table-flex-none card p-3 border border-5" style="border-radius: 10px">
                <div class="row g-3 grid-inputs">
                    <div class="col-sm-6 col-md-3">
                        <label>Tienda:</label>
                        <div>
                            <span>{{ $solicitud->NomTienda }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 ">
                        <label>Tipo De Pago:</label>
                        <div>
                            <span>{{ $solicitud->NomTipoPago }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 ">
                        <label>Banco:</label>
                        <div>
                            <span>{{ $solicitud->NomBanco ? $solicitud->NomBanco : 'Sin dato' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 ">
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
            </div>
        </div>

    </div>
@endsection
