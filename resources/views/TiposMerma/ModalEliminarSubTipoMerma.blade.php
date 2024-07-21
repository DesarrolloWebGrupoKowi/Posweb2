<!-- Modal Eliminar Sub Tipo Merma-->
<div class="modal fade" id="ModalEliminarSubTipoMerma{{ $subTipoMerma->IdSubTipoMerma }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Sub Tipo de Merma</h5>
            </div>
            <form action="/EliminarSubTipoMerma/{{ $subTipoMerma->IdSubTipoMerma }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro que desea eliminar este sub tipo de merma?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $subTipoMerma->NomSubTipoMerma }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button class="btn btn-sm btn-danger">Eliminar </button>
                </div>
            </form>
        </div>
    </div>
</div>
