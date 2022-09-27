<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enviar Articulo</title>
</head>
<style>
    .btn:focus {
    outline: none;
    box-shadow: none;
}
</style>
<body>
    <div class="container-fluid">
        <div class="mb-1 d-flex justify-content-center">
            <strong>Articulos Encontrados: ({{count($xxkw_items)}})</strong>
        </div>
        <form method="POST" target="iframeMostrar">
            @csrf
            <table class="table table-responsive table-sm table-striped">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($xxkw_items) == 0)
                        <tr>
                            <td colspan="3">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($xxkw_items as $item)
                        <tr>
                            <td>{{$item->ITEM_NUMBER}}</td>
                            <td>{{$item->DESCRIPTION}}</td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="AgregarArticulo/{{$item->ITEM_NUMBER}}">
                                    <span style="color: orange;" class="material-icons">cloud_download</span>
                                </button>
                            </td>
                        </tr>
                        @endforeach 
                    @endif
                </tbody>
            </table>
        </form>
    </div>
        
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>