@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Paquetes')
<style>
    td {
        font-size: 16px;
    }
</style>
@section('contenido')
    <div class="d-flex justify-content-center mb-2">
        <div class="col-auto">
            <h3 class="card shadow p-1">Catálogo de Paquetes</h3>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <div class="row">
            <div class="container">
                <form id="formPaquete" action="/GuardarPaquete" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center mb-3">
                        <div class="col-8">
                            <input class="form-control" type="text" name="nomPaquete" id="nomPaquete"
                                placeholder="Nombre de Paquete" required>
                        </div>
                    </div>
                    <div id="contenedorPaquete" class="container">

                    </div>
                    <input type="hidden" id="importePaquete" name="importePaquete">
                </form>
            </div>
            <div class="col-auto">
                <input class="form-control" type="text" name="codArticulo" id="codArticulo" placeholder="Código">
            </div>
            <div class="col-auto">
                <h4 class="nomArticulo mt-0"></h4>
                <h4 hidden class="nomArticuloValid"></h4>
            </div>
            <div class="input-group flex-nowrap col d-flex justify-content-end">
                <span class="input-group-text bg-white">Ver Paquetes</span>
                <a href="/VerPaquetes" class="btn card shadow">
                    <span class="material-icons">library_books</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container">
        <table id="tblArticulos" class="table table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Importe</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th>Costo del Paquete : </th>
                <th><span class="totalPaquete"></span></th>
                <th></th>
            </tfoot>
        </table>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <button id="btnGenerarObject" class="btn btn-warning">
                    <i class="fa fa-save"></i> Generar Paquete
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('codArticulo').addEventListener('input', function(e) {
            fetch('/BuscarCodArticuloPaquqete?codArticulo=' + e.target.value)
                .then(res => res.text())
                .then(respuesta => {
                    if (respuesta != '') {
                        document.querySelector('.nomArticulo').innerHTML = respuesta
                        document.querySelector('.nomArticuloValid').innerHTML = respuesta
                    } else {
                        if (document.getElementById('codArticulo').value == '') {
                            document.querySelector('.nomArticulo').innerHTML =
                                '';
                        } else {
                            document.querySelector('.nomArticulo').innerHTML =
                                'Buscando Articulo ... <i class="fa fa-clock-o"></i>';
                            document.querySelector('.nomArticuloValid').innerHTML = '';
                        }
                    }
                });
        });

        document.getElementById('codArticulo').addEventListener('keypress', (e) => {
            const codArticulo = document.getElementById('codArticulo');
            const nomArticulo = document.querySelector('.nomArticulo');
            const nomArticuloValid = document.querySelector('.nomArticuloValid');

            if (e.key == 'Enter') {
                if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent !=
                    '') {
                    document.querySelector('tbody').insertRow(-1).innerHTML = '<tr>' +
                        '<td>' + codArticulo.value + '</td>' +
                        '<td>' + nomArticulo.textContent + '</td>' +
                        '<td><input class="form-control form-control-sm" type="number" name="cantArticulo[]" id="cantArticulo" placeholder="Cantidad" required></td>' +
                        '<td><input class="form-control form-control-sm" type="number" name="precioArticulo[]" id="precioArticulo" placeholder="Precio" required></td>' +
                        '<td></td>' +
                        '<td><button class="btn btnEliminarArticulo"><span style="color: red" class="material-icons">delete_forever</span></button></td>' +
                        '</tr>';
                    codArticulo.value = '';
                    nomArticulo.textContent = '';
                }
            }
        })

        $(document).on('click', '.btnEliminarArticulo', function() {
            $(event.target).closest('tr').remove();

            if(document.getElementById('tblArticulos').rows.length == 2){
                document.querySelector('.totalPaquete').textContent = '';
            }
            $importe = 0;
            $('#tblArticulos tr:has(td)').map(function(i, v) {
                var $fila = $('td', this);
                $cantidad = $fila.eq(2).find('input[type="number"]').val();
                $precio = $fila.eq(3).find('input[type="number"]').val();

                $($fila.eq(4)).html("$" + ($precio * $cantidad).toFixed(2));

                $importe = $importe + parseFloat(($precio * $cantidad).toFixed(2));
                document.querySelector('.totalPaquete').textContent = '$' + $importe.toFixed(2);

                document.getElementById('importePaquete').value = $importe.toFixed(2);
            });

        });

        $(document).on('click', '#btnGenerarObject', function() {
            if (document.getElementById('nomPaquete').value != '') {

                var hijos = $(document.getElementById('contenedorPaquete')).find('input').length;
                if (hijos > 0) {
                    $(document.getElementById('contenedorPaquete')).find('input').remove();
                }

                var tbl = $('#tblArticulos tr:has(td)').map(function(i, v) {
                    var $td = $('td', this);

                    var cArticulo = document.getElementById('contenedorPaquete').appendChild(document
                        .createElement('input'));
                    cArticulo.name = 'CodArticulo[]';
                    cArticulo.setAttribute("hidden", "true");
                    cArticulo.value = $td.eq(0).text();

                    var nArticulo = document.getElementById('contenedorPaquete').appendChild(document
                        .createElement('input'));
                    nArticulo.name = 'CantArticulo[]';
                    nArticulo.setAttribute("hidden", "true");
                    nArticulo.value = $td.eq(2).find('input[type="number"]').val();

                    var pArticulo = document.getElementById('contenedorPaquete').appendChild(document
                        .createElement('input'));
                    pArticulo.name = 'PrecioArticulo[]';
                    pArticulo.setAttribute("hidden", "true");
                    pArticulo.value = $td.eq(3).find('input[type="number"]').val();
                });

                $('#tblArticulos tr:has(td)').map(function(i, v) {
                    var $fila = $('td', this);
                    $cantidad = $fila.eq(2).find('input[type="number"]').val();
                    $precio = $fila.eq(3).find('input[type="number"]').val();

                    if ($cantidad != '' && $precio != '') {
                        document.getElementById('formPaquete').submit();
                    }
                });
            } else {
                document.getElementById('nomPaquete').focus();
            }
        })

        $(document).on('input', 'input[type="number"]', function() {
            $importe = 0;
            $('#tblArticulos tr:has(td)').map(function(i, v) {
                var $fila = $('td', this);
                $cantidad = $fila.eq(2).find('input[type="number"]').val();
                $precio = $fila.eq(3).find('input[type="number"]').val();

                $($fila.eq(4)).html("$" + ($precio * $cantidad).toFixed(2));

                $importe = $importe + parseFloat(($precio * $cantidad).toFixed(2));
                document.querySelector('.totalPaquete').textContent = '$' + $importe.toFixed(2);

                document.getElementById('importePaquete').value = $importe.toFixed(2);
            });
        })
    </script>
@endsection
