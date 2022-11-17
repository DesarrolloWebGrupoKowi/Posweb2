<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: rgb(212, 244, 247);
            padding: 10px;
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
    <title>Alerta Bajo Stock</title>
</head>

<body>
    <div class="contenido">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Articulo</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articulosBajoInventario as $articulo)
                    <tr>
                        <td>{{ $articulo->CodArticulo }}</td>
                        <td>{{ $articulo->NomArticulo }}</td>
                        <td class="Stock">{{ $articulo->StockArticulo }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
