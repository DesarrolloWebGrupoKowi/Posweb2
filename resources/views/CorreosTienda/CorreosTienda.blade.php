<x-page-container title="Correos Por Tienda">
    <x-card-gradient-header
        icon="envelope-at"
        title="Correos Por Tienda"
        subtitle="Configure los correos electrónicos por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/CorreosTienda"
            id="formCorreoTienda"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? ''"
                    onchange="document.getElementById('formCorreoTienda').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-envelope-paper me-2"
                                style="color: #64748b;"
                            ></i>
                            Correos de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            {{ $correos->count() == 0 ? 'Configure los correos electrónicos' : 'Edite los correos electrónicos configurados' }}
                        </p>
                    </div>
                </div>

                <div
                    class="card overflow-hidden border-0 shadow-sm"
                    style="border-radius: 16px;"
                >
                    <div class="card-body p-4">
                        @if ($correos->count() == 0)
                            <form
                                action="/GuardarCorreosTienda/{{ $idTienda }}"
                                method="POST"
                            >
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-person-badge me-1"></i>Correo del gerente
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="gerenteCorreo"
                                                placeholder="Correo del Gerente"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-person-workspace me-1"></i>Correo del encargado
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="encargadoCorreo"
                                                placeholder="Correo del Encargado"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-eye me-1"></i>Correo del supervisor
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="supervisorCorreo"
                                                placeholder="Correo del Supervisor"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-building me-1"></i>Correo administrativo
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="administrativaCorreo"
                                                placeholder="Correo Administrativa"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-box-seam me-1"></i>Correo almacenista
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="almacenistaCorreo"
                                                placeholder="Correo del Almacenista"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-receipt me-1"></i>Correo facturista
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="facturistaCorreo"
                                                placeholder="Correo de Facturista"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            <i class="bi bi-inbox me-1"></i>Correo recepción
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control border-start-0"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                name="recepcionCorreo"
                                                placeholder="Correo Recepción"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn-modern btn-agregar"
                                    >
                                        <i class="bi bi-floppy me-2"></i>Guardar Correos
                                    </button>
                                </div>
                            </form>
                        @else
                            @foreach ($correos as $correo)
                                <form
                                    action="/EditarCorreosTienda/{{ $idTienda }}"
                                    method="POST"
                                >
                                    @csrf
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-person-badge me-1"></i>Correo del gerente
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="gerenteCorreo"
                                                    value="{{ $correo->GerenteCorreo }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-person-workspace me-1"></i>Correo del encargado
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="encargadoCorreo"
                                                    value="{{ $correo->EncargadoCorreo }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-eye me-1"></i>Correo del supervisor
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="supervisorCorreo"
                                                    value="{{ $correo->SupervisorCorreo }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-building me-1"></i>Correo administrativo
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="administrativaCorreo"
                                                    value="{{ $correo->AdministrativaCorreo }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-box-seam me-1"></i>Correo almacenista
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="almacenistaCorreo"
                                                    value="{{ $correo->AlmacenistaCorreo }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-receipt me-1"></i>Correo facturista
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="facturistaCorreo"
                                                    value="{{ $correo->FacturistaCorreo }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="form-label fw-medium mb-2"
                                                style="color: #475569; font-size: 0.85rem;"
                                            >
                                                <i class="bi bi-inbox me-1"></i>Correo recepción
                                            </label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text"
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                                >
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input
                                                    type="email"
                                                    class="form-control border-start-0"
                                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                    name="recepcionCorreo"
                                                    value="{{ $correo->RecepcionCorreo }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button
                                            type="submit"
                                            class="btn-modern btn-warning-modern"
                                        >
                                            <i class="bi bi-pencil-square me-2"></i>Editar Correos
                                        </button>
                                    </div>
                                </form>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para configurar sus correos electrónicos</p>
            </div>
        @endif
    </x-card-gradient-header>
    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCorreoTienda').submit();
        });
    </script>
</x-page-container>
