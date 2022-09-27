<!-- Modal Agregar-->
<div class="modal fade" data-bs-backdrop="static" id="ModalAgregarCajaTienda" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Caja Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/AgregarCajaTienda" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-auto">
                            <label for="">Tienda:</label>
                            <select class="form-select shadow" name="idTiendaCaja" id="idTiendaCaja">
                                @foreach ($tiendas as $tienda)
                                    <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <label for="">Numero de Caja:</label>
                            <select class="form-select shadow" name="idCaja" id="idCaja">
                                @foreach ($cajas as $caja)
                                    <option value="{{ $caja->IdCaja }}">{{ $caja->NumCaja }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
