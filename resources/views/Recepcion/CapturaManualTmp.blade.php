<!DOCTYPE html>
<html lang="en">
<style>
    button:active{
        transform: scale(1.2);
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Icons/font-awesome.min.css">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">
    <title>Captura Manual Tmp</title>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-start">
            <h5>Productos a Recepcionar Manual</h5>
        </div>
        <table class="table table-responsive table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($articulosManual->count() == 0)
                    <tr>
                        <td colspan="4">Agregar Producto Manual!</td>
                    </tr>
                @else
                    @foreach ($articulosManual as $aManual)
                        <form action="/EliminarProductoManual/{{ $aManual->IdCapRecepcionManual }}" method="POST">
                            @csrf
                            <tr>
                                <td>{{ $aManual->CodArticulo }}</td>
                                <td>{{ $aManual->NomArticulo }}</td>
                                <td>{{ $aManual->CantArticulo }}</td>
                                <td>
                                    <button class="btn btn-sm">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </td>
                            </tr>
                        </form>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
