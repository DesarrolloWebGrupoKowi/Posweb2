<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregarDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/AgregarArticuloDePreparados/{{ $idPreparado }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar detalle de preparado</h5>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Escribe el nombre del articulo</label>
                        <input class=" form-control" list="articulos" name="codigo" id="codigo"
                            placeholder="Buscar articulo" autocomplete="off" onkeypress="return event.keyCode != 13;"
                            required>
                        <datalist id="articulos">
                            @foreach ($articulos as $articulo)
                                <option class="prom{{ $articulo->CodArticulo }}" value="{{ $articulo->CodArticulo }}"">
                                    {{ $articulo->NomArticulo }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Cantidad</label>
                        <input type="number" name="cantidad" min="0" class="form-control"
                            placeholder="Cantidad" step=".01">
                    </div>
                    {{-- <div class="mb-3">
                        <label for="" class="form-label mb-0">Lista de precios</label>
                        <select name="IdListaPrecio" id="IdListaPrecio" class="form-select">
                            @foreach ($listaPrecios as $lista)
                                <option value="{{ $lista->IdListaPrecio }}">{{ $lista->NomListaPrecio }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Agregar preparado">
                </div>
            </form>
        </div>
    </div>
</div>
