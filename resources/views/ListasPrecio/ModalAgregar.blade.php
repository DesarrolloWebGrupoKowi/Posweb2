<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Lista de Precio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearListaPrecio" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Lista de Precio</label>
                        <input type="text" id="NomListaPrecio" name="NomListaPrecio" class="form-control" onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Peso Minimo</label>
                        <input class="form-control" type="number" name="PesoMinimo" id="PesoMinimo" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Peso Maximo</label>
                        <input class="form-control" type="number" name="PesoMaximo" id="PesoMaximo" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Iva</label>
                        <input class="form-control" type="number" name="PorcentajeIva" id="PorcentajeIva" step="0.01" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <input class="form-check-input" type="checkbox" name="checkExistente" id="checkExistente">
                        </span>
                        <span class="input-group-text">Crear a Partir de una Lista de Precios Existente</span>
                    </div>
                    <div id="divSelectListaPrecio" class="mb-3" style="display: none">
                        <select class="form-select" name="selectListaPrecio" id="selectListaPrecio">
                            @foreach ($selectListaPrecio as $listaPrecio)
                                <option value="{{$listaPrecio->IdListaPrecio}}">{{$listaPrecio->NomListaPrecio}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-save"></i> Crear
                </button>
                </form>
            </div>
        </div>
    </div>
</div>