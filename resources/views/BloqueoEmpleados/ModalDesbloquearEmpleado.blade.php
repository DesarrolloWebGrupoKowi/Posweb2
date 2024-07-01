<!-- Modal Desbloquear Empleado -->
<div class="modal fade" id="DesbloquearEmpleado{{ $bloqueo->NumNomina }}" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Desbloquear Empleado </h5>
            </div>
            <form action="/DesbloquearEmpleado/{{ $bloqueo->NumNomina }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                            ¿Desea desbloquear el empleado
                            <br>
                            {{ $bloqueo->Empleado->Nombre }} {{ $bloqueo->Empleado->Apellidos }} ?
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button id="btnDesbloquear" class="btn btn-warning">Desbloquear Empleado </button>
                </div>
            </form>
        </div>
    </div>
</div>
