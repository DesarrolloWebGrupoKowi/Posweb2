<div class="modal fade" id="ModalRostisado" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Lote rostisado</h5>
            </div>
            <div class="modal-body">
                @foreach ($codigosEtiqueta as $codigo)
                    <div id="{{ $codigo->CodigoEtiqueta }}" class="d-none">
                        <label>{{ $codigo->CodigoEtiqueta }}</label>
                        <br>
                        @foreach ($codigo->fechas as $fecha)
                            <div class="form-check form-check-inline">
                                <input id="{{ $fecha->IdDatDetalleRosticero }}" class="form-check-input" type="radio"
                                    name="IdDatDetalleRosticero" value="{{ $fecha->IdDatDetalleRosticero }}">
                                <label for="{{ $fecha->IdDatDetalleRosticero }}"
                                    class="form-check-label">{{ strftime('%d %B %Y', strtotime($fecha->Fecha)) }}</label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-dark" data-bs-dismiss="modal">Guardar Venta </button>
            </div>
        </div>
    </div>
</div>
