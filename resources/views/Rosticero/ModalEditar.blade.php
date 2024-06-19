<!-- Modal Agregar-->
<div class="modal fade" id="ModalEditar{{ $rostisado->IdDatRosticero }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Rostisado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarRosticero/{{ $rostisado->IdDatRosticero }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="CodigoMatPrima" class="form-label">Materia prima</label>
                            <select name="CodigoMatPrima" id="CodigoMatPrima" class="CodigoMatPrima form-select">
                                @foreach ($articulos as $articulo)
                                    <option value="{{ $articulo->IdCatRosticeroArticulos }}"
                                        {{ $articulo->CodigoMatPrima == $rostisado->CodigoMatPrima ? 'selected' : '' }}>
                                        {{ $articulo->articuloPrima }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="CodigoVenta" class="form-label">Materia venta</label>
                            <select name="CodigoVenta" id="CodigoVenta" class="CodigoVenta form-select">
                                @foreach ($articulos as $articulo)
                                    <option value="{{ $articulo->IdCatRosticeroArticulos }}"
                                        {{ $articulo->CodigoVenta == $rostisado->CodigoVenta ? 'selected' : '' }}>
                                        {{ $articulo->articuloVenta }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="" class="form-label">Cantidad materia prima</label>
                            <input class="form-control" type="number" step="any" min="0.1"
                                name="CantidadMatPrima" placeholder="Cantidad materia prima" required
                                value="{{ $rostisado->CantidadMatPrima }}">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Cantidad materia venta</label>
                            <input class="form-control" type="number" step="any" min="0.1"
                                name="CantidadVenta" placeholder="Cantidad materia venta" required
                                value="{{ $rostisado->CantidadVenta }}">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Crear Rostisado">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('change', e => {
        if (e.target.matches('.CodigoMatPrima')) {
            e.target.parentNode.parentNode.querySelector('.CodigoVenta').value = e.target.value;
        }
        if (e.target.matches('.CodigoVenta')) {
            e.target.parentNode.parentNode.querySelector('.CodigoMatPrima').value = e.target.value;
        }
    })
</script>
