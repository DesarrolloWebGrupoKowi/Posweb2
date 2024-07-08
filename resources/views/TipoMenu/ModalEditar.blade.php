<!-- Modal Editar Tipo de Menu-->
<div class="modal fade" id="ModalEditar{{ $tipoMenu->IdTipoMenu }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Menú</h5>
            </div>
            <div class="modal-body">
                <form action="/EditarTipoMenu/{{ $tipoMenu->IdTipoMenu }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de Tipo de Menú</label>
                        <input type="text" id="NomTipoMenu" name="NomTipoMenu" class="form-control rounded"
                            style="line-height: 18px" tabindex="1" value="{{ $tipoMenu->NomTipoMenu }}"
                            onkeyup="mayusculas(this)" required>
                    </div>
                    <div>
                        <label for="" class="form-label">Estatus</label>
                        <select name="Status" id="Status" class="form-select rounded" style="line-height: 18px">
                            <option {!! $tipoMenu->Status == 0 ? 'selected' : '' !!} value="0">Activo</option>
                            <option {!! $tipoMenu->Status == 1 ? 'selected' : '' !!} value="1">Inactivo</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button class="btn btn-warning">Editar </button>
                </form>
            </div>
        </div>
    </div>
</div>
