<!-- Modal Editar Monedero Electronico-->
<div class="modal fade" id="ModalEditar{{ $monedero->IdCatMonederoElectronico }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Monedero Electrónico</h5>
            </div>
            <form action="/EditarMonederoElectronico/{{ $monedero->IdCatMonederoElectronico }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for=""><strong>Maximo Acumulado ($)</strong></label>
                            <input type="number" class="form-control rounded" style="line-height: 18px"
                                name="maximoAcumulado" id="maximoAcumulado" value="{{ $monedero->MaximoAcumulado }}">
                        </div>
                        <div class="col-3">
                            <label for=""><strong>Multiplo ($)</strong></label>
                            <input type="number" class="form-control rounded" style="line-height: 18px" name="multiplo"
                                id="multiplo" value="{{ $monedero->MonederoMultiplo }}">
                        </div>
                        <div class="col-3">
                            <label for=""><strong>Pesos Por Multiplo ($)</strong></label>
                            <input type="number" class="form-control rounded" style="line-height: 18px"
                                name="pesosPorMultiplo" id="pesosPorMultiplo" value="{{ $monedero->PesosPorMultiplo }}">
                        </div>
                        <div class="col-3">
                            <label for=""><strong>Vigencia (dias)</strong></label>
                            <input type="number" class="form-control rounded" style="line-height: 18px" name="vigencia"
                                id="vigencia" value="{{ $monedero->VigenciaMonedero }}">
                        </div>
                        <div class="col-auto mt-3">
                            <label for=""><strong>Grupo Funcional</strong></label>
                            <select class="form-select rounded" style="line-height: 18px" name="idGrupo" id="idGrupo">
                                @foreach ($grupos as $grupo)
                                    <option {!! $grupo->IdGrupo == $monedero->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-warning">Guardar </button>
                </div>
            </form>
        </div>
    </div>
</div>
