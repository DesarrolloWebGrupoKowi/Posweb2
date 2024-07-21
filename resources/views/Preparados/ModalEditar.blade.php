<div class="modal fade" id="ModalEditar{{ $preparado->IdPreparado }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form action="/EditarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Preparado</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3" style="line-height: 24px">
                        <label class="form-label mb-0">Nombre del preparado</label>
                        <input type="text" name="nombre" class="form-control rounded" style="line-height: 18px"
                            value="{{ $preparado->Nombre }}" placeholder="Nombre del preparado" autofocus>

                    </div>
                    <div class="mb-3" style="line-height: 24px">
                        <label class="form-label mb-0">Cantidad</label>
                        <input type="number" name="cantidad" min="0" class="form-control rounded"
                            style="line-height: 18px" value="{{ $preparado->Cantidad }}" placeholder="Cantidad">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-sm btn-primary" value="Editar Preparado">
                </div>
            </form>
        </div>
    </div>
</div>
