<!-- Modal Eliminar-->
<div class="modal fade" id="ModalEliminar{{ $tienda->IdTienda }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar tienda</h5>
            </div>
            <div class="modal-body">
                <form action="EliminarTienda/{{ $tienda->IdTienda }}" method="POST">
                    @csrf
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro que desea eliminar esa tienda?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $tienda->NomTienda }}
                    </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <button class="btn btn-sm btn-danger">Elimnar </button>
                </form>
            </div>
        </div>
    </div>
</div>
