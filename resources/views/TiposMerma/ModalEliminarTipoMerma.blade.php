<!--MODAL ELIMINAR TIPO DE MERMA-->
<div class="modal fade" id="ModalEliminarArticuloTipoMerma{{ $tipoMerma->IdTipoMerma }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar el Tipo de Merma</h5>
            </div>
            <form action="/EliminarTipoMerma/{{ $tipoMerma->IdTipoMerma }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro de eliminar el tipo de merma?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $tipoMerma->NomTipoMerma }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar </button>
            </form>
        </div>
    </div>
</div>
