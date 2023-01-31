<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

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
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancelación de Ticket</title>
</head>

<body>
    <h3 style="text-align: center">Se ha cancelado un nuevo ticket</h3>
    <table>
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Caja</th>
                <th>Ticket</th>
                <th>Importe</th>
                <th>FechaCancelación</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $solicitudCancelacion->Tienda->NomTienda }}</td>
                <td>{{ $solicitudCancelacion->Encabezado->NumCaja }}</td>
                <td>{{ $solicitudCancelacion->Encabezado->IdTicket }}</td>
                <td>$ {{ number_format($solicitudCancelacion->Encabezado->ImporteVenta, 2) }}</td>
                <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime(date('d-m-Y H:i:s'))) }}</td>
                <td>{{ $solicitudCancelacion->MotivoCancelacion }}</td>
            </tr>
        </tbody>
    </table>
    <h3 style="text-align: center">Detalle del ticket cancelado</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Articulo</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Iva</th>
                <th>Importe</th>
                <th>Paquete</th>
                <th>Pedido</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($solicitudCancelacion->Detalle as $detalle)
                <tr>
                    <td>{{ $detalle->CodArticulo }}</td>
                    <td>{{ $detalle->NomArticulo }}</td>
                    <td>{{ number_format($detalle->CantArticulo, 3) }}</td>
                    <td>{{ number_format($detalle->PrecioArticulo, 2) }}</td>
                    <td>{{ number_format($detalle->IvaArticulo, 2) }}</td>
                    <td>{{ number_format($detalle->ImporteArticulo, 2) }}</td>
                    <td>{{ $detalle->NomPaquete }}</td>
                    <td>{{ $detalle->Cliente }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <h2 style="text-align: center">Ticket Cancelado Por: {{ Auth::user()->NomUsuario }}</h2>
    </div>
</body>

</html>
