<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearCatProdDiez" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Codigo de producto</label>
                        <input type="text" name="CodArticulo" class="form-control rounded" style="line-height: 18px"
                            tabindex="1" required placeholder="Escribe el codigo de producto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peso Minimo</label>
                        <input type="number" min=".1" step=".1" name="PesoMinimo"
                            class="form-control rounded" style="line-height: 18px" tabindex="1" required
                            placeholder="Peso minimo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peso Maximo</label>
                        <input type="number" min=".1" step=".1" name="PesoMaximo"
                            class="form-control rounded" style="line-height: 18px" tabindex="1" required
                            placeholder="Peso maximo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lista de precios</label>
                        <select name="IdListaPrecio" class="form-select rounded" style="line-height: 18px">
                            @foreach ($listaPrecios as $item)
                                <option value="{{ $item->IdListaPrecio }}">{{ $item->NomListaPrecio }}</option>
                            @endforeach
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Agregar Artículo">
                </form>
            </div>
        </div>
    </div>
</div>
