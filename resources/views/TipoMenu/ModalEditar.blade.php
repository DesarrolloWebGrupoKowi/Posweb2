<!-- Modal Editar Tipo de Menu-->
<div class="modal fade" id="ModalEditar{{$tipoMenu->IdTipoMenu}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarTipoMenu/{{$tipoMenu->IdTipoMenu}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de Tipo de Menú</label>
                        <input type="text" id="NomTipoMenu" name="NomTipoMenu" class="form-control" tabindex="1" value="{{$tipoMenu->NomTipoMenu}}" onkeyup="mayusculas(this)" required>
                    </div>
                    <div>
                        <label for="" class="form-label">Status</label>
                        <select name="Status" id="Status" class="form-select">
                            <option {!! $tipoMenu->Status == 0 ? 'selected' : '' !!} value="0">Activo</option>
                            <option {!! $tipoMenu->Status == 1 ? 'selected' : '' !!} value="1">Inactivo</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-warning">
                    <i class="fa fa-edit"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>