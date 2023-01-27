<!-- Modal Desbloquear Empleado -->
<div class="modal fade" data-bs-backdrop="static" id="DesbloquearEmpleado{{ $bloqueo->NumNomina }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-user-plus"></i> Desbloquear Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/DesbloquearEmpleado/{{ $bloqueo->NumNomina }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <h4 style="text-align: center">¿Desea desbloquear el empleado: {{ $bloqueo->Empleado->Nombre }}
                            {{ $bloqueo->Empleado->Apellidos }} ?</h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button id="btnDesbloquear" class="btn btn-warning">
                        <i class="fa fa-user-plus"></i> Desbloquear Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
