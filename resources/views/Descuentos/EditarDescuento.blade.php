@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Editar Descuento')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', [
                'titulo' => $descuento->NomDescuento,
                'options' => [['name' => 'Descuentos', 'value' => '/VerDescuentos']],
            ])
            <div>
                <a href="/VerDescuentos" class="btn btn-sm btn-dark" title="Ver descuentos">
                    <i class="fa fa-eye"></i> Ver descuentos
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <form class="d-flex flex-wrap align-items-top justify-content-around gap-2" action="/GuardarDescuento"
                method="POST">
                <input type="hidden" name="IdEncDescuento" value="{{ $descuento->IdEncDescuento }}">
                @csrf
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Nombre del descuento</label>
                    <input class="form-control" type="text" name="nomDescuento" id="nomDescuento"
                        placeholder="Nombre de descuento" required value="{{ $descuento->NomDescuento }}" autofocus>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Tipo descuento</label>
                    <select class="form-select" name="tipoDescuento" id="tipoDescuento" required
                        value="{{ old('tipoDescuento') }}">
                        <option value="">Seleccione tipo descuento</option>
                        @foreach ($tiposdescuentos as $td)
                            <option value="{{ $td->IdTipoDescuento }}"
                                {{ $td->IdTipoDescuento == $descuento->TipoDescuento ? 'selected' : '' }}>
                                {{ $td->NomTipoDescuento }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Tiendas</label>
                    <select class="form-select" name="idTienda" id="idTienda" value="{{ old('idTienda') }}">
                        <option value="">Seleccione una tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->IdTienda }}"
                                {{ $tienda->IdTienda == $descuento->IdTienda ? 'selected' : '' }}>
                                {{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Plazas</label>
                    <select class="form-select" name="idPlaza" id="idPlaza" value="{{ old('idPlaza') }}">
                        <option value="">Seleccione una plaza</option>
                        @foreach ($plazas as $plaza)
                            <option value="{{ $plaza->IdPlaza }}"
                                {{ $plaza->IdPlaza == $descuento->IdPlaza ? 'selected' : '' }}>
                                {{ $plaza->NomPlaza }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Fecha inicio</label>
                    <input class="form-control" type="date" name="fechaInicio" id="fechaInicio"required
                        value="{{ $descuento->FechaInicio }}" {{ count($detalle) != 0 ? 'disabled' : '' }}>
                    @if (count($detalle) != 0)
                        <input type="hidden" name="fechaInicio" id="fechaInicio" value="{{ $descuento->FechaInicio }}">
                    @endif
                </div>
                <div class="col-12 col-sm-5 form-group">
                    <label class="form-label fw-bold text-secondary">Fecha fin</label>
                    <input class="form-control" type="date" name="fechaFin" id="fechaFin" required
                        value="{{ $descuento->FechaFin }}" {{ count($detalle) != 0 ? 'disabled' : '' }}>
                    @if (count($detalle) != 0)
                        <input type="hidden" name="fechaFin" id="fechaFin" value="{{ $descuento->FechaFin }}">
                    @endif
                </div>
                <div class="col-11 d-flex justify-content-end">
                    <button id="btnGenerarObject" class="btn btn-warning">
                        <i class="fa fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-4 content-table content-table-full card p-4" style="border-radius: 20px">
            <div class="row mb-4">
                <div class="container">
                    <form id="formPaquete" action="/EditarDescuentoExistente/{{ $descuento->IdEncDescuento }}"
                        method="POST">
                        @csrf
                        <div id="contenedorPaquete" class="container">

                        </div>
                    </form>
                </div>
                <div class="col-auto">
                    <label class="form-label fw-bold text-secondary">Codigo de articulo</label>
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
                        <th>Lista de precio</th>
                        <th>Precio</th>
                        <th class="rounded-end">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalle as $item)
                        <tr style="vertical-align: middle">
                            <td>{{ $item->CodArticulo }}</td>
                            <td>{{ $item->NomArticulo }}</td>
                            <td>
                                <select class="form-control form-control-sm" name="listaPrecios[]" id="listaPrecios"
                                    required>
                                    @foreach ($ListaPrecio as $lista)
                                        <option value="{{ $lista->IdListaPrecio }}"
                                            {{ $lista->IdListaPrecio == $item->IdListaPrecio ? 'selected' : '' }}>
                                            {{ $lista->NomListaPrecio }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="number" name="precioArticulo[]"
                                    id="precioArticulo" value="{{ number_format($item->PrecioDescuento, 2) }}" required>
                            </td>
                            <td>
                                <button class="btn btnEliminarArticulo">
                                    <span style="color: red" class="material-icons">delete_forever</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-4">
                <div class="col-auto">
                    <button id="btnConfirmar" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarGuardar">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="ulListaPrecios" class="d-none">
        <select class="form-control form-control-sm" name="listaPrecios[]" id="listaPrecios" required>
            @foreach ($ListaPrecio as $lista)
                <option value="{{ $lista->IdListaPrecio }}">
                    {{ $lista->NomListaPrecio }}
                </option>
            @endforeach
        </select>
    </div>

    @include('Descuentos.ModalArticuloRepetido')
    @include('Descuentos.ModalConfirmarGuardar')
    @include('Descuentos.ModalCantidadPrecioCero')

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
                let repeat = 0;
                var tbl = $('#tblArticulos tr:has(td)').map(function(i, v) {
                    var $td = $('td', this);
                    let cod = $td.eq(0).text();
                    repeat = $td.eq(0).text() == codArticulo.value ? ++repeat : repeat;
                })

                if (repeat != 0) {
                    $('#ModalArticuloRpetido').modal('show');
                    return;
                }

                if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent !=
                    '') {
                    let select = document.querySelector('#ulListaPrecios').innerHTML;
                    document.querySelector('tbody').insertRow(-1).innerHTML = '<tr>' +
                        '<td>' + codArticulo.value + '</td>' +
                        '<td>' + nomArticulo.textContent + '</td>' +
                        '<td>' + select + '</td>' +
                        '<td><input class="form-control form-control-sm" type="number" name="precioArticulo[]" id="precioArticulo" placeholder="Precio" required></td>' +
                        '<td><button class="btn btnEliminarArticulo"><span style="color: red" class="material-icons">delete_forever</span></button></td>' +
                        '</tr>';
                    codArticulo.value = '';
                    nomArticulo.textContent = '';
                }
            }
        });

        $(document).on('click', '.btnEliminarArticulo', function() {
            $(event.target).closest('tr').remove();
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

                let sArticulo = document.getElementById('contenedorPaquete').appendChild(document
                    .createElement('input'));
                sArticulo.name = 'listaPrecios[]';
                sArticulo.setAttribute("hidden", "true");
                sArticulo.value = $td.eq(2).find('select').val();

                let pArticulo = document.getElementById('contenedorPaquete').appendChild(document
                    .createElement('input'));
                pArticulo.name = 'PrecioArticulo[]';
                pArticulo.setAttribute("hidden", "true");
                pArticulo.value = $td.eq(3).find('input[type="number"]').val();

                if (pArticulo.value == 0)
                    $('#ModalCantidadPrecioCero').modal('show');
            });

            $('#tblArticulos tr:has(td)').map(function(i, v) {
                var $fila = $('td', this);
                var $cantidad = $fila.eq(2).find('select').val();
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
