<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
    <form id="formArticulo" action="/LigarArticulo" method="POST">
        @csrf
        <div class="container">
            <div class="mb-3">
                @foreach ($articulo_XXKW_ITEMS as $articulo)
                    <div class="row card-header">
                        <div class="col-auto">
                            <label>Nombre: </label>
                            <strong>{{$articulo->DESCRIPTION}}</strong>
                        </div>
                        <div class="col-auto">
                            <label>Código: </label>
                            <strong>{{$articulo->ITEM_NUMBER}}</strong>
                        </div>
                    </div>
                    <input type="hidden" name="txtNomArticulo" id="txtNomArticulo" value="{{$articulo->DESCRIPTION}}">
                    <input type="hidden" name="txtCodArticulo" id="txtCodArticulo" value="{{$articulo->ITEM_NUMBER}}">
                @endforeach
            </div>
            <div class="row">
                <div class="col-auto mb-3">
                    <label for="txtCodAmece">Amece</label>
                    <input type="text" id="txtCodAmece" name="txtCodAmece" maxlength="13" class="form-control" placeholder="Amece" required>
                </div>
            </div>
            <div class="row">
                <div class="col-4 mb-3">
                    <label for="txtUOM">Unidad de Medida</label>
                    <select class="form-select" name="txtUOM" id="txtUOM">
                        <option value="KG">Kilogramo</option>
                        <option value="LT">Litro</option>
                        <option value="PZA">Pieza</option>
                    </select>
                </div>
                <div class="col-4 mb-3">
                    <label for="txtPeso">Peso</label>
                    <input type="text" id="txtPeso" name="txtPeso" class="form-control" placeholder="Peso" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="txtTercero">Tercero</label>
                    <select name="txtTercero" id="txtTercero" class="form-select">
                        <option value="0">Si</option>
                        <option selected value="1">No</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-4 mb-3">
                    <label for="txtPrecioRecorte">Precio Recorte</label>
                    <input type="number" id="txtPrecioRecorte" name="txtPrecioRecorte" class="form-control" placeholder="Precio Recorte" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="txtFactor">Factor</label>
                    <input type="number" id="txtFactor" name="txtFactor" class="form-control" placeholder="Factor" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="txtIdFamilia">Familia</label>
                    <select name="txtIdFamilia" id="txtIdFamilia" class="form-select">
                        @foreach ($familias as $familia)
                            <option value="{{$familia->IdFamilia}}">{{$familia->NomFamilia}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-4 mb-3">
                    <label for="txtIdGrupo">Grupo</label>
                    <select name="txtIdGrupo" id="txtIdGrupo" class="form-select">
                        @foreach ($grupos as $grupo)
                            <option value="{{$grupo->IdGrupo}}">{{$grupo->NomGrupo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 mb-3">
                    <label for="txtIva">Iva</label>
                    <select name="txtIva" id="txtIva" class="form-select">
                        <option value="0">Si</option>
                        <option selected value="1">No</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>