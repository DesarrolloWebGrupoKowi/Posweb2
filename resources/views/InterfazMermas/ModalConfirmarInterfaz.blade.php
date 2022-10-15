<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarInterfaz" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Interfazar Mermas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="titulo">¿Desea Interfazar Mermas?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
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
