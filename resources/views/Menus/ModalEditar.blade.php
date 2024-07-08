<!--Modal Editar Estado-->
<div class="modal fade" id="ModalEditar{{ $menuPosweb->cmpIdMenu }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar menú</h5>
            </div>
            <div class="modal-body">
                <form action="EditarMenu/{{ $menuPosweb->cmpIdMenu }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2 flex-column flex-sm-row">
                        <div style="flex: 1">
                            <label for="" class="form-label">Nombre</label>
                            <input type="text" id="NomMenu" name="NomMenu" class="form-control rounded"
                                style="line-height: 18px" tabindex="2" value="{{ $menuPosweb->cmpNomMenu }}"
                                onkeyup="mayusculas(this)" required>
                        </div>
                        <div class="mb-3" style="flex: 1">
                            <label for="" class="form-label">Tipo de Menu</label>
                            <select class="form-select rounded" style="line-height: 18px" name="IdTipoMenu"
                                id="IdTipoMenu">
                                @foreach ($tipoMenus as $tipoMenu)
                                    <option {!! $menuPosweb->cmpIdTipoMenu == $tipoMenu->IdTipoMenu ? 'selected' : '' !!} value="{{ $tipoMenu->IdTipoMenu }}">
                                        {{ $tipoMenu->NomTipoMenu }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Link</label>
                        <input type="text" id="Link" name="Link" class="form-control rounded"
                            style="line-height: 18px" tabindex="2" value="{{ $menuPosweb->cmpLink }}" required>
                    </div>
                    <div class="d-flex gap-2 flex-column flex-sm-row">
                        <div style="flex: 1">
                            <label for="" class="form-label">Icono</label>
                            <input class="form-control rounded" style="line-height: 18px" type="text" name="Icono"
                                id="Icono" placeholder="fa fa-icons" value="{{ $menuPosweb->cmpIcono }}" required>
                        </div>
                        <div class="mb-3" style="flex: 1">
                            <label for="" class="form-label">Background Color</label>
                            <input class="form-control rounded" style="line-height: 18px" type="text" name="BgColor"
                                id="BgColor" placeholder="bg-orange" value="{{ $menuPosweb->cmpBgColor }}" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-warning">Editar </button>
                </form>
            </div>
        </div>
    </div>
</div>
