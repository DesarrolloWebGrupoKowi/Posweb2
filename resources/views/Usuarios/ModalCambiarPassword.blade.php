<!--Modal Cambiar Password-->

<div class="modal fade" id="modalCambiarPassword{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <h4 class="tituloUsuario">{{ $usuario->NomUsuario }}</h4>
                </div>
                <form action="/CambiarContraseña/{{ $usuario->IdUsuario }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nueva Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="Password1{{ $usuario->IdUsuario }}"
                                name="Password1" placeholder="Contraseña" aria-label="Password"
                                aria-describedby="btnPass1" required>
                            <button id="btnPass1" class="btn btn-default" type="button"
                                onclick="mostrarPass1({{ $usuario->IdUsuario }})"><span
                                    class="material-icons">visibility</span></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="Password2{{ $usuario->IdUsuario }}"
                                name="Password2" placeholder="Confirmar Contraseña" aria-label="Password"
                                aria-describedby="btnPass1" required>
                            <button id="btnPass1" class="btn btn-default" type="button"
                                onclick="mostrarPass2({{ $usuario->IdUsuario }})"><span
                                    class="material-icons">visibility</span></button>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-unlock-alt"></i> Cambiar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
