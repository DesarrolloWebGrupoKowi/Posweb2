<div class="modal fade" id="ModalEliminar{{ $pArticulo->IdDatVentaTmp }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalToggleLabel2">Eliminar Articulo de la Preventa</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EliminarArticuloPreventa/{{$pArticulo->IdDatVentaTmp}}" method="POST">
                    <input type="hidden" name="nNominaEmpleado" value="{{ empty($cliente->NumNomina) ? '' : $cliente->NumNomina }}">
                    @csrf
                    <div class="d-flex justify-content-center mb-3">
                        <img src="img/01.png" width="10%" alt="">
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>{{ $pArticulo->NomArticulo }}</h5>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-trash-o"></i> Eliminar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>