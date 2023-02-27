<!DOCTYPE html>
<html lang="en">

<head>
    <base target="_parent" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <title>Empleado</title>
</head>

<body>
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    <div class="container">
        @if ($banVentasDiarias == 1)
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h5 class="bg-danger text-white"><i class="fa fa-exclamation-triangle"></i> Máximo de Ventas Diarias
                        Alcanzado <i class="fa fa-exclamation-triangle"></i></h5>
                </div>
            </div>
        @endif
    </div>
    <div class="table-responsive">
        <table style="font-size: 16px" class="table table-responsive table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nómina</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Límite</th>
                    <th>Crédito Disponible</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($statusEmpleado == 1)
                    <tr>
                        <th style="font-size: 20px; color: red" colspan="6">¡El empleado esta dado de baja!</th>
                    </tr>
                @else
                    @if (empty($empleado))
                        <tr>
                            <td colspan="6">No se Encontro el Empleado!</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $empleado->NumNomina }}</td>
                            <td>{{ $empleado->Nombre }}</td>
                            <td>{{ $empleado->Apellidos }}</td>
                            <td>${{ number_format($empleado->LimiteCredito->Limite, 2) }}</td>
                            <td style="color: {!! $saldoEmpleado == 0 ? 'red; font-weight: bold;' : '' !!}">${{ number_format($saldoEmpleado, 2) }}</td>
                            <form action="/CobroEmpleado" method="GET">
                                <input type="hidden" name="numNomina" value="{{ $empleado->NumNomina }}">
                                <td>
                                    @if ($saldoEmpleado > 0 && $banVentasDiarias == 0)
                                        <button class="col-auto btn btn-sm btn-warning mt-0">
                                            <i class="fa fa-check-circle"></i> Elegir
                                        </button>
                                    @endif
                                </td>
                            </form>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
