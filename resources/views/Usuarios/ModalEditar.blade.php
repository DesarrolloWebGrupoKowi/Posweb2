<!-- Modal Editar-->
<div class="modal fade" id="ModalEditar{{$usuario->IdUsuario}}-{{$usuario->NomUsuario}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Usuario: {{$usuario->NomUsuario}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form action="Editar/{{$usuario->IdUsuario}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Numero de Nomina</label>
                            <input type="text" id="NumNomina" name="NumNomina" class="form-control" tabindex="2" required value="{{$usuario->NumNomina}}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Correo</label>
                            <input type="email" id="Correo" name="Correo" class="form-control" tabindex="4" required value="{{$usuario->Correo}}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tipo Usuario</label>
                            <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select">
                                @foreach($tipoUsuarios as $tipoUsuario)
                                <option {!! $usuario->IdTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{$tipoUsuario->IdTipoUsuario}}">{{$tipoUsuario->NomTipoUsuario}}</option>
                                @endforeach
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Editar Usuario
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
