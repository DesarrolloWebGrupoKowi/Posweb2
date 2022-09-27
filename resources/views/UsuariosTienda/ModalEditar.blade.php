<!-- Modal Editar Usuario Tienda-->
<div class="modal fade" id="ModalEditar{{$usuarioTienda->IdUsuarioTienda}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Editar Usuario: {{$usuarioTienda->NomUsuario}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="EditarUsuarioTienda/{{$usuarioTienda->IdUsuarioTienda}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Seleccione Una Opción</label>
                            <div class="form-check">
                                <input {!! $usuarioTienda->Todas == 0 ? 'checked' : '' !!} class="form-check-input" type="radio" name="radioEdit" id="radioTodasEdit{{$usuarioTienda->IdUsuarioTienda}}" value="todas" onclick="OpcionesEdit({{$usuarioTienda->IdUsuarioTienda}})">
                                <label class="form-check-label" for="radioTodas">
                                  Todas Las Tiendas y Plazas
                                </label>
                            </div>
                            <div class="form-check">
                                <input {!! $usuarioTienda->IdPlaza != null ? 'checked' : '' !!} class="form-check-input" type="radio" name="radioEdit" id="radioPlazaEdit{{$usuarioTienda->IdUsuarioTienda}}" value="plaza" onclick="OpcionesEdit({{$usuarioTienda->IdUsuarioTienda}})">
                                <label class="form-check-label" for="radioPlaza">
                                  Plaza
                                </label>
                            </div>
                            <div class="form-check">
                                <input {!! $usuarioTienda->IdTienda != null ? 'checked' : '' !!} class="form-check-input" type="radio" name="radioEdit" id="radioTiendaEdit{{$usuarioTienda->IdUsuarioTienda}}" value="tienda" onclick="OpcionesEdit({{$usuarioTienda->IdUsuarioTienda}})">
                                <label class="form-check-label" for="radioTienda">
                                  Tienda
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="divTiendaEdit{{$usuarioTienda->IdUsuarioTienda}}" style="display: {!! $usuarioTienda->IdTienda == null ? 'none' : 'block' !!}">
                            <label for="" class="form-label">Tienda</label>
                            <select name="IdTienda" id="IdTienda" class="form-select">
                            @foreach($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $usuarioTienda->IdTienda ? 'selected' : '' !!} value="{{$tienda->IdTienda}}">{{$tienda->NomTienda}}</option>
                            @endforeach 
                            </select>
                        </div>
                        <div class="mb-3" id="divPlazaEdit{{$usuarioTienda->IdUsuarioTienda}}" style="display: {!! $usuarioTienda->IdPlaza == null ? 'none' : 'block' !!}">
                            <label for="" class="form-label">Plaza</label>
                            <select name="IdPlaza" id="IdPlaza" class="form-select">
                            @foreach($plazas as $plaza)
                            <option {!! $plaza->IdPlaza == $usuarioTienda->IdPlaza ? 'selected' : '' !!} value="{{$plaza->IdPlaza}}">{{$plaza->NomPlaza}}</option>
                            @endforeach
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button class="btn btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>