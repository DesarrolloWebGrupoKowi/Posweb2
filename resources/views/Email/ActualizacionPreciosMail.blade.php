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
    <title>Actualización de Precios</title>
</head>
<body>
    <h3 style="text-align: center">Nueva Actualización de Precios</h3>
    <table>
        <thead>
            <tr>
                <td>Lista de Precios</td>
                <th>Código</th>
                <th>Articulo</th>
                <th>Precio Anterior</th>
                <th>Precio Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($preciosActualizados as $pActualizado)
                <tr>
                    <td>{{ $pActualizado->NomListaPrecio }}</td>
                    <td>{{ $pActualizado->CodArticulo }}</td>
                    <td>{{ $pActualizado->NomArticulo }}</td>
                    <td>{{ $pActualizado->PrecioArticuloViejo }}</td>
                    <td>{{ $pActualizado->PrecioArticuloNuevo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <h2 style="text-align: center">Actualización de Precios Por: {{ Auth::user()->NomUsuario }}</h2>
    </div>
</body>
</html>