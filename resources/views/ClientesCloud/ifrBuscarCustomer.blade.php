<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_parent"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Customer Cloud</title>
</head>
<body>
    <form action="/GuardarCustomerCloud">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Id Cliente</th>
                    <th>Nombre</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                @if (count($customers)  == 0)
                    <tr>
                        <td colspan="3">No Hay Coincidencias!</td>
                    </tr>
                @else
                @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->ID_CLIENTE }}</td>
                    <td>{{ $customer->NOMBRE }}</td>
                    <td><input class="form-check-input" type="checkbox" name="chkCustomer[]" value="{{ $customer->ID_CLIENTE }}"></td>
                </tr>
            @endforeach
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <button class="btn btn-sm btn-warning">
                <i class="fa fa-save"></i> Guardar
            </button>
        </div>
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>