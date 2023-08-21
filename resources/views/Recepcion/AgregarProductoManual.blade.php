<!DOCTYPE html>
<html lang="en">

<head>
    <!--<base target="_parent" />-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Icons/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">
    <title>Agregar Producto Manual</title>
    <style>
        button:active {
            transform: scale(1.2);
        }
    </style>
</head>

<body class="bg-white">
    <div class="content-table content-table-full">
        <table class="w-100">
            <thead class="table-head">
                <tr>
                    <th class="rounded-start">Código</th>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th class="rounded-end">Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                @if ($articuloPendiente == 0)
                    <tr>
                        <th colspan="4"><i style="color: red" class="fa fa-exclamation-triangle"></i> Articulo
                            Pendiente Por Recepcionar, Vaya a Recepciones Pendientes!</th>
                    </tr>
                @else
                    @if ($articulos->count() == 0)
                        <tr>
                            <td colspan="4">No se Encontro el Articulo!</td>
                        </tr>
                    @else
                        @foreach ($articulos as $articulo)
                            <form action="/CapturaManualTmp" target="ifrGuardarTmp">
                                <input type="hidden" name="codArticulo" value="{{ $articulo->CodArticulo }}">
                                <tr>
                                    <td>{{ $articulo->CodArticulo }}</td>
                                    <td>{{ $articulo->NomArticulo }}</td>
                                    <td class="d-flex">
                                        <input type="number" min="0.1" step="any"
                                            class="form-control form-control-sm" name="cantArticulo" id="cantArticulo"
                                            required>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm">
                                            <span class="material-icons">add_circle</span>
                                        </button>
                                    </td>
                                </tr>
                            </form>
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
