<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }

        td {
            border: 1px solid black;
        }

        thead {
            text-align: left;
            background-color: #000000;
            color: white;
        }

        .Stock {
            color: red;
            font-weight: bold;
        }

        .contenido {
            padding: 10px;
            margin-bottom: 5px;
            background-color: white;
            border-radius: 5px;
            color: black;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nueva Recepción</title>
</head>
<body>
    <h3 style="text-align: center">La tienda: {{ $nomTienda }} realizó una nueva recepción</h3>

    <div>
        <h4>Información de la Recepción</h4>
        <table>
            <thead>
                <tr>
                    <th>Id Recepción</th>
                    <th>Fecha Llegada</th>
                    <th>Fecha Recepción</th>
                    <th>PackingList</th>
                    <th>Recepcionó</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $recepcion->IdCapRecepcion }}</td>
                    <td>{{ $recepcion->FechaLlegada }}</td>
                    <td>{{ $recepcion->FechaRecepcion }}</td>
                    <td>{{ $recepcion->PackingList }}</td>
                    <td>{{ Auth::user()->NomUsuario }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h4>Detalle de la recepción</h4>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Articulo</th>
                    <th>Cant. Enviada</th>
                    <th>Cant. Recepcionada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recepcion->DetalleRecepcion as $dRecepcion)
                    <tr>
                        <td>{{ $dRecepcion->CodArticulo }}</td>
                        <td>{{ $dRecepcion->NomArticulo }}</td>
                        <td>{{ number_format($dRecepcion->CantEnviada, 2) }}</td>
                        <td>{{ number_format($dRecepcion->CantRecepcionada, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>