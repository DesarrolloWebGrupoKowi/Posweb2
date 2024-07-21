<div class="modal fade" id="ModalEliminarPreparado{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" style="white-space: wrap">Eliminar preparado </h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">¿Estas seguro de eliminar el preparado?</p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">{{$preparado->Nombre}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form class="d-flex" action="/EliminarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
