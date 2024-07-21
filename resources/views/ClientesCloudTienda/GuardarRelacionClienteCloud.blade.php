<!DOCTYPE html>
<html lang="es">

<head>
    <base target="_parent" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Guardar Relacion Cliente Cloud</title>
</head>

<body>
    <h5 class="d-flex justify-content-center bg-success text-white">Guardar Relacion Cliente Cloud</h5>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center mb-3">
            <div class="col-4">
                <h6 style="text-align: center" class="card-header">{{ $tienda->NomTienda }}</h6>
            </div>
        </div>
        <form action="GuardarDatClienteCloud" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-3 mb-3">
                    <label for="">Id Cliente Cloud</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="idClienteCloud"
                        value="{{ $idClienteCloud }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Ciudad</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="ciudad"
                        value="{{ $customer->CIUDAD }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Dirección</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="direccion"
                        value="{{ $customer->CALLE }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Pais</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="pais"
                        value="{{ $customer->PAIS }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Código Postal</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="codigoPostal"
                        value="{{ $customer->CODIGO_POSTAL }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Locacion</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="locacion"
                        value="{{ $customer->LOCACION }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Tipo de Cliente</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="tipoCliente"
                        value="{{ $customer->TIPO_CLIENTE }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Dirección de Envio</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="shipTo"
                        value="{{ $customer->SHIP_TO }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Dirección de Facturación</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="billTo"
                        value="{{ $customer->BILL_TO }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Código de Envio</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="codigoEnvio"
                        value="{{ $customer->CODIGO_ENVIO }}" readonly required>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Lista de Precio</label>
                    <select class="form-select" name="idListaPrecio" id="idListaPrecio">
                        @foreach ($listasPrecio as $listaPrecio)
                            <option value="{{ $listaPrecio->IdListaPrecio }}">{{ $listaPrecio->NomListaPrecio }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Tipo de Pago</label>
                    <select class="form-select" name="idTipoPago" id="idTipoPago">
                        @foreach ($tiposPago as $tipoPago)
                            <option value="{{ $tipoPago->IdTipoPago }}">{{ $tipoPago->NomTipoPago }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3">
                    <label for="">Tipo de Nómina</label>
                    <select class="form-select" name="idTipoNomina" id="idTipoNomina">
                        <option value="">Null</option>
                        <option value="4">QUINCENAL</option>
                        <option value="3">SEMANAL</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>
    </div>
    <input type="hidden" name="idTienda" value="{{ $idTienda }}">
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
