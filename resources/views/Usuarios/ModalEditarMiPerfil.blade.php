<!-- Modal Editar Mi Pefil-->
<div class="modal fade" id="ModalEditarMiPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Mi Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="">Correo</label>
                    <input type="text" class="form-control" name="" id="" placeholder="e-mail"
                        value="{{ Auth::user()->Correo }}" required>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i>
                    Cerrar</button>
                <button class="btn btn-warning"><i class="fa fa-save"></i> Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <!--Modal Cambiar Contraseña-->
    <div class="modal fade" id="ModalCambiarPassword">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/CambiarPassword/{{ Auth::user()->IdUsuario }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editPassword">Contraseña</label>
                            <div class="input-group mb-3">
                                <button type="button" class="input-group-text" id="verPassword"><span
                                        class="material-icons">visibility</span></button>
                                <input type="password" class="form-control" placeholder="Contraseña Nueva"
                                    name="editPassword" id="editPassword" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#ModalEditarMiPerfil">
                            <i class="fa fa-arrow-left"></i> Regresar
                        </button>
                        <button class="btn btn-warning">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/CambiarPasswordScript.js"></script>
