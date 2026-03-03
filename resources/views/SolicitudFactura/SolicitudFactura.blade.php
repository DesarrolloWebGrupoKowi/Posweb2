@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitar Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Solicitar Factura'])
                </div>
                <div class="d-flex gap-2">
                    <a href="/VerSolicitudesFactura"
                        class="btn btn-sm"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 8px 16px;">
                        <span class="d-flex align-items-center gap-2">
                            @include('components.icons.text-file')
                            Historial
                        </span>
                    </a>
                </div>
            </div>

            <div class="mt-2">
                @include('Alertas.Alertas')
            </div>
        </div>

        @include('SolicitudFactura.ModalClienteConfirm')
        <input type="hidden"
            id="clienteCount"
            value="{{ $cliente->count() }}">

        <!-- BUSCADOR INICIAL COMPACTO -->
        @if (!$rfcCliente)
            <div class="card border-0 p-5"
                style="border-radius: 10px; background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); min-height: 500px; display: flex; align-items: center; justify-content: center;">

                <div class="text-center"
                    style="max-width: 500px;">
                    <!-- Icono animado sutil -->
                    <div class="mb-4 position-relative">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                            style="background-color: rgba(30, 41, 59, 0.03); width: 160px; height: 160px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); width: 100px; height: 100px; box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);">
                                <div style="color: white; width: 50px; height: 50px;">
                                    <!-- Icono de factura / documento -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="50"
                                        height="50"
                                        fill="currentColor"
                                        class="bi bi-file-earmark-medical"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z" />
                                        <path
                                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Círculos decorativos -->
                        <div class="position-absolute"
                            style="width: 20px; height: 20px; background-color: #f59e0b; border-radius: 50%; bottom: 20px; right: calc(50% - 70px); opacity: 0.5;">
                        </div>
                        <div class="position-absolute"
                            style="width: 12px; height: 12px; background-color: #3b82f6; border-radius: 50%; top: 20px; left: calc(50% - 70px); opacity: 0.5;">
                        </div>
                    </div>

                    <h2 class="fw-600 mb-3"
                        style="color: #0f172a; font-size: 2rem;">
                        Solicitud de Factura
                    </h2>

                    <p class="text-muted mb-4"
                        style="font-size: 1.1rem;">
                        Ingresa el RFC del cliente para consultar su información fiscal y generar su factura electrónica.
                    </p>

                    <!-- Formulario grande y visible -->
                    <div class="p-4 rounded-3"
                        style="background-color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <form action="/SolicitudFactura"
                            method="GET">
                            <div class="d-flex flex-column flex-sm-row gap-3">
                                <div class="flex-grow-1">
                                    <div class="input-group"
                                        style="width: 100%;">
                                        <input type="text"
                                            class="form-control form-control-lg py-2 border-gray-300"
                                            id="rfcCliente"
                                            name="rfcCliente"
                                            placeholder="Ej: ABCD123456XYZ"
                                            value="{{ $rfcCliente ?? '' }}"
                                            placeholder="Ej: 12345"
                                            autofocus
                                            required>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn btn-outline-dark bg-dark text-white ">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        @include('components.icons.search')
                                        Buscar
                                    </span>
                                </button>
                                {{-- <div class="w-100">
                                    <div class="input-group"
                                        style="width: 100%;">
                                        <span class="input-group-text bg-light border-end-0"
                                            style="font-weight: 500;">
                                            RFC
                                        </span>
                                        <input type="text"
                                            class="form-control form-control-lg py-2 {{ $errors->has('rfcCliente') ? 'is-invalid' : '' }}"
                                            id="rfcCliente"
                                            name="rfcCliente"
                                            placeholder="Ej: ABCD123456XYZ"
                                            value="{{ $rfcCliente ?? '' }}"
                                            maxlength="13"
                                            style="text-align: center; font-size: 1.1rem; letter-spacing: 0.5px;"
                                            autofocus
                                            required>
                                        <button class="btn btn-warning px-4"
                                            type="submit">
                                            <span class="d-flex align-items-center justify-content-center gap-2">
                                                <i class="fa fa-search"></i>
                                                Buscar
                                            </span>
                                        </button>
                                    </div>
                                </div> --}}

                                @if ($errors->has('rfcCliente'))
                                    <div class="text-danger text-start pt-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $errors->first('rfcCliente') }}
                                    </div>
                                @endif

                                @if (isset($cliente) && empty($cliente))
                                    <div class="text-danger text-start pt-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        No se encontró el cliente con RFC: {{ $rfcCliente }}
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Información adicional útil -->
                    <div class="mt-4 d-flex flex-wrap gap-3 justify-content-center">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #3b82f6; border-radius: 50%;"></div>
                            <span class="small text-muted">RFC válido (12 o 13 caracteres)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%;"></div>
                            <span class="small text-muted">Persona moral (12 chars)</span>
                        </div>
                        {{-- <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #10b981; border-radius: 50%;"></div>
                            <span class="small text-muted">Persona física (13 chars)</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        @endif

        <!-- BUSCADOR PARA CUANDO YA SE TIENE UN DATO -->
        @if (!empty($rfcCliente))
            <!-- SECCION DEL RFC -->
            <div class="card border-0 p-4"
                style="border-radius: 10px; background-color: white;">
                <form action="/SolicitudFactura">
                    <!-- Barra de búsqueda principal -->
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold text-secondary mb-2">
                                <i class="fa fa-search me-1"></i> Buscar Cliente por RFC
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa fa-id-card text-muted"></i>
                                </span>
                                <input style="text-align: center; letter-spacing: 1px; font-weight: 500;"
                                    type="text"
                                    class="form-control form-control-lg bg-white border-start-0"
                                    name="rfcCliente"
                                    placeholder="Ej: ABCD123456XYZ"
                                    value="{{ $rfcCliente }}"
                                    maxlength="13"
                                    required>
                                <button class="btn btn-warning px-4"
                                    type="submit">
                                    <i class="fa fa-search me-2"></i>Buscar
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fa fa-info-circle me-1"></i>Ingresa RFC de 12 o 13 caracteres
                            </small>
                        </div>

                        <!-- Tarjeta de información del cliente (cuando existe) -->
                        @if ($cliente->count() > 0 && isset($nomCliente))
                            <div class="col-12 col-md-7">
                                <div class="bg-white rounded-3 p-3 border-start border-4 border-success"
                                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Avatar/icono del cliente -->
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;">
                                            <i class="fa fa-building text-success fa-lg"></i>
                                        </div>

                                        <!-- Información del cliente -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">
                                                    <i class="fa fa-check-circle me-1"></i>Cliente encontrado
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-0"
                                                style="color: #0f172a; font-size: 1.1rem;">
                                                {{ $nomCliente->NomCliente }}
                                            </h6>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fa fa-tag me-1"></i>RFC: {{ $rfcCliente }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tarjeta de cliente no encontrado (NUEVO) -->
                        @if ($cliente->count() == 0)
                            <div class="col-12 col-md-7">
                                <div class="bg-white rounded-3 p-3 border-start border-4 border-primary"
                                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                    <div class="d-flex align-items-center gap-3">

                                        <!-- Avatar/icono informativo -->
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;">
                                            <i class="fa fa-user-plus text-primary fa-lg"></i>
                                        </div>

                                        <!-- Información de cliente nuevo -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                                    <i class="fa fa-info-circle me-1"></i>Cliente nuevo
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1"
                                                style="color: #0f172a; font-size: 1rem;">
                                                RFC: {{ $rfcCliente }}
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                El RFC ingresado no está registrado. Puedes proceder a registrar el
                                                cliente.
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- SECCION DONDE SE SELECCIONA EL TICKET -->
            <div class="card border-0 p-4"
                style="border-radius: 10px; background-color: white;">
                <form action="/SolicitudFactura">
                    <input type="hidden"
                        id="rfcCliente"
                        name="rfcCliente"
                        value="{{ $rfcCliente }}">

                    <div class="row g-4">
                        <!-- Columna izquierda: Búsqueda de ticket -->
                        <div class="col-12 col-lg-5">
                            <label class="form-label fw-semibold text-secondary mb-2">
                                <i class="fa fa-search me-1"></i> Buscar Ticket
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa fa-hashtag text-muted"></i>
                                </span>
                                <input type="number"
                                    class="form-control form-control-lg bg-white border-start-0 border-end-0"
                                    id="numTicket"
                                    name="numTicket"
                                    placeholder="Número de ticket"
                                    value="{{ $numTicket }}"
                                    style="text-align: center; font-weight: 500;"
                                    required
                                    autofocus>
                                <button class="btn btn-warning px-4"
                                    type="submit"
                                    id="button-addon1">
                                    <i class="fa fa-search me-2"></i>Buscar
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fa fa-info-circle me-1"></i>Ingresa el número de ticket a facturar
                            </small>
                        </div>

                        <!-- Columna derecha: Resultados y mensajes -->
                        <div class="col-12 col-lg-7">
                            @if (!empty($numTicket))

                                @php
                                    $color = 'primary';
                                    $icon = 'fa-info-circle';
                                    $badgeText = '';
                                    $title = '';
                                    $message = '';

                                    if (empty($ticket)) {
                                        $color = 'danger';
                                        $icon = 'fa-exclamation-triangle';
                                        $badgeText = 'Error';
                                        $title = "Ticket #{$numTicket} no encontrado";
                                        $message = 'El número de ticket ingresado no existe en el sistema.';
                                    } elseif ($ticket->StatusVenta == 1) {
                                        $color = 'danger';
                                        $icon = 'fa-ban';
                                        $badgeText = 'Ticket Cancelado';
                                        $title = "Ticket #{$numTicket}";
                                        $message = 'Este ticket ha sido cancelado y no puede ser facturado.';
                                    } elseif ($auxTicketFacturado == 0) {
                                        $color = 'success';
                                        $icon = 'fa-check-circle';
                                        $badgeText = 'Ticket Facturado';
                                        $title = "Ticket #{$numTicket}";
                                        $message = 'Este ticket ya ha sido facturado anteriormente.';
                                    }
                                @endphp

                                <div class="bg-white rounded-3 p-3 border-start border-4 border-{{ $color }}"
                                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.03);">

                                    @if (!empty($badgeText))
                                        <!-- Estado del Ticket -->
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-3 d-flex align-items-center justify-content-center"
                                                style="width: 48px; height: 48px;">
                                                <i class="fa {{ $icon }} text-{{ $color }} fa-lg"></i>
                                            </div>

                                            <div class="flex-grow-1">
                                                <span
                                                    class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-1 rounded-pill mb-1">
                                                    <i class="fa {{ $icon }} me-1"></i>{{ $badgeText }}
                                                </span>
                                                <h6 class="fw-bold mb-0"
                                                    style="color: #0f172a;">
                                                    {{ $title }}
                                                </h6>
                                                <p class="text-muted small mt-1 mb-0">
                                                    {{ $message }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Ticket válido -->
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="bg-light rounded-3 p-3 text-center">
                                                    <small class="text-muted d-block">Importe del Ticket</small>
                                                    <span class="fw-bold fs-4"
                                                        style="color: #0f172a;">
                                                        ${{ number_format($ticket->ImporteVenta, 2) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="bg-light rounded-3 p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <i class="fa fa-id-card text-muted"></i>
                                                        <small class="text-muted">RFC del Cliente</small>
                                                    </div>
                                                    <span class="fw-semibold"
                                                        style="color: #0f172a; letter-spacing: 0.5px;">
                                                        {{ $rfcCliente }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                </form>
            </div>

            <!-- SECCION PARA VER LA TABLA DE DE DIRECCIONES (CLIENTE ACTIVO) -->
            @if ($cliente->count() > 0 && !empty($ticket->ImporteVenta) && $ticket->StatusVenta == 0 && $auxTicketFacturado > 0)
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white;">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start p-2">Dirección</th>
                                    <th class=" p-2">Correo</th>
                                    <th class="rounded-end text-center p-2">Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cliente as $index => $dCliente)
                                    <tr class="align-middle">
                                        <td>
                                            <span class="small d-inline-block"
                                                title="{{ $dCliente->Calle }}">
                                                {{ $dCliente->Calle }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($dCliente->Email)
                                                <span class="small">{{ $dCliente->Email }}</span>
                                            @else
                                                <span class="tags-red d-inline-flex align-items-center gap-1"
                                                    style="padding: 2px 6px; font-size: 0.65rem;">
                                                    @include('components.icons.x')
                                                    <span>Sin correo</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="/VerificarSolicitudFactura/{{ $numTicket }}/{{ $rfcCliente }}/{{ $dCliente->Bill_To }}/{{ empty($dCliente->Email) ? 'NoTieneCorreo' : $dCliente->Email }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Verificar solicitud"
                                                style="padding: 0.25rem 0.5rem;">
                                                @include('components.icons.list')
                                                <span class="d-none d-md-inline">VER</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- SECCION PARA VER EL FORMULARIO (CLIENTE NUEVO) -->
            @if (!empty($ticket->ImporteVenta) && $cliente->count() == 0 && $auxTicketFacturado > 0)
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white;">
                    <h5 class="mb-0 text-gray-800 mb-3"> AGREGAR CLIENTE NUEVO </h5>
                    <form action="/GuardarSolicitudFacturaClienteNuevo"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($banderaMultiPagoFact == 0 && !empty($ticket->ImporteVenta) && $cliente->count() == 0)
                            <div class="table-responsive mb-3"
                                style="border: 2px solid #0f172a; border-radius: 8px">
                                <table class="table table-sm m-0">
                                    <thead class="table-head">
                                        <tr>
                                            <th class="rounded-start">Tipo de Pago</th>
                                            <th>Importe</th>
                                            <th class="rounded-end text-center">Dispnible P/ Facturar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tiposPagoTicket as $tipoPagoTicket)
                                            <tr>
                                                <td>{{ $tipoPagoTicket->NomTipoPago }}</td>
                                                <td>{{ number_format($tipoPagoTicket->ImporteArticulo, 2) }}</td>
                                                <td class="text-center">
                                                    @if ($tipoPagoTicket->IdSolicitudFactura == null)
                                                        <input class="form-check-input mt-0"
                                                            type="checkbox"
                                                            id="checkPagoFac"
                                                            name="chkTipoPagoTicket[]"
                                                            value="{{ $tipoPagoTicket->IdTipoPago }}"
                                                            style="width: 20px; height: 20px; cursor: pointer; accent-color: #1e293b;">
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <input type="hidden"
                            name="numTicket"
                            value="{{ $numTicket }}">
                        <input type="hidden"
                            name="rfcCliente"
                            value="{{ $rfcCliente }}">

                        <!-- TIPO DE PERSONA -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="">Tipo de Persona</label>
                                <select class="form-select rounded"
                                    name="tipoPersona"
                                    id="tipoPersona"
                                    required>
                                    <option value="PERSON">FISICA</option>
                                    <option value="ORGANIZATION">MORAL</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">Nombre</label>
                                <input type="text"
                                    class="form-control rounded"
                                    style="border-color: #e5e7eb;"
                                    name="nomCliente"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe el nombre">
                            </div>
                            <div class="col-4">
                                <label for="">Calle</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="calle"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe la calle">
                            </div>
                            <div class="col-1">
                                <label for="">Número Ext</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="numExt"
                                    required
                                    placeholder="Escribe el númeor exterior">
                            </div>
                            <div class="col-1">
                                <label for="">Número Int</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="numInt"
                                    required
                                    placeholder="Escribe el número interior">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="">Colonia</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="colonia"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe el nombre de la colonia">
                            </div>
                            <div class="col-3">
                                <label for="">Ciudad</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="ciudad"
                                    required
                                    placeholder="Escribe el nombre de la ciudad">
                            </div>
                            <div class="col-3">
                                <label for="">Municipio</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="municipio"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe el municipio">
                            </div>
                            <div class="col-3">
                                <label for="">Estado</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="estado"
                                    required
                                    placeholder="Escribe el nombre del estado">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="">Código Postal</label>
                                <input type="text"
                                    class="form-control rounded"
                                    name="codigoPostal"
                                    required
                                    placeholder="Escribe el código postal">
                            </div>
                            <div class="col-3">
                                <label for="">Correo</label>
                                <input type="email"
                                    class="form-control rounded"
                                    name="correo"
                                    required
                                    placeholder="Escribe el correo">
                            </div>
                            <div class="col-3">
                                <label for="">Teléfono</label>
                                <input type="tel"
                                    class="form-control rounded"
                                    name="telefono"
                                    minlength="10"
                                    maxlength="10"
                                    required
                                    placeholder="Escribe el teléfono">
                            </div>
                            <div class="col-3">
                                <label for="">Uso del CFDI</label>
                                <select name="cfdi"
                                    id="cfdi"
                                    class="form-select rounded"
                                    required>
                                    @foreach ($usosCFDI as $usoCFDI)
                                        <option value="{{ $usoCFDI->UsoCFDI }}">{{ $usoCFDI->NomCFDI }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="">Método de pago</label>
                                <select class="form-select rounded"
                                    name="metodopag"
                                    id="metodopag"
                                    required>
                                    @foreach ($metodosPago as $metodopago)
                                        <option value="{{ $metodopago->MetPago }}">{{ $metodopago->Descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">Régimen fiscal</label>
                                <select class="form-select rounded"
                                    name="regimenfiscal"
                                    id="regimenfiscal"
                                    required>
                                    @foreach ($regimenFiscal as $regimen)
                                        <option value="{{ $regimen->RegimenFiscal }}">
                                            {{ $regimen->NomRegimenFiscal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-4">
                                <label for="">Constancia Situación Fiscal</label>
                                <input type="file"
                                    class="form-control rounded"
                                    name="cSituacionFiscal"
                                    required>
                            </div> --}}
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-2">
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalConfirmarSolicitudCliente"
                                    class="btn btn-warning">
                                    <i class="fa fa-save"></i> Guardar Solicitud
                                </button>
                            </div>
                        </div>
                        @include('SolicitudFactura.ModalConfirmarSolicitudCliente')
                    </form>
                </div>
            @endif
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .table thead th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 0.5rem;
        }

        .table tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.02);
        }

        label {
            font-weight: 500;
            color: #6c757d;
        }
    </style>
@endsection
