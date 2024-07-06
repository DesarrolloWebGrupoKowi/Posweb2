<!-- Modal Editar-->
<div class="modal fade" id="ModalEditar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Usuario: {{ $usuario->NomUsuario }}</h5>
            </div>

            <div class="modal-body">
                <form action="Editar/{{ $usuario->IdUsuario }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Numero de Nomina</label>
                        <input type="text" id="NumNomina" name="NumNomina" class="form-control rounded"
                            style="line-height: 18px" tabindex="2" required value="{{ $usuario->NumNomina }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Correo</label>
                        <input type="email" id="Correo" name="Correo" class="form-control rounded"
                            style="line-height: 18px" tabindex="4" required value="{{ $usuario->Correo }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo Usuario</label>
                        <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select rounded"
                            style="line-height: 18px">
                            @foreach ($tipoUsuarios as $tipoUsuario)
                                <option {!! $usuario->IdTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{ $tipoUsuario->IdTipoUsuario }}">
                                    {{ $tipoUsuario->NomTipoUsuario }}</option>
                            @endforeach
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-warning">Editar Usuario </button>
                </form>
            </div>
        </div>
    </div>
</div>
