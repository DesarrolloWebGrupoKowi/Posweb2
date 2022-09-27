<!--Modal Editar Estado-->
<div class="modal fade" id="ModalEditar{{$menuPosweb->cmpIdMenu}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$menuPosweb->cmpNomMenu}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="EditarMenu/{{$menuPosweb->cmpIdMenu}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre</label>
                        <input type="text" id="NomMenu" name="NomMenu" class="form-control" tabindex="2" value="{{$menuPosweb->cmpNomMenu}}" onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Menu</label>
                        <select class="form-select" name="IdTipoMenu" id="IdTipoMenu">
                            @foreach ($tipoMenus as $tipoMenu)
                                <option {!! $menuPosweb->cmpIdTipoMenu == $tipoMenu->IdTipoMenu ? 'selected' : '' !!} value="{{$tipoMenu->IdTipoMenu}}">{{$tipoMenu->NomTipoMenu}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Link</label>
                        <input type="text" id="Link" name="Link" class="form-control" tabindex="2" value="{{$menuPosweb->cmpLink}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Icono</label>
                        <input class="form-control" type="text" name="Icono" id="Icono" placeholder="fa fa-icons" value="{{ $menuPosweb->cmpIcono }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Background Color</label>
                        <input class="form-control" type="text" name="BgColor" id="BgColor" placeholder="bg-orange" value="{{ $menuPosweb->cmpBgColor }}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>