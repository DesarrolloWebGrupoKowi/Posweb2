<!--Modal Editar Estado-->
<div class="modal fade" id="ModalEditar{{$listaPrecio->IdListaPrecio}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$listaPrecio->NomListaPrecio}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="EditarListaPrecio/{{$listaPrecio->IdListaPrecio}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Peso Minimo</label>
                        <input class="form-control" type="number" name="PesoMinimo" id="PesoMinimo" step="0.001" value="{{$listaPrecio->PesoMinimo}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Peso Maximo</label>
                        <input class="form-control" type="number" name="PesoMaximo" id="PesoMaximo" step="0.001" value="{{$listaPrecio->PesoMaximo}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Iva</label>
                        <input class="form-control" type="number" name="PorcentajeIva" id="PorcentajeIva" step="0.01" value="{{$listaPrecio->PorcentajeIva}}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>