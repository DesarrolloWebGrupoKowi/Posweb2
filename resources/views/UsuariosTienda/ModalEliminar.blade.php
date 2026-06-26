<!--Modal Eliminar Usuario Tienda-->
<div
    class="modal fade"
    id="ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}"
    tabindex="-1"
    aria-labelledby="ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #1e293b 100%); border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
                        >
                            @include('components.icons.trash')
                        </div>
                        <span>Eliminar Usuario Tienda</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div
                        class="d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 64px; height: 64px; background-color: rgba(220, 38, 38, 0.1); border-radius: 50%;"
                    >
                        <x-icons.box
                            :width="24"
                            :height="24"
                            color="#dc2626"
                        />
                    </div>
                    <p
                        class="fs-6 fw-medium m-0 text-gray-700"
                        style="line-height: 24px;"
                    >
                        ¿Seguro que desea eliminar el usuario?
                    </p>
                    <p class="fs-5 fw-semibold text-danger mb-0 mt-2">
                        {{ $usuarioTienda->NomUsuario }}
                    </p>
                    <p class="fs-6 text-muted mb-0 mt-3">
                        Esta acción no se puede revertir.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.x')
                        Cancelar
                    </span>
                </button>
                <form
                    action="EliminarUsuarioTienda/{{ $usuarioTienda->IdUsuarioTienda }}"
                    method="POST"
                    class="d-inline"
                >
                    @csrf
                    <button
                        type="submit"
                        class="btn btn-danger"
                        style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; transition: all 0.2s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #b91c1c 0%, #991b1b 100%)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'"
                    >
                        <span class="d-flex align-items-center gap-1">
                            @include('components.icons.trash')
                            Eliminar
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
