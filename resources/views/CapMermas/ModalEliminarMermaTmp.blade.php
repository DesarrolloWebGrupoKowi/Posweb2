<div class="modal fade" id="ModalEliminarMermaTmp{{ $merma->IdTmpMerma }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar articulo</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de eliminar el articulo?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $merma->NomArticulo }} - {{ number_format($merma->CantArticulo, 2) }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal"> Cerrar </button>
                <form action="/EliminarMermaTmp/{{ $merma->IdTmpMerma }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger"> Eliminar </button>
                </form>
            </div>
        </div>
    </div>
</div>
