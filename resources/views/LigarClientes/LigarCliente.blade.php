<x-page-container title="Ligar Cliente">
    <x-card-gradient-header
        icon="link-45deg"
        title="Ligar Solicitud de Factura con Cliente Oracle"
        subtitle="Relacione la solicitud con un cliente existente en Oracle"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <div class="p-4">
            <!-- Encabezado con botón -->
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-file-earmark-text me-2"
                            style="color: #64748b;"
                        ></i>Datos de la Solicitud #{{ $solicitud->IdSolicitudFactura }}
                    </h5>
                    <p class="section-content-subtitle">Relacione la solicitud con un cliente de Oracle</p>
                </div>
                <div>
                    @if ($solicitud['ConstanciaSituacionFiscal'] != null)
                        <a
                            href="{{ '/VerConstanciaCliente/' . $solicitud->IdSolicitudFactura }}"
                            class="btn-modern btn-outline-modern"
                            target="_blank"
                        >
                            <i class="bi bi-file-text me-2"></i>Ver constancia
                        </a>
                    @endif
                    @if ($solicitud->Bill_To != null)
                        <a
                            href="{{ '/ClientesNuevos/Finalizar/' . $solicitud->Id }}"
                            class="btn-modern btn-agregar"
                        >
                            <i class="bi bi-check-lg me-2"></i>Finalizar solicitud
                        </a>
                    @endif
                    <a
                        href="/ClientesNuevos"
                        class="btn-modern btn-outline-modern"
                    >
                        <i class="bi bi-arrow-left me-2"></i>Ver solicitudes
                    </a>
                </div>
            </div>

            <!-- Datos de la Solicitud -->
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <h5
                        class="mb-4"
                        style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                    >
                        <i
                            class="bi bi-file-earmark-text me-2"
                            style="color: #3b82f6;"
                        ></i>Datos en Solicitud
                    </h5>
                    <div class="row g-3">
                        @php
                            $telefonosOracle = collect($clienteSolicitud)->pluck('Telefono')->unique();
                            $callesUnicas = $clienteSolicitud->pluck('Calle')->unique();
                            $NumExtUnicas = $clienteSolicitud->pluck('NumExt')->unique();
                            $NumIntUnicas = $clienteSolicitud->pluck('NumInt')->unique();
                            $coloniaUnicas = $clienteSolicitud->pluck('Colonia')->unique();
                            $CodigoPostalUnicas = $clienteSolicitud->pluck('CodigoPostal')->unique();
                            $MunicipioUnicas = $clienteSolicitud->pluck('Municipio')->unique();
                            $CiudadUnicas = $clienteSolicitud->pluck('Ciudad')->unique();
                            $EstadoUnicas = $clienteSolicitud->pluck('Estado')->unique();
                            $PaisUnicas = $clienteSolicitud->pluck('Pais')->unique();
                        @endphp

                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Id</label>
                            <p
                                class="mb-0"
                                style="font-weight: 600; color: #0f172a;"
                            >{{ $solicitud->IdSolicitudFactura }}</p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >No Ticket</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->IdTicket }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Id Cliente Cloud</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->IdClienteCloud ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Id Usuario Cliente</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->IdUsuarioCliente ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Fecha</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            >
                                <i
                                    class="bi bi-clock me-1"
                                    style="color: #94a3b8; font-size: 0.8rem;"
                                ></i>
                                <span
                                    style="font-weight: 500">{{ strftime('%d %B %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Nombre Cliente</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NomCliente }}</span></p>
                            @foreach ($clienteSolicitud as $cliente)
                                @if ($cliente->NomCliente != $solicitud->NomCliente)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $cliente->NomCliente }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Teléfono</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Telefono ?: 'Sin dato' }}</span></p>
                            @foreach ($telefonosOracle as $telefono)
                                @if ($telefono != $solicitud->Telefono)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $telefono }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Correo</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Email }}</span></p>
                            @isset($cliente)
                                @php $correos = $cliente->CorreoCliente->pluck('Email')->unique(); @endphp
                                @foreach ($correos as $email)
                                    @if ($email != $solicitud->Email)
                                        <span
                                            class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                            style="font-size: 0.7rem;"
                                        >Oracle: {{ $email }}</span>
                                    @endif
                                @endforeach
                                @if (count($cliente->CorreoCliente) == 0)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: Sin correo</span>
                                @endif
                            @endisset
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >RFC</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->RFC }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Tipo Persona</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->TipoPersona }}</span></p>
                            @foreach ($clienteSolicitud as $cliente)
                                @if ($cliente->TipoPersona != $solicitud->TipoPersona)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $cliente->TipoPersona }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Calle</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Calle }}</span></p>
                            @foreach ($callesUnicas as $calle)
                                @if (strtolower($calle) != strtolower($solicitud->Calle))
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $calle }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Número Exterior</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NumExt }}</span></p>
                            @foreach ($NumExtUnicas as $numExt)
                                @if ($numExt != $solicitud->NumExt)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $numExt }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Número Interior</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NumInt ?: 'Sin dato' }}</span></p>
                            @foreach ($NumIntUnicas as $numInt)
                                @if ($numInt != $solicitud->NumInt)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $numInt }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Colonia</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Colonia }}</span></p>
                            @foreach ($coloniaUnicas as $col)
                                @if ($col != $solicitud->Colonia)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $col }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Código Postal</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->CodigoPostal }}</span></p>
                            @foreach ($CodigoPostalUnicas as $CodigoPostal)
                                @if ($CodigoPostal != $solicitud->CodigoPostal)
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $CodigoPostal }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Municipio</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Municipio }}</span></p>
                            @foreach ($MunicipioUnicas as $Municipio)
                                @if (strtolower($Municipio) != strtolower($solicitud->Municipio))
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $Municipio }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Ciudad</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Ciudad }}</span></p>
                            @foreach ($CiudadUnicas as $Ciudad)
                                @if (strtolower($Ciudad) != strtolower($solicitud->Ciudad))
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $Ciudad }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Estado</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Estado }}</span></p>
                            @foreach ($EstadoUnicas as $Estado)
                                @if (strtolower($Estado) != strtolower($solicitud->Estado))
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $Estado }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >País</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Pais }}</span></p>
                            @foreach ($PaisUnicas as $Pais)
                                @if (strtolower($Pais) != strtolower($solicitud->Pais))
                                    <span
                                        class="badge bg-danger text-danger rounded-pill d-inline-block mt-1 bg-opacity-10"
                                        style="font-size: 0.7rem;"
                                    >Oracle: {{ $Pais }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Acción</label>
                            @if ($solicitud->Editar)
                                <span class="badge-status badge-pending">Actualizar</span>
                            @else
                                <span class="badge-status badge-active">Nuevo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de la Solicitud -->
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <h5
                        class="mb-4"
                        style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                    >
                        <i
                            class="bi bi-info-circle me-2"
                            style="color: #3b82f6;"
                        ></i>Detalle de la Solicitud
                    </h5>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Tienda</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NomTienda }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Tipo de Pago</label>
                            <p class="mb-0"><span
                                    class="badge bg-light text-dark border"
                                    style="font-weight: 500; font-size: 0.8rem;"
                                >{{ $solicitud->NomTipoPago }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Banco</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NomBanco ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Número Tarjeta</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NumTarjeta ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Bill To</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->Bill_To ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Uso CFDI</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->UsoCFDI ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Método Pago</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->MetodoPago ?: 'Sin dato' }}</span></p>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label
                                style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                            >Régimen Fiscal</label>
                            <p
                                class="mb-0"
                                style="color: #475569; font-size: 0.9rem;"
                            ><span style="font-weight: 500">{{ $solicitud->NomRegimenFiscal ?: 'Sin dato' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Coincidencias -->
            <div class="card-modern">
                <div
                    class="card-header border-bottom p-3"
                    style="background: #f8fafc;"
                >
                    <div class="d-flex align-items-center gap-2">
                        <i
                            class="bi bi-search"
                            style="color: #64748b;"
                        ></i>
                        <h6
                            class="fw-bold mb-0"
                            style="color: #0f172a;"
                        >Clientes Encontrados en Oracle</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover table-custom mb-0 table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-globe me-1"></i>Sitio</th>
                                    <th><i class="bi bi-person me-1"></i>Cliente</th>
                                    <th><i class="bi bi-cloud me-1"></i>Id Cloud</th>
                                    <th><i class="bi bi-rss me-1"></i>RFC</th>
                                    <th><i class="bi bi-truck me-1"></i>Ship To</th>
                                    <th><i class="bi bi-receipt me-1"></i>Bill To</th>
                                    <th><i class="bi bi-geo me-1"></i>Locación</th>
                                    <th><i class="bi bi-building me-1"></i>Ciudad</th>
                                    <th><i class="bi bi-signpost me-1"></i>Dirección</th>
                                    <th class="text-center"><i class="bi bi-link-45deg me-1"></i>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($clientes) <= 0)
                                    <tr>
                                        <td colspan="10">
                                            <div class="py-5 text-center">
                                                <div class="empty-state-icon mx-auto mb-3">
                                                    <i
                                                        class="bi bi-search fs-3"
                                                        style="color: #94a3b8;"
                                                    ></i>
                                                </div>
                                                <h6 class="text-muted">Sin coincidencias</h6>
                                                <small class="text-muted">No se encontraron clientes en Oracle</small>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($clientes as $cliente)
                                        <tr>
                                            <td style="font-weight: 500; color: #0f172a;">{{ $cliente->Sitio }}</td>
                                            <td>{{ $cliente->NomCliente }}</td>
                                            <td>{{ $cliente->IdClienteCloud }}</td>
                                            <td style="font-weight: 500;">{{ $cliente->RFC }}</td>
                                            <td>{{ $cliente->Ship_To }}</td>
                                            <td>{{ $cliente->Bill_To }}</td>
                                            <td>{{ $cliente->Locacion }}</td>
                                            <td>{{ $cliente->Ciudad }}</td>
                                            <td>
                                                <span
                                                    class="text-truncate d-inline-block"
                                                    style="max-width: 200px;"
                                                >
                                                    {{ $cliente->Calle }} #{{ $cliente->NumExt }} Col.
                                                    {{ $cliente->Colonia }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    class="btn-table-action btn-table-activate"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalRelacionarCliente{{ $cliente->IdCatCliente }}"
                                                    title="Relacionar"
                                                >
                                                    <i class="bi bi-link-45deg"></i> Relacionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($clientes as $cliente)
            @include('LigarClientes.ModalRelacionarCliente', ['solicitud' => $solicitud])
        @endforeach
    </x-card-gradient-header>
</x-page-container>

