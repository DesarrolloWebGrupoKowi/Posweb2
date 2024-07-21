<!--Modal Cambiar Password-->

<div class="modal fade" id="modalCambiarPassword{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Cambiar Contraseña</h5>
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
                            <input type="password" class="form-control rounded" style="line-height: 18px"
                                id="Password1{{ $usuario->IdUsuario }}" name="Password1" placeholder="Contraseña"
                                aria-label="Password" aria-describedby="btnPass1" required>
                            <button id="btnPass1" class="input-group-text" type="button"
                                onclick="mostrarPass1({{ $usuario->IdUsuario }})">
                                @include('components.icons.eye')
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control rounded" style="line-height: 18px"
                                id="Password2{{ $usuario->IdUsuario }}" name="Password2"
                                placeholder="Confirmar Contraseña" aria-label="Password" aria-describedby="btnPass1"
                                required>
                            <button id="btnPass1" class="input-group-text" type="button"
                                onclick="mostrarPass2({{ $usuario->IdUsuario }})">
                                @include('components.icons.eye')
                            </button>
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
