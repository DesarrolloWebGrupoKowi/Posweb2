<div class="modal fade" id="ModalConfirmarInterfaz" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Interfazar Bajas</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de dar baja a los productos?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <form action="/InterfazarRosticeroBaja/{{ $idTienda }}/{{ $fecha1 }}/{{ $fecha2 }}"
                    method="POST">
                    @csrf
                    <button class="btn btn-sm btn-warning">Interfazar Bajas </button>
                </form>
            </div>
        </div>
    </div>
</div>
