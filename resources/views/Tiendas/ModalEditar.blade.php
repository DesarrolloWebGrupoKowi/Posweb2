<!-- Modal Editar Tienda -->
<div
    class="modal fade"
    id="ModalEditar{{ $tienda->IdTienda }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $tienda->IdTienda }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-xl"
        style="margin-top: 5vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalEditarLabel{{ $tienda->IdTienda }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-building"></i>
                        </div>
                        <span>Editar Tienda: {{ $tienda->NomTienda }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="EditarTienda/{{ $tienda->IdTienda }}"
                    method="POST"
                >
                    @csrf

                    <!-- Fila 1: Nombre y Nombre Corto -->
                    <div class="row mb-3">
                        <div class="col-md-9 mb-md-0 mb-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Nombre
                            </label>
                            <div
                                class="rounded p-3"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f172a; font-size: 0.9rem;"
                            >
                                {{ $tienda->NomTienda }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label
                                for="NombreCorto{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Nombre Corto <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="NombreCorto"
                                id="NombreCorto{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->NombreCorto }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 2: Dirección y Plaza -->
                    <div class="row mb-3">
                        <div class="col-md-9 mb-md-0 mb-3">
                            <label
                                for="Direccion{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Dirección <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Direccion"
                                id="Direccion{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Direccion }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                for="IdPlaza{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Plaza
                            </label>
                            <select
                                class="form-select"
                                name="IdPlaza"
                                id="IdPlaza{{ $tienda->IdTienda }}"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($plazas as $plaza)
                                    <option
                                        {{ $plaza->IdPlaza == $tienda->IdPlaza ? 'selected' : '' }}
                                        value="{{ $plaza->IdPlaza }}"
                                    >
                                        {{ $plaza->NomPlaza }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Colonia, Correo, Teléfono, Centro de Costo -->
                    <div class="row mb-3">
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Colonia{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Colonia <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Colonia"
                                id="Colonia{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Colonia }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Correo{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Correo <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="email"
                                name="Correo"
                                id="Correo{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Correo }}"
                                required
                            >
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Telefono{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Teléfono (123-456-7890)<span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="tel"
                                name="Telefono"
                                id="Telefono{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Telefono }}"
                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                placeholder="123-456-7890"
                                required
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                for="CentroCosto{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Centro de Costo <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="CentroCosto"
                                id="CentroCosto{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->CentroCosto }}"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 4: Ciudad, Almacén, Organization_Name, Subinventory_Code -->
                    <div class="row mb-3">
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="IdCiudad{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Ciudad
                            </label>
                            <select
                                name="IdCiudad"
                                id="IdCiudad{{ $tienda->IdTienda }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($ciudades as $ciudad)
                                    <option
                                        {{ $ciudad->IdCiudad == $tienda->IdCiudad ? 'selected' : '' }}
                                        value="{{ $ciudad->IdCiudad }}"
                                    >
                                        {{ $ciudad->NomCiudad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Almacen{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Almacén <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Almacen"
                                id="Almacen{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Almacen }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Organization_Name{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Organization Name <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Organization_Name"
                                id="Organization_Name{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Organization_Name }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                for="Subinventory_Cloud{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Subinventory Code <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Subinventory_Cloud"
                                id="Subinventory_Cloud{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Subinventory_Code }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 5: Servicio a Domicilio, Costo, Comentario -->
                    <div class="row mb-3">
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="ServicioaDomicilio{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Servicio a Domicilio
                            </label>
                            <select
                                class="form-select"
                                name="ServicioaDomicilio"
                                id="ServicioaDomicilio{{ $tienda->IdTienda }}"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option
                                    {{ $tienda->ServicioaDomicilio == 0 ? 'selected' : '' }}
                                    value="0"
                                >Activo</option>
                                <option
                                    {{ $tienda->ServicioaDomicilio == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="CostoaDomicilio{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Costo
                            </label>
                            <input
                                type="number"
                                name="CostoaDomicilio"
                                id="CostoaDomicilio{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->CostoaDomicilio }}"
                            >
                        </div>
                        <div class="col-md-6">
                            <label
                                for="Comentario{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Comentario
                            </label>
                            <input
                                type="text"
                                name="Comentario"
                                id="Comentario{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Comentario }}"
                                onkeyup="mayusculas(this)"
                            >
                        </div>
                    </div>

                    <!-- Fila 6: Order_Type_Cloud, Tienda Local Activa, Inventario, Lista de Precios -->
                    <div class="row mb-3">
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Order_Type_Cloud{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Order Type Cloud <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="Order_Type_Cloud"
                                id="Order_Type_Cloud{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->Order_Type_Cloud }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="TiendaActiva{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tienda Local Activa
                            </label>
                            <select
                                class="form-select"
                                name="TiendaActiva"
                                id="TiendaActiva{{ $tienda->IdTienda }}"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option
                                    {{ $tienda->TiendaActiva == 0 ? 'selected' : '' }}
                                    value="0"
                                >Activa</option>
                                <option
                                    {{ $tienda->TiendaActiva == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactiva</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-md-0 mb-3">
                            <label
                                for="Inventario{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Inventario
                            </label>
                            <select
                                class="form-select"
                                name="Inventario"
                                id="Inventario{{ $tienda->IdTienda }}"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option
                                    {{ $tienda->Inventario == 0 ? 'selected' : '' }}
                                    value="0"
                                >Activo</option>
                                <option
                                    {{ $tienda->Inventario == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label
                                for="IdListaPrecios{{ $tienda->IdTienda }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Lista de Precios <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                name="IdListaPrecios"
                                id="IdListaPrecios{{ $tienda->IdTienda }}"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ $tienda->IdListaPrecios }}"
                                required
                            >
                        </div>
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="fa fa-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
