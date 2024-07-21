<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearUsuario" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de Usuario</label>
                        <input type="text" id="NomUsuario" name="NomUsuario" class="form-control rounded"
                            style="line-height: 18px" onkeypress="return (event.charCode != 32)" tabindex="1"
                            placeholder="Escribe el nombre de usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Numero de Nomina</label>
                        <input type="text" id="NumNomina" name="NumNomina" class="form-control rounded"
                            style="line-height: 18px" placeholder="Escribe el numero de nomina" tabindex="2" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Contraseña</label>
                        <input type="password" id="Password" name="Password" class="form-control rounded"
                            style="line-height: 18px" placeholder="Escribe la contraseña" tabindex="3" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Correo</label>
                        <input type="email" id="Correo" name="Correo" class="form-control rounded"
                            style="line-height: 18px" tabindex="4" placeholder="Escribe el correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo Usuario</label>
                        <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select rounded"
                            style="line-height: 18px">
                            @foreach ($tipoUsuarios as $tipoUsuario)
                                <option value="{{ $tipoUsuario->IdTipoUsuario }}">{{ $tipoUsuario->NomTipoUsuario }}
                                </option>
                            @endforeach
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar </button>
                <button type="submit" class="btn btn-warning">Guardar </button>
                </form>
            </div>
        </div>
    </div>
</div>
