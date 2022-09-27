<!-- Modal Editar Articulo-->
<div class="modal fade" id="ModalEditar-{{$articulo->CodArticulo}}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarArticulo/{{$articulo->CodArticulo}}" method="POST">
                    @csrf
                        <div class="d-flex justify-content-center">
                            <div class="row card-header">
                                <div class="col-auto">
                                    <label>Nombre: </label>
                                    <strong>{{$articulo->NomArticulo}}</strong>
                                </div>
                                <div class="col-auto">
                                    <label>Código: </label>
                                    <strong>{{$articulo->CodArticulo}}</strong>
                                </div>
                            </div>
                        </div>
                    <div class="col-auto mb-3">
                        <label for="txtCodAmece">Amece</label>
                        <input type="text" id="txtCodAmece" name="txtCodAmece" class="form-control"
                            placeholder="Amece" value="{{$articulo->Amece}}" maxlength="13">
                    </div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="txtUOM">Unidad de Medida</label>
                            <select class="form-select" name="txtUOM" id="txtUOM">
                                <option {!! $articulo->UOM == 'KG' ? 'selected' : '' !!} value="KG">Kilogramo</option>
                                <option {!! $articulo->UOM == 'LT' ? 'selected' : '' !!} value="LT">Litro</option>
                                <option {!! $articulo->UOM == 'PZA' ? 'selected' : '' !!} value="PZA">Pieza</option>
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="txtPeso">Peso</label>
                            <input type="text" id="txtPeso" name="txtPeso" class="form-control" placeholder="Peso"
                               value="{{$articulo->Peso}}">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="txtTercero">Tercero</label>
                            <select name="txtTercero" id="txtTercero" class="form-select">
                                <option {!! $articulo->Tercero == 0 ? 'selected' : '' !!} value="0">Si</option>
                                <option {!! $articulo->Tercero == 1 ? 'selected' : '' !!} value="1">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="txtPrecioRecorte">Precio Recorte</label>
                            <input type="number" id="txtPrecioRecorte" name="txtPrecioRecorte" class="form-control"
                                placeholder="Precio Recorte" value="{{$articulo->PrecioRecorte}}">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="txtFactor">Factor</label>
                            <input type="number" id="txtFactor" name="txtFactor" class="form-control"
                                placeholder="Factor" value="{{$articulo->Factor}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="txtIdFamilia">Familia</label>
                            <select name="txtIdFamilia" id="txtIdFamilia" class="form-select">
                                @foreach ($familias as $familia)
                                    <option {!! $articulo->IdFamilia == $familia->IdFamilia ? 'selected' : '' !!} value="{{$familia->IdFamilia}}">{{$familia->NomFamilia}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="txtIdGrupo">Grupo</label>
                            <select name="txtIdGrupo" id="txtIdGrupo" class="form-select">
                                @foreach ($grupos as $grupo)
                                    <option {!! $articulo->IdGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{$grupo->IdGrupo}}">{{$grupo->NomGrupo}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="txtIva">Iva</label>
                            <select name="txtIva" id="txtIva" class="form-select">
                                <option {!! $articulo->Iva == 0 ? 'selected' : '' !!} value="0">Si</option>
                                <option {!! $articulo->Iva == 1 ? 'selected' : '' !!} value="1">No</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-sm btn-warning">
                    <i class="fa fa-edit"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
