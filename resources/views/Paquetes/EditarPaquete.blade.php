@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Editar Paquete')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => $nomPaquete,
                    'options' => [['name' => 'CATÁLOGO DE PAQUETES', 'value' => '/VerPaquetes']],
                ])
                <div>
                    <a href="/VerPaquetes" class="btn btn-sm btn-dark" title="Agregar Usuario">
                        Ver paquetes @include('components.icons.list')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="row mb-2">
                <div class="container">
                    <form id="formPaquete" action="/EditarPaqueteExistente/{{ $idPaquete }}" method="POST">
                        @csrf
                        <div id="contenedorPaquete" class="container">

                        </div>
                        <input type="hidden" id="importePaquete" name="importePaquete" value="{{ $importePaquete }}">
                    </form>
                </div>
                <div class="col-auto">
                    <label class="form-label text-secondary mb-0" style="font-weight: 500">Código de artículo</label>
                    <input class="form-control" type="text" name="codArticulo" id="codArticulo"
                        placeholder="Código del articulo" autofocus>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <h4 class="nomArticulo"></h4>
                    <h4 hidden class="nomArticuloValid"></h4>
                </div>
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
                <tbody>
                    @foreach ($paquete as $paqDetalle)
                        <tr style="vertical-align: middle">
                            <td>{{ $paqDetalle->CodArticulo }}</td>
                            <td>{{ $paqDetalle->NomArticulo }} - ${{ number_format($paqDetalle->PrecioLista, 2) }}</td>
                            <td>
                                <input class="form-control form-control-sm" type="number" name="cantArticulo[]"
                                    id="cantArticulo" value="{{ number_format($paqDetalle->CantArticulo, 2) }}" required>
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="number" name="precioArticulo[]"
                                    id="precioArticulo" value="{{ number_format($paqDetalle->PrecioArticulo, 2) }}"
                                    required>
                            </td>
                            <td>${{ $paqDetalle->ImporteArticulo }}</td>
                            <td>
                                <button class="btn-table btn-table-delete btnEliminarArticulo">
                                    @include('components.icons.delete')</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Costo del Paquete : </th>
                    <th><span class="totalPaquete">${{ number_format($importePaquete, 2) }}</span></th>
                    <th></th>
                </tfoot>
            </table>
            <div class="d-flex justify-content-end">
                <div class="col-auto">
                    <button id="btnConfirmar" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarGuardar"> Guardar </button>
                </div>
            </div>
        </div>
    </div>

    @include('Paquetes.ModalConfirmarGuardar')

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
                    document.querySelector('tbody').insertRow(-1).innerHTML = `<tr>
                            <td>${codArticulo.value} </td>
                            <td>${nomArticulo.textContent} </td>
                            <td><input class="form-control form-control-sm" type="number" name="cantArticulo[]" id="cantArticulo" placeholder="Cantidad" required></td>
                            <td><input class="form-control form-control-sm" type="number" name="precioArticulo[]" id="precioArticulo" placeholder="Precio" required></td>
                            <td></td>
                            <td>
                                <button class="btn-table btn-table-delete btnEliminarArticulo">
                                    @php echo view('components.icons.delete')->render(); @endphp
                                </button>
                            </td>
                        </tr>`;
                    codArticulo.value = '';
                    nomArticulo.textContent = '';
                }
            }
        });

        $(document).on('click', '.btnEliminarArticulo', function() {
            $(event.target).closest('tr').remove();

            if (document.getElementById('tblArticulos').rows.length == 2) {
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
        });

        $(document).on('click', '#btnEditarPaquete', function() {
            var enviar = 0;
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
                var $cantidad = $fila.eq(2).find('input[type="number"]').val();
                var $precio = $fila.eq(3).find('input[type="number"]').val();

                if ($cantidad == '' || $precio == '' || $cantidad == 0 || $precio == 0) {
                    enviar = enviar + 1;
                }
            });
            if (enviar == 0) {
                document.getElementById('formPaquete').submit();
            }
        });
    </script>
@endsection
