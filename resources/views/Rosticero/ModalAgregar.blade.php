<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Rostisado</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearRosticero" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="CodigoVenta" class="form-label">Materia venta</label>
                        <select name="CodigoVenta" id="CodigoVenta" class="CodigoVenta form-select">
                            @foreach ($articulos as $articulo)
                                <option value="{{ $articulo->IdCatRosticeroArticulos }}">
                                    {{ $articulo->articuloVenta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="" class="form-label">Cantidad materia prima</label>
                        <input class="form-control" type="number" step="any" min="0.1" name="CantidadMatPrima"
                            placeholder="Cantidad materia prima" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Crear Rostisado">
                </form>
            </div>
        </div>
    </div>
</div>
