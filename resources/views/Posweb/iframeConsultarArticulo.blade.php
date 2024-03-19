<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Icons/font-awesome.min.css"> --}}
    <title>Consultar Articulo</title>
    <style>
        * {
            font-family: sans-serif;
        }

        h6 {
            font-size: 20px;
            padding: 4px;
            margin: 0;
            color: white;
        }

        .bg-danger {
            color: red;
        }

        table {
            margin-bottom: 8px;
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            line-height: 18px;
        }

        tr:hover {
            background: #33415521;
        }
    </style>
</head>

<body>
    <div class="container">
        @if ($articulos->count() == 0)
            <h6 style="text-align: center" class="card-header bg-danger text-white"><i
                    class="fa fa-exclamation-triangle"></i> No se Encontro el Articulo</h6>
        @else
            <div class="row d-flex justify-content-center">
                @foreach ($articulos as $articulo)
                    <div class="col-6" style="border-radius: 10px">
                        <h6 class="card-header text-white d-flex justify-content-center" style="background: #334155">
                            {{ $articulo->CodArticulo }} - {{ $articulo->NomArticulo }} - {{ $articulo->CodEtiqueta }}
                        </h6>
                        <table class="table table-striped table-responsive table-sm">
                            <tbody>
                                @foreach ($articulo->PrecioArticulo as $precioArticulo)
                                    <tr>
                                        <td>{{ $precioArticulo->NomListaPrecio }}</td>
                                        <th>$ {{ number_format($precioArticulo->PivotPrecio->PrecioArticulo, 2) }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
