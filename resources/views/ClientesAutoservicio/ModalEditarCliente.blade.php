<!-- Modal Editar Cliente -->
<div
    class="modal fade"
    id="ModalEditarCliente"
    tabindex="-1"
    aria-labelledby="ModalEditarCliente"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >

            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i
                                class="bi bi-pencil-square"
                                style="color: white; font-size: 1rem;"
                            ></i>
                        </div>
                        <span>Editar Cliente</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    id="formEditarCliente"
                    autocomplete="off"
                >
                    @csrf
                    @method('PUT')
                    <input
                        type="hidden"
                        id="editarClienteId"
                        name="cliente_id"
                    >
                    <!-- ID del Cliente (solo lectura) -->
                    <!-- <div class="mb-3">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-hash me-1"></i>ID del Cliente
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-hash"></i>
                            </span>
                            <input
                                type="text"
                                id="editarClienteId"
                                name="cliente_id"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; background: var(--bg-subtle);"
                                readonly
                            >
                        </div>
                    </div> -->

                    <!-- Nombre del Cliente -->
                    <div class="mb-3">
                        <label
                            for="editarNombre"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-person me-1"></i>Nombre del Cliente <span
                                style="color: var(--danger-color);"
                            >*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-person"></i>
                            </span>
                            <input
                                type="text"
                                id="editarNombre"
                                name="nombre"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe el nombre del cliente"
                                tabindex="1"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-editar-nombre"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label
                            for="editarDireccion"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-geo-alt me-1"></i>Dirección <span
                                style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <input
                                type="text"
                                id="editarDireccion"
                                name="direccion"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe la dirección completa"
                                tabindex="2"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-editar-direccion"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Subinventario (Nombre) -->
                    <div class="mb-3">
                        <label
                            for="editarSubinvName"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-building me-1"></i>Organización<span
                                style="color: var(--danger-color);"
                            >*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-building"></i>
                            </span>
                            <input
                                type="text"
                                id="editarSubinvName"
                                name="subinventario_name"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Ej: AHE-111"
                                tabindex="3"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-editar-subinventario_name"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Subinventario (Cloud) -->
                    <div class="mb-3">
                        <label
                            for="editarSubinvCloud"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-cloud me-1"></i>Almacen<span
                                style="color: var(--danger-color);"
                            >*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-cloud"></i>
                            </span>
                            <input
                                type="text"
                                id="editarSubinvCloud"
                                name="subinventario_cloud"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Ej: AHE-CLOUD-01"
                                tabindex="4"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-editar-subinventario_cloud"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Campo: Sucursal -->
                    <div class="col-12">
                        <label
                            for="editarSucursal"
                            class="form-label fw-semibold"
                            style="font-size: 0.85rem; color: #475569;"
                        >
                            <i class="bi bi-building me-1"></i>Sucursal
                        </label>
                        <select
                            id="editarSucursal"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.9rem;"
                        >
                            <option value="">Seleccionar sucursal...</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->Sucursal }}</option>
                            @endforeach
                        </select>
                        <div
                            id="error-editar-sucursal"
                            class="form-text text-danger"
                            style="font-size: 0.75rem;"
                        ></div>
                    </div>

                    <!-- Campo oculto para SUBINVENTORY_ID fijo en 0 -->
                    <input
                        type="hidden"
                        id="editarSubinvId"
                        name="subinventario_id"
                        value="0"
                    >
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="bi bi-x"></i>
                    Cancelar
                </button>
                <button
                    type="button"
                    id="btnEditarCliente"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="bi bi-pencil-square"></i>
                    Actualizar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal.fade .modal-dialog {
        transform: translateY(-10px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--border-input) !important;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.1) !important;
    }

    .modal-body .input-group:focus-within .input-group-text {
        border-color: var(--border-input) !important;
        color: var(--text-primary) !important;
    }
</style>
