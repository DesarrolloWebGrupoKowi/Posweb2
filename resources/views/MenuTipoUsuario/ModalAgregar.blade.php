<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Menú Posweb</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearMenuTipoUsuario" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Usuario</label>
                        <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select">
                            @foreach ($tipoUsuarios as $tipoUsuario)
                                <option value="{{$tipoUsuario->IdTipoUsuario}}">{{$tipoUsuario->NomTipoUsuario}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Menú</label>
                        <select name="IdMenu" id="IdMenu" class="form-select">
                            @foreach ($Menus as $Menu)
                                <option value="{{$Menu->IdMenu}}">{{$Menu->NomMenu}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary" value="Crear Menu">
                </form>
            </div>
        </div>
    </div>
</div>