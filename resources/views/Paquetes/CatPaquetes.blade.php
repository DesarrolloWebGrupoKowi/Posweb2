@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Paquetes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', [
                'titulo' => 'Catálogo de Paquetes',
                'options' => [['name' => 'Paquetes', 'value' => '/VerPaquetes']],
            ])
            <div>
                <a href="/VerPaquetes" class="btn btn-sm btn-dark" title="Agregar Usuario">
                    <i class="fa fa-eye"></i> Ver paquetes
                </a>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>


        <div class="content-table content-table-full card p-4 mt-4" style="border-radius: 20px">
            <div class="d-flex align-items-center justify-content-between pb-2 gap-4">
                <form class="input-group"style="max-width: 600px" id="formPaquete" action="/GuardarPaquete" method="POST">
                    @csrf
                    <label class="form-label fw-bold text-secondary">Nombre del paquete</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="nomPaquete" id="nomPaquete"
                            placeholder="Nombre de Paquete" required>
                    </div>
                    <div id="contenedorPaquete" class="container">
                    </div>
                    <input type="hidden" id="importePaquete" name="importePaquete">
                </form>
                <div>
                    <label class="form-label fw-bold text-secondary">Codigo de articulo</label>
                    <input class="form-control" type="text" name="codArticulo" id="codArticulo" placeholder="Código">
                </div>
            </div>

            <div class="text-center pb-4">
                <h4 class="nomArticulo mt-0"></h4>
                <h4 hidden class="nomArticuloValid"></h4>
            </div>

            <table id="tblArticulos">
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th class="rounded-end">Eliminar</th>
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
            <div>
                <div class="d-flex justify-content-end">
                    <button hidden id="btnGenerarObject" class="btn btn-warning">
                        <i class="fa fa-save"></i> Generar Paquete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('Paquetes.ModalArticuloRepetido')
    @include('Paquetes.ModalConfirmarCreacionPaquete')
    @include('Paquetes.ModalPaqueteSinNombre')
    @include('Paquetes.ModalCantidadPrecioCero')

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
                var contCodigosRepetidos = 0;
                $('#tblArticulos tr:has(td)').map(function(i, v) {
                    var $fila = $('td', this);
                    $codArticulo = $fila.eq(0).text();

                    document.getElementById('codArticulo').value == $codArticulo ? contCodigosRepetidos =
                        contCodigosRepetidos + 1 : '';
                });

                if (contCodigosRepetidos > 0) {
                    $('#ModalArticuloRpetido').modal('show');
                } else {
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
                        document.getElementById('btnGenerarObject').hidden = false;
                    }
                }
            }
        })

        $(document).on('click', '.btnEliminarArticulo', function() {
            $(event.target).closest('tr').remove();

            if (document.getElementById('tblArticulos').rows.length == 2) {
                document.querySelector('.totalPaquete').textContent = '';
                document.getElementById('btnGenerarObject').hidden = true;
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
                $('#ModalConfirmarCreacionPaquete').modal('show');
                document.getElementById('btnCrearPaquete').addEventListener('click', (e) => {
                    var hijos = $(document.getElementById('contenedorPaquete')).find('input').length;
                    if (hijos > 0) {
                        $(document.getElementById('contenedorPaquete')).find('input').remove();
                    }

                    var tbl = $('#tblArticulos tr:has(td)').map(function(i, v) {
                        var $td = $('td', this);

                        var cArticulo = document.getElementById('contenedorPaquete').appendChild(
                            document
                            .createElement('input'));
                        cArticulo.name = 'CodArticulo[]';
                        cArticulo.setAttribute("hidden", "true");
                        cArticulo.value = $td.eq(0).text();

                        var nArticulo = document.getElementById('contenedorPaquete').appendChild(
                            document
                            .createElement('input'));
                        nArticulo.name = 'CantArticulo[]';
                        nArticulo.setAttribute("hidden", "true");
                        nArticulo.value = $td.eq(2).find('input[type="number"]').val();

                        var pArticulo = document.getElementById('contenedorPaquete').appendChild(
                            document
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
                        } else {
                            $('#ModalCantidadPrecioCero').modal('show');
                        }
                    });
                })
            } else {
                $('#ModalPaqueteSinNombre').modal('show');
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
