<!-- Modal Agregar-->
<div class="modal fade" id="ModalRecalentado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Recalentar Rostisado</h5>
            </div>
            <div class="modal-body">
                <form id="formRecalentado" action="/RecalentarRosticero" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="" class="form-label mb-0">Código etiqueta anterior</label>
                        <input id="CodigoEtiquetaAnterior" class="form-control rounded" style="line-height: 18px"
                            type="text" maxlength="13" minlength="12" name="CodigoEtiquetaAnterior" required>
                    </div>
                    @foreach ($codigosEtiqueta as $codigo)
                        <div id="ant{{ $codigo->CodigoEtiqueta }}" class="d-none" data-CodigoEtiquetaAnterior>
                            <label>{{ $codigo->CodigoEtiqueta }}</label>
                            <br>
                            @foreach ($codigo->fechas as $fecha)
                                <div class="form-check form-check-inline">
                                    <input id="{{ $fecha->IdDatDetalleRosticero }}" class="form-check-input"
                                        type="radio" name="IdDatDetalleRosticeroAN"
                                        value="{{ $fecha->IdDatDetalleRosticero }}">
                                    <label for="{{ $fecha->IdDatDetalleRosticero }}"
                                        class="form-check-label">{{ strftime('%d %B %Y', strtotime($fecha->Fecha)) }}</label>
                                </div>
                            @endforeach
                            <br>
                            <span class="text-danger" style="font-size: small; font-weight: 500">
                                Selecciona una fecha de rostisado
                            </span>
                        </div>
                    @endforeach
                    <div>
                        <label for="" class="form-label mb-0">Código etiqueta nueva</label>
                        <input class="form-control rounded" style="line-height: 18px" type="text" maxlength="13"
                            minlength="12" name="CodigoEtiquetaNueva" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Guardar Recalentado">
                </form>
            </div>
        </div>
    </div>
</div>
