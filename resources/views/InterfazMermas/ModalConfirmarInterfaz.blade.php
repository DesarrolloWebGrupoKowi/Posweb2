<div class="modal fade" id="ModalConfirmarInterfaz" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Interfazar Mermas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de interfazar las mermas?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> Cerrar </button>
                <form action="/InterfazarMermas/{{ $idTienda }}/{{ $fecha1 }}/{{ $fecha2 }}"
                    method="POST">
                    @csrf
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-refresh"></i> Interfazar Mermas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
