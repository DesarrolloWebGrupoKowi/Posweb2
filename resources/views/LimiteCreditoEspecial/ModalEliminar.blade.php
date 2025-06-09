<!--MODAL ELIMINAR EMPLEADO DE LIMITE ESPECIAL-->
<div class="modal fade" id="ModalEliminarEmpleado{{ $lCredito->IdCatLimiteCreditoEspecial }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Empleado</h5>
            </div>
            <form action="/CatLimiteCreditoEspecial/{{ $lCredito->IdCatLimiteCreditoEspecial }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro de eliminar el empleado?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $lCredito->Nombre }} {{ $lCredito->Apellidos }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar </button>
            </form>
        </div>
    </div>
</div>
