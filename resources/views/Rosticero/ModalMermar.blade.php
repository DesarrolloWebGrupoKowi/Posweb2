<!-- Modal Agregar-->
<div class="modal fade" id="ModalMermar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mermar Rostisado</h5>
            </div>
            <form id="formMermar" action="/MermarRosticero" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-2">
                        <label for="" class="form-label mb-0">Código etiqueta</label>
                        <input id="CodigoEtiqueta" class="form-control rounded" style="line-height: 18px" type="text"
                            maxlength="13" minlength="12" name="CodigoEtiqueta" required>
                    </div>
                    @foreach ($codigosEtiqueta as $codigo)
                        <div id="{{ $codigo->CodigoEtiqueta }}" class="d-none" data-CodigoEtiqueta>
                            <label>{{ $codigo->CodigoEtiqueta }}</label>
                            <br>
                            @foreach ($codigo->fechas as $fecha)
                                <div class="form-check form-check-inline">
                                    <input id="ant{{ $fecha->IdDatDetalleRosticero }}" class="form-check-input"
                                        type="radio" name="IdDatDetalleRosticero"
                                        value="{{ $fecha->IdDatDetalleRosticero }}">
                                    <label for="ant{{ $fecha->IdDatDetalleRosticero }}"
                                        class="form-check-label">{{ strftime('%d %B %Y', strtotime($fecha->Fecha)) }}</label>
                                </div>
                            @endforeach
                            <br>
                            <span class="text-danger" style="font-size: small; font-weight: 500">
                                Selecciona una fecha de rostisado
                            </span>
                        </div>
                    @endforeach
                    <div class="mb-2">
                        <label for="tipoMerma" class="form-label">Tipo de merma</label>
                        <select name="tipoMerma" id="tipoMerma" class="form-select rounded" required
                            style="line-height: 18px">
                            <option value="">Selecciona el tipo de merma</option>
                            @foreach ($tiposMermas as $merma)
                                <option value="{{ $merma->IdTipoMerma }}">
                                    {{ $merma->NomTipoMerma }}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach ($tiposMermas as $merma)
                        @if (isset($merma->detalle[0]->IdTipoMerma))
                            <div id="subtipomerma{{ $merma->detalle[0]->IdTipoMerma }}"
                                class="subtiposmerma d-none mb-2">
                                <label for="subMerma" class="form-label">Sub tipo de merma</label>
                                <select name="{{ $merma->IdTipoMerma }}" class="form-select rounded"
                                    style="line-height: 18px">
                                    <option value="">Selecciona el sub tipo de merma</option>
                                    @foreach ($merma->detalle as $detalle)
                                        <option value="{{ $detalle->IdSubTipoMerma }}">
                                            {{ $detalle->NomSubTipoMerma }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-sm btn-warning" value="Guardar Recalentado">
                </div>
            </form>
        </div>
    </div>
</div>
