<!-- Modal Agregar Tienda -->
<style>
    .form-step {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .form-step-active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progressbar {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin: 0.5rem 0 2rem;
        counter-reset: step;
    }

    .progressbar::before {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        width: 100%;
        background-color: #e2e8f0;
        z-index: 0;
        border-radius: 2px;
    }

    .progress {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        width: 0%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        z-index: 1;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .progress-step {
        width: 32px;
        height: 32px;
        background-color: #e2e8f0;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
        position: relative;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .progress-step::before {
        counter-increment: step;
        content: counter(step);
    }

    .progress-step::after {
        content: attr(data-title);
        position: absolute;
        top: calc(100% + 6px);
        font-size: 0.7rem;
        color: #64748b;
        white-space: nowrap;
        font-weight: 500;
    }

    .progress-step-active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .progress-step-active::after {
        color: #0f172a;
        font-weight: 600;
    }

    .btn-wizard {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-wizard-next {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: white;
        float: right;
    }

    .btn-wizard-next:hover {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        transform: translateY(-1px);
    }

    .btn-wizard-back {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-wizard-back:hover {
        background: #e2e8f0;
    }

    .btn-wizard-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-wizard-submit:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
</style>

<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregarLabel"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg modal-dialog-scrollable"
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
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-plus-circle"></i>
                        </div>
                        <span>Agregar Tienda</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 pb-2 pt-4">
                <form
                    action="/CrearTienda"
                    method="POST"
                    id="formAgregarTienda"
                >
                    @csrf

                    <!-- Barra de progreso -->
                    <div class="progressbar">
                        <div
                            class="progress"
                            id="progress"
                        ></div>
                        <div
                            class="progress-step progress-step-active"
                            data-title="General"
                        ></div>
                        <div
                            class="progress-step"
                            data-title="Dirección"
                        ></div>
                        <div
                            class="progress-step"
                            data-title="Lista Precios"
                        ></div>
                        <div
                            class="progress-step"
                            data-title="Plaza"
                        ></div>
                        <div
                            class="progress-step"
                            data-title="Cloud"
                        ></div>
                        <div
                            class="progress-step"
                            data-title="Servicio"
                        ></div>
                    </div>

                    <!-- Paso 1: General -->
                    <div class="form-step form-step-active">
                        <div class="mb-3">
                            <label
                                for="NomTienda"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Nombre de Tienda <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-font"></i>
                                </span>
                                <input
                                    type="text"
                                    id="NomTienda"
                                    name="NomTienda"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe el nombre de la tienda"
                                >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label
                                for="Correo"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Correo <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    id="Correo"
                                    name="Correo"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    required
                                    placeholder="Escribe el correo"
                                >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label
                                for="RFC"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                RFC <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-file-text"></i>
                                </span>
                                <input
                                    type="text"
                                    id="RFC"
                                    name="RFC"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe el RFC"
                                >
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-next siguienteBtn"
                            >
                                Siguiente <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Dirección -->
                    <div class="form-step">
                        <div class="mb-3">
                            <label
                                for="Direccion"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Dirección <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input
                                    type="text"
                                    id="Direccion"
                                    name="Direccion"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    onkeyup="mayusculas(this)"
                                    required
                                    placeholder="Escribe la dirección"
                                >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label
                                for="Colonia"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Colonia <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="Colonia"
                                name="Colonia"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                required
                                placeholder="Escribe la colonia"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="Telefono"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Teléfono <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-phone"></i>
                                </span>
                                <input
                                    type="text"
                                    id="Telefono"
                                    name="Telefono"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    required
                                    placeholder="Escribe el número de teléfono"
                                >
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-back atrasBtn"
                            >
                                <i class="fa fa-arrow-left"></i> Atrás
                            </button>
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-next siguienteBtn"
                            >
                                Siguiente <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 3: Lista de Precios -->
                    <div class="form-step">
                        <div class="mb-3">
                            <label
                                for="IdListaPrecios"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Lista de Precios <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="IdListaPrecios"
                                name="IdListaPrecios"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                required
                                placeholder="Escribe la lista de precios"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="TiendaActiva"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tienda Activa Local
                            </label>
                            <select
                                class="form-select"
                                name="TiendaActiva"
                                id="TiendaActiva"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option value="0">Si</option>
                                <option
                                    selected
                                    value="1"
                                >No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label
                                for="Inventario"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Inventario
                            </label>
                            <select
                                class="form-select"
                                name="Inventario"
                                id="Inventario"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option value="0">Si</option>
                                <option value="1">No</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-back atrasBtn"
                            >
                                <i class="fa fa-arrow-left"></i> Atrás
                            </button>
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-next siguienteBtn"
                            >
                                Siguiente <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 4: Plaza -->
                    <div class="form-step">
                        <div class="mb-3">
                            <label
                                for="CentroCosto"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Centro de Costo <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="CentroCosto"
                                name="CentroCosto"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                required
                                placeholder="Escribe el centro de costo"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="IdCiudad"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Ciudad
                            </label>
                            <select
                                name="IdCiudad"
                                id="IdCiudad"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->IdCiudad }}">{{ $ciudad->NomCiudad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label
                                for="IdPlaza"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Plaza
                            </label>
                            <select
                                name="IdPlaza"
                                id="IdPlaza"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($plazas as $plaza)
                                    <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-back atrasBtn"
                            >
                                <i class="fa fa-arrow-left"></i> Atrás
                            </button>
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-next siguienteBtn"
                            >
                                Siguiente <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 5: Cloud -->
                    <div class="form-step">
                        <div class="mb-3">
                            <label
                                for="Almacen"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Almacén <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="Almacen"
                                name="Almacen"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                required
                                placeholder="Escribe el almacén"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="Organization_Name"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Organización Nombre <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="Organization_Name"
                                name="Organization_Name"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                required
                                placeholder="Escribe el nombre de la organización"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="Subinventory_Code"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Subinventario <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="Subinventory_Code"
                                name="Subinventory_Code"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                required
                                placeholder="Sub inventario"
                            >
                        </div>
                        <div class="mb-3">
                            <label
                                for="Order_Type_Cloud"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tipo de Orden <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="Order_Type_Cloud"
                                name="Order_Type_Cloud"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                required
                                placeholder="Escribe el tipo de orden"
                            >
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-back atrasBtn"
                            >
                                <i class="fa fa-arrow-left"></i> Atrás
                            </button>
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-next siguienteBtn"
                            >
                                Siguiente <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 6: Servicio -->
                    <div class="form-step">
                        <div class="mb-3">
                            <label
                                for="ServicioaDomicilio"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Servicio a Domicilio
                            </label>
                            <select
                                class="form-select"
                                name="ServicioaDomicilio"
                                id="ServicioaDomicilio"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option value="0">Activo</option>
                                <option value="1">Inactivo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label
                                for="CostoaDomicilio"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Costo a Domicilio
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-dollar"></i>
                                </span>
                                <input
                                    type="number"
                                    id="CostoaDomicilio"
                                    name="CostoaDomicilio"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    placeholder="Escribe el costo a domicilio"
                                >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label
                                for="Comentario"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Comentario
                            </label>
                            <textarea
                                id="Comentario"
                                name="Comentario"
                                class="form-control"
                                rows="2"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                placeholder="Comentario"
                            ></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn-wizard btn-wizard-back atrasBtn"
                            >
                                <i class="fa fa-arrow-left"></i> Atrás
                            </button>
                            <button
                                type="submit"
                                class="btn-wizard btn-wizard-submit"
                            >
                                <i class="fa fa-save"></i> Crear Tienda
                            </button>
                        </div>
                    </div>
                </form>
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
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const atrasBtns = document.querySelectorAll(".atrasBtn");
    const siguienteBtns = document.querySelectorAll(".siguienteBtn");
    const progress = document.getElementById("progress");
    const formSteps = document.querySelectorAll(".form-step");
    const progressSteps = document.querySelectorAll(".progress-step");

    let formStepsNum = 0;

    siguienteBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            if (formStepsNum < formSteps.length - 1) {
                formStepsNum++;
                updateFormSteps();
                updateProgressbar();
            }
        });
    });

    atrasBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            if (formStepsNum > 0) {
                formStepsNum--;
                updateFormSteps();
                updateProgressbar();
            }
        });
    });

    function updateFormSteps() {
        formSteps.forEach(formStep => {
            formStep.classList.remove("form-step-active");
        });
        formSteps[formStepsNum].classList.add("form-step-active");
    }

    function updateProgressbar() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx <= formStepsNum) {
                progressStep.classList.add("progress-step-active");
            } else {
                progressStep.classList.remove("progress-step-active");
            }
        });

        const progressActive = document.querySelectorAll(".progress-step-active");
        progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
    }
</script>
