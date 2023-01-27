<!-- Modal Eliminar Ajuste -->
<div class="modal fade" data-bs-backdrop="static" id="ModalEliminarAjuste{{ $credito->IdEncabezado }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Ajuste de Deuda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/EliminarAjuste/{{ $credito->IdEncabezado }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <h4 style="text-align: center">¿Desea eliminar el ajuste?</h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button id="btnAjustaDeuda" class="btn btn-sm btn-danger">
                        <i class="fa fa-check"></i> Eliminar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>