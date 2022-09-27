<!-- Modal Editar-->
<div class="modal fade" id="ModalEditar{{$listaPrecioTienda->ClptIdListaPrecio}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$listaPrecioTienda->CtNomTienda}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/AgregarListaPrecioTienda" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Lista de Precio</label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary" value="Crear Lista">
                </form>
            </div>
        </div>
    </div>
</div>