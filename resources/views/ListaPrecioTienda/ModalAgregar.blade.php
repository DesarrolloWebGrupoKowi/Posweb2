<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Lista de Precio a Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearListaPrecioTienda" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tienda</label>
                        <select class="form-select" name="IdTienda" id="IdTienda">
                            @foreach ($tiendas as $tienda)
                                <option value="{{$tienda->IdTienda}}">{{$tienda->NomTienda}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Seleccione Listas de Precio</label>
                        @foreach ($listasPrecio as $listaPrecio)
                            <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="chkListaPrecio[]" value="{{$listaPrecio->IdListaPrecio}}">
                                        <label class="form-check-label" for="">
                                        {{$listaPrecio->NomListaPrecio}}
                                    </label>
                            </div>
                        @endforeach
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary" value="Guardar">
                </form>
            </div>
        </div>
    </div>
</div>