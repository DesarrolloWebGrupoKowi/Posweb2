@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Transacción de Producto')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Transacción de Producto'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="line-height: 18px">Origen</span>
                        <span class="input-group-text bg-white" style="line-height: 18px">{{ $nomTienda }}</span>
                    </div>
                </div>
                <div class="col-auto">
                    <form id="formTransaccion" action="/GuardarTransaccion" method="POST">
                        @csrf
                        <div id="contenedorTransaccion" class="container">
                            <div class="input-group mb-3">
                                <span class="input-group-text" style="line-height: 18px">Destino</span>
                                @if ($tiendas->count() == 0)
                                    <span class="input-group-text bg-danger text-white" style="line-height: 18px">No Hay
                                        Tiendas Destino
                                        Agregadas</span>
                                @else
                                    <select class="form-select rounded" style="line-height: 18px" name="idTiendaDestino"
                                        id="idTiendaDestino">
                                        @foreach ($tiendas as $tienda)
                                            <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3">
                    <div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="line-height: 18px">Código Articulo</span>
                            <input class="form-control rounded" style="line-height: 18px" id="codArticulo"
                                name="codArticulo" type="text" placeholder="Escribe">
                            <span id="nomArticulo" class="input-group-text bg-white" style="line-height: 18px">...</span>
                        </div>
                    </div>
                    <div>
                        <button id="btnAgregar" hidden class="btn btn-sm btn-warning shadow">
                            <i class="fa fa-check"></i> Agregar
                        </button>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex justify-content-end me-3">
                        <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                            Articulos: (<span id="countArticulos">0</span>)
                        </p>
                    </div>
                </div>
            </div>

            <table id="tblTransaccion">
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th class="rounded-end">Eliminar</th>
                    </tr>
                </thead>
                <tbody> </tbody>
            </table>

            <input type="hidden" id="tiendaSeleccionada" name="tiendaSeleccionada">
            <div class="container">
                <div class="d-flex justify-content-end">
                    <div class="col-auto">
                        <button hidden type="button" id="btnTransaccionarProducto" class="btn btn-warning shadow">
                            <i class="fa fa-save"></i> Transaccionar Producto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('TransaccionProducto.ModalConfirmarTransaccion')
        @include('TransaccionProducto.ModalSinCantidadArticulos')
        @include('TransaccionProducto.ModalArticuloRepetido')
    </div>

    <script>
        document.getElementById('codArticulo').addEventListener('input', function(e) {
            fetch('/BuscarArticuloTransaccion?codArticulo=' + e.target.value)
                .then(res => res.text())
                .then(respuesta => {
                    if (respuesta == ' - ') {
                        document.querySelector('#nomArticulo').innerHTML = '<i class="fa fa-clock-o"></i>';
                        document.getElementById('btnAgregar').hidden = true;
                    }
                    if (respuesta == 1) {
                        document.querySelector('#nomArticulo').innerHTML = 'Articulo Sin Inventario!';
                        document.getElementById('btnAgregar').hidden = true;
                    }
                    if (respuesta != 1 && respuesta != ' - ') {
                        document.querySelector('#nomArticulo').innerHTML = respuesta;
                        document.getElementById('btnAgregar').hidden = false;
                    }
                });
        });

        document.getElementById('btnAgregar').addEventListener('click', (e) => {
            var contCodigosRepetidos = 0;
            $('#tblTransaccion tr:has(td)').map(function(i, v) {
                var $fila = $('td', this);
                $codArticulo = $fila.eq(0).text();

                document.getElementById('codArticulo').value == $codArticulo ? contCodigosRepetidos =
                    contCodigosRepetidos + 1 : '';
            });

            if (contCodigosRepetidos > 0) {
                $('#ModalArticuloRpetido').modal('show');
            } else {
                document.querySelector('tbody').insertRow(-1).innerHTML = '<tr>' +
                    '<td>' + document.getElementById('codArticulo').value + '</td>' +
                    '<td>' + document.querySelector('#nomArticulo').textContent + '</td>' +
                    '<td><input class="form-control form-control-sm" type="number" name="cantArticulo[]" id="cantArticulo" placeholder="Cantidad" required></td>' +
                    // '<td><button class="btn btnEliminarArticulo"><span style="color: red" class="material-icons">delete_forever</span></button></td>' +
                    `<td><button class="btn-table btnEliminarArticulo" title="Eliminar rostizado"> @include("components.icons.delete") </button></td>`+
                    '</tr>';
                codArticulo.value = '';
                document.querySelector('#nomArticulo').innerHTML = '...';
                document.getElementById('btnAgregar').hidden = true;
                document.getElementById('countArticulos').textContent = document.getElementById('tblTransaccion')
                    .rows
                    .length - 1;
                if (document.getElementById('tblTransaccion').rows.length > 1) {
                    document.getElementById('btnTransaccionarProducto').hidden = false;
                }
            }
        });

        $(document).on('click', '.btnEliminarArticulo', function() {
            $(event.target).closest('tr').remove();
            document.getElementById('countArticulos').textContent = document.getElementById('tblTransaccion').rows
                .length - 1
            if (document.getElementById('tblTransaccion').rows.length == 1) {
                document.getElementById('btnTransaccionarProducto').hidden = true;
            }
        });

        document.getElementById('btnTransaccionarProducto').addEventListener('click', (e) => {
            var hijos = $(document.getElementById('contenedorTransaccion')).find('input').length;
            if (hijos > 0) {
                $(document.getElementById('contenedorTransaccion')).find('input').remove();
            }

            var aux = 0;

            var tbl = $('#tblTransaccion tr:has(td)').map(function(i, v) {
                var $td = $('td', this);

                var cArticulo = document.getElementById('contenedorTransaccion').appendChild(document
                    .createElement('input'));
                cArticulo.name = 'CodArticulo[' + $td.eq(0).text() + ']';
                cArticulo.setAttribute("hidden", "true");
                cArticulo.value = $td.eq(2).find('input[type="number"]').val();

                /*var nArticulo = document.getElementById('contenedorTransaccion').appendChild(document
                    .createElement('input'));
                nArticulo.name = 'CantArticulo[]';
                nArticulo.setAttribute("hidden", "true");
                nArticulo.value = $td.eq(2).find('input[type="number"]').val();
                */
                $('#tblTransaccion tr:has(td)').map(function(i, v) {
                    var $fila = $('td', this);
                    $cantidad = $fila.eq(2).find('input[type="number"]').val();

                    $cantidad == '' || $cantidad == 0 || $cantidad < 0 ? aux = aux + 1 : '';
                });
            });

            aux == 0 ? $('#ModalConfirmarTransaccion').modal('show') : $('#ModalSinCantidadArticulos').modal(
                'show');
        })

        document.getElementById('tiendaSeleccionada').value = document.getElementById('idTiendaDestino').options[document
            .getElementById('idTiendaDestino').selectedIndex].text;
        document.getElementById('destino').textContent = document.getElementById('tiendaSeleccionada').value;
        document.getElementById('idTiendaDestino').addEventListener('change', (e) => {
            document.getElementById('tiendaSeleccionada').value = document.getElementById('idTiendaDestino')
                .options[document.getElementById('idTiendaDestino').selectedIndex].text;
            document.getElementById('destino').textContent = document.getElementById('tiendaSeleccionada').value;
        })
    </script>
@endsection
