<!-- Modal Editar-->
<div class="modal fade" id="ModalEditar{{$tienda->IdTienda}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$tienda->NomTienda}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="EditarTienda/{{$tienda->IdTienda}}" method="POST">
                    @csrf
                <div class="container-fluid">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-9">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Direccion
                                </span>
                                <input class="form-control" type="text" name="Direccion" id="Direccion" value="{{$tienda->Direccion}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Plaza
                                </span>
                                    <select class="form-select" name="IdPlaza" id="IdPlaza">
                                        @foreach($plazas as $plaza)
                                        <option {!! $plaza->IdPlaza == $tienda->IdPlaza ? 'selected' : '' !!} value="{{$plaza->IdPlaza}}">{{$plaza->NomPlaza}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Colonia
                                </span>
                                    <input class="form-control" type="text" name="Colonia" id="Colonia" value="{{$tienda->Colonia}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Correo
                                </span>
                                    <input type="email" name="Correo" id="Correo" class="form-control" value="{{$tienda->Correo}}" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Teléfono
                                </span>
                                    <input type="tel" name="Telefono" id="Telefono" class="form-control" value="{{$tienda->Telefono}}" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Centro de Costo
                                </span>
                                    <input type="text" name="CentroCosto" id="CentroCosto" class="form-control" value="{{$tienda->CentroCosto}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Ciudad
                                </span>
                                    <select name="IdCiudad" id="IdCiudad" class="form-select">
                                        @foreach($ciudades as $ciudad)
                                        <option {!! $ciudad->IdCiudad == $tienda->IdCiudad ? 'selected' : '' !!} value="{{$ciudad->IdCiudad}}">{{$ciudad->NomCiudad}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Almacen
                                </span>
                                    <input type="text" name="Almacen" id="Almacen" class="form-control" value="{{$tienda->Almacen}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Organization_Name
                                </span>
                                    <input type="text" name="Organization_Name" id="Organization_Name" class="form-control" value="{{$tienda->Organization_Name}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Subinventory_Code
                                </span>
                                    <input type="text" name="Subinventory_Cloud" id="Subinventory_Cloud" class="form-control" value="{{$tienda->Subinventory_Code}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Servicio a Domicilio
                                </span>
                                    <select class="form-select" name="ServicioaDomicilio" id="ServicioaDomicilio">
                                        <option {!! $tienda->ServicioaDomicilio == 0 ? 'selected' : '' !!} value="0">Activo</option>
                                        <option {!! $tienda->ServicioaDomicilio == 1 ? 'selected' : '' !!} value="1">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Costo
                                </span>
                                    <input class="form-control" type="number" name="CostoaDomicilio" id="CostoaDomicilio" value="{{$tienda->CostoaDomicilio}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Comentario
                                </span>
                                    <input type="text" name="Comentario" id="Comentario" class="form-control" value="{{$tienda->Comentario}}" onkeyup="mayusculas(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Order_Type_Cloud
                                </span>
                                    <input type="text" name="Order_Type_Cloud" id="Oder_Type_Cloud" class="form-control" value="{{$tienda->Order_Type_Cloud}}" onkeyup="mayusculas(this)" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Tienda Local Activa
                                </span>
                                    <select class="form-select" name="TiendaActiva" id="TiendaActiva">
                                        <option {!! $tienda->TiendaActiva == 0 ? 'selected' : '' !!} value="0">Activa</option>
                                        <option {!! $tienda->TiendaActiva == 1 ? 'selected' : '' !!} value="1">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Inventario
                                </span>
                                <select class="form-select" name="Inventario" id="Inventario">
                                    <option {!! $tienda->Inventario == 0 ? 'selected' : '' !!} value="0">Activo</option>
                                    <option {!! $tienda->Inventario == 1 ? 'selected' : '' !!} value="1">Inactivo</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="list-group">
                                    <span class="list-group-item list-group-item-action bg-dark text-white" aria-current="true">
                                        Lista de Precios
                                </span>
                                    <input type="text" name="IdListaPrecios" id="IdListaPrecios" class="form-control" value="{{$tienda->IdListaPrecios}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-warning">
                    <i class="fa fa-pencil-square-o"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>