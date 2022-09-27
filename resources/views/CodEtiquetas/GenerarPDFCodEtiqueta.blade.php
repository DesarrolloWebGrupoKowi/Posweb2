<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/logokowi.png">
    <title>Listado de Códigos de Etiquetas</title>
</head>
<style>
    * {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        text-align: left;
        padding: 6px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2
    }

    th {
        background-color: #04AA6D;
        color: white;
    }
</style>

<body>
    <h2 style="text-align: center">{{ $titulo }}</h2>
    <h3 style="text-align: center">{{ $nomTienda }}</h3>
    <h5>{{ $fecha }}</h5>
    </div>
    <div>
        <table style="text-align: center">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Codigo Etiqueta</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($codEtiquetas as $listaCodEtiqueta)
                <tr>
                    <td>{{ $listaCodEtiqueta->CodArticulo }}</td>
                    <td>{{ $listaCodEtiqueta->NomArticulo }}</td>
                    <td>{{ $listaCodEtiqueta->CodEtiqueta }}</td>
                    <td>{{ $listaCodEtiqueta->PrecioArticulo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>