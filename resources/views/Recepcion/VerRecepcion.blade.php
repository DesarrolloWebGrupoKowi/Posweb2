<!DOCTYPE html>
<html lang="en">

<head>
    <base target="_parent" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Icons/font-awesome.min.css">
    <title>Ver Detalle Recepción</title>
</head>

<body>
    <form action="/RecepcionarProducto/{{ $idRecepcion }}" method="POST">
        @csrf
        <div>
            <table class="table table-sm table-responsive table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Origen</th>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Confirmar</th>
                        <th style="text-align: center">Recepcionar <input checked type="checkbox"
                                class="form-check-input mt-1" name="chkTodos" id="chkTodos"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($detalleRecepcion))
                        <tr>
                            <td colspan="6">Productos a Recepcionar</td>
                        </tr>
                    @else
                        @foreach ($detalleRecepcion as $dRecepcion)
                            <tr>
                                <td>{{ $dRecepcion->PackingList }}</td>
                                <td>{{ $dRecepcion->CodArticulo }}</td>
                                <td>{{ $dRecepcion->NomArticulo }}</td>
                                <td>{{ number_format($dRecepcion->CantEnviada, 2) }}</td>
                                <td>
                                    <input style="width: 15vh" type="text" class="form-control form-control-sm"
                                        name="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                        value="{{ $dRecepcion->CantEnviada }}">
                                </td>
                                <td style="text-align: center">
                                    <input checked type="checkbox" class="form-check-input"
                                        name="chkArticulo[{{ $dRecepcion->CodArticulo }}]" id="chkArticulo" value="{{ $dRecepcion->PackingList }}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th style="text-align: center">Total:</th>
                        <th>{{ number_format($totalCantidad, 2) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if (!empty($detalleRecepcion))
            <div class="d-flex justify-content-end mb-3">
                <div class="col-auto me-4">
                    <button class="btn btn-warning">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        @endif
    </form>

    <script>
        const chkArticulos = document.querySelectorAll('#chkArticulo');
        const chkTodos = document.getElementById('chkTodos');

        chkTodos.addEventListener('click', (e) => {
            if (chkTodos.checked == true) {
                chkArticulos.forEach(element => {
                    element.checked = true;
                });
            } else {
                chkArticulos.forEach(element => {
                    element.checked = false;
                });
            }
        });
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
