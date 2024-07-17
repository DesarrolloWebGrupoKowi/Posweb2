<!-- Modal Articulos Paquete-->
<div class="modal fade" id="ModalCantidadRecepcion{{ $paquete->IdPaquete }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0">
            <form action="/Paquetes/{{ $paquete->IdPreparado }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar cantidad recepcion</h5>
                </div>
                <div class="modal-body">
                    {{ $paquete->NomPaquete }}
                    @csrf
                    <div class="mb-3">
                        <label class="form-label mb-0">Cantidad recepción</label>
                        <input type="number" name="cantidad" class="form-control rounded" style="line-height: 18px"
                            placeholder="Cantidad recepción" autofocus value="{{ $paquete->CantidadEnvio }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <input type="submit" class="btn btn-sm btn-warning" value="Actualizar">
                </div>
            </form>
        </div>
    </div>
</div>
