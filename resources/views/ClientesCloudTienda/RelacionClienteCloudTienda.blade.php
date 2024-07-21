<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">
    <title>Relacion Cliente Cloud</title>
</head>

<body>
    <form action="/GuardarRelacionClienteCloud" target="ifrGuardarRelacionClienteCloud">

        <div class="content-table content-table-full card border-0 p-4 mb-4" style="border-radius: 10px">
            <h5>Direcciones de Envío</h5>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nombre</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th class="rounded-end" style="text-align: center">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customersShipTo as $customer)
                        <tr>
                            <td>{{ $customer->NOMBRE }}</td>
                            <td>{{ $customer->CALLE }}</td>
                            <td>{{ $customer->CIUDAD }}</td>
                            <td style="text-align: center"><input class="form-check-input" type="checkbox"
                                    name="chkShipTo[]" id="chkShipToClienteCloud" value="{{ $customer->SHIP_TO }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <h5>Direcciones de Facturación</h5>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nombre</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th class="rounded-end" style="text-align: center">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customersBillTo as $customer)
                        <tr>
                            <td>{{ $customer->NOMBRE }}</td>
                            <td>{{ $customer->CALLE }}</td>
                            <td>{{ $customer->CIUDAD }}</td>
                            <td style="text-align: center"><input class="form-check-input" type="checkbox"
                                    name="chkBillTo[]" id="chkBillToClienteCloud" value="{{ $customer->BILL_TO }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mb-3">
            <button id="btnGuardar" class="btn btn-warning">
                <i class="fa fa-arrow-circle-right"></i> Enviar
            </button>
        </div>
        <input type="hidden" name="idTienda" value="{{ $idTienda }}">
        <input type="hidden" name="idCliente" value="{{ $idCliente }}">
    </form>


    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<script>
    const listaChecks = document.querySelectorAll("#chkClienteCloud");
</script>
