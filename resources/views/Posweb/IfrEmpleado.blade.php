<!DOCTYPE html>
<html lang="en">

<head>
    <base target="_parent" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}"> --}}
    <title>Empleado</title>
    <style>
        * {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-head {
            background: #1e293b;
            color: white;

        }

        tr th,
        tr td {
            text-align: left;
            line-height: 32px;
        }
    </style>
</head>

<body class="bg-transparent">
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    @if (!empty($frecuenteSocio))
        <div style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Cliente</th>
                        <th>Folio</th>
                        <th>Nombre</th>
                        <th class="rounded-end"><i class="fa fa-check"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{ $frecuenteSocio->TipoCliente->NomTipoCliente }}</th>
                        <td>{{ $frecuenteSocio->FolioViejo }}</td>
                        <td>{{ $frecuenteSocio->Nombre }}</td>
                        <form action="CobroFrecuenteSocio/{{ $frecuenteSocio->FolioViejo }}" method="POST">
                            @csrf
                            <td>
                                <button class="col-auto btn btn-sm btn-warning mt-0">
                                    <i class="fa fa-check-circle"></i> Elegir
                                </button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="container">
            @if ($banVentasDiarias == 1)
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <h5 class="bg-danger text-white">
                            <i class="fa fa-exclamation-triangle"></i> Máximo de Ventas
                            Diarias
                            Alcanzado <i class="fa fa-exclamation-triangle"></i>
                        </h5>
                    </div>
                </div>
            @endif
        </div>
        <div class="content-table content-table-full card border-0" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nómina</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Límite</th>
                        <th>Crédito Disponible</th>
                        <th class="rounded-end"><i class="fa fa-check"></i></th>
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
                                        {{-- @if ($saldoEmpleado > 0 && $banVentasDiarias == 0) --}}
                                        @if ($banVentasDiarias == 0)
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
    @endif

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
