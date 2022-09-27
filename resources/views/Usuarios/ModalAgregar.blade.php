<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/CrearUsuario" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Nombre de Usuario</label>
                            <input type="text" id="NomUsuario" name="NomUsuario" class="form-control" onkeypress="return (event.charCode != 32)" tabindex="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Numero de Nomina</label>
                            <input type="text" id="NumNomina" name="NumNomina" class="form-control" tabindex="2" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Contraseña</label>
                            <input type="password" id="Password" name="Password" class="form-control" tabindex="3" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Correo</label>
                            <input type="email" id="Correo" name="Correo" class="form-control" tabindex="4" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tipo Usuario</label>
                            <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select">
                                @foreach($tipoUsuarios as $tipoUsuario)
                                <option value="{{$tipoUsuario->IdTipoUsuario}}">{{$tipoUsuario->NomTipoUsuario}}</option>
                                @endforeach
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>