<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario Tienda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/CrearUsuarioTienda" method="POST">
                        @csrf
                        @if(empty($usuarios))
                            <h4 style="text-align: center;">No Hay Usuarios Por Agregar</h4>
                        @else
                        <div class="mb-3">
                            <label for="" class="form-label">Usuario</label>
                            <select name="IdUsuario" id="IdUsuario" class="form-select">
                                @foreach($usuarios as $usuario)
                                <option value="{{$usuario->IdUsuario}}">{{$usuario->NomUsuario}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Seleccione Opción</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="radioTodas" value="todas" onclick="Opciones()">
                                <label class="form-check-label" for="radioTodas">
                                  Todas las Tiendas y Plazas
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="radioPlaza" value="plaza" onclick="Opciones()">
                                <label class="form-check-label" for="radioPlaza">
                                  Plaza
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="radioTienda" value="tienda" onclick="Opciones()">
                                <label class="form-check-label" for="radioTienda">
                                  Tienda
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="divTienda" style="display: none">
                            <label for="" class="form-label">Tienda</label>
                            <select name="IdTienda" id="IdTienda" class="form-select">
                                <!--<option value="">SIN TIENDA</option>-->
                                @foreach($tiendas as $tienda)
                                <option value="{{$tienda->IdTienda}}">{{$tienda->NomTienda}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="divPlaza" style="display: none">
                            <label for="" class="form-label">Plaza</label>
                            <select name="IdPlaza" id="IdPlaza" class="form-select">
                                <!--<option value="">SIN PLAZA</option>-->
                                @foreach($plazas as $plaza)
                                <option value="{{$plaza->IdPlaza}}">{{$plaza->NomPlaza}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <input {!! $usuarios == null ? 'hidden' : '' !!} type="submit" class="btn btn-warning" value="Asignar">
                    </form>
                </div>
            </div>
        </div>
    </div>