<!-- Modal Desbloquear Empleado -->
<div
    class="modal fade"
    id="DesbloquearEmpleado{{ $bloqueo->NumNomina }}"
    tabindex="-1"
    aria-labelledby="DesbloquearEmpleado{{ $bloqueo->NumNomina }}Label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">
                <!-- Icono -->
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: #eff6ff;"
                >
                    <i
                        class="bi bi-unlock"
                        style="font-size: 1.5rem; color: #3b82f6;"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >Desbloquear Empleado</h5>

                <!-- Mensaje -->
                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;">
                    ¿Desea desbloquear al empleado?
                </p>
                <p
                    class="fw-semibold mb-3"
                    style="color: #3b82f6; font-size: 1rem;"
                >
                    {{ $bloqueo->Empleado->Nombre }} {{ $bloqueo->Empleado->Apellidos }}
                </p>
                {{-- <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 24px;">
                    El empleado podrá acceder nuevamente al sistema.
                </p> --}}

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <form
                        action="/DesbloquearEmpleado/{{ $bloqueo->NumNomina }}"
                        method="POST"
                        class="flex-grow-1"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn w-100"
                            style="background: #3b82f6; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-unlock me-1"></i> Desbloquear
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
