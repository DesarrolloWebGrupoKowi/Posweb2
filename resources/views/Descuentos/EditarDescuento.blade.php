@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Editar Descuento')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => $descuento->NomDescuento,
                    'options' => [['name' => 'CATÁLOGO DE DESCUENTOS', 'value' => '/VerDescuentos']],
                ])
                <div>
                    <a href="/VerDescuentos" class="btn btn-sm btn-dark" title="Ver descuentos">
                        Ver descuentos @include('components.icons.list')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="w-auto card border-0 p-4" style="border-radius: 10px">
            <form action="/GuardarDescuento" method="POST">
                <input type="hidden" name="IdEncDescuento" value="{{ $descuento->IdEncDescuento }}">
                @csrf
                <div class="d-flex flex-wrap gap-2 gap-md-3">
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Nombre del descuento</label>
                        <input class="form-control rounded" style="line-height: 18px" type="text" name="nomDescuento"
                            id="nomDescuento" placeholder="Nombre de descuento" required
                            value="{{ $descuento->NomDescuento }}">
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Tipo descuento</label>
                        <select class="form-select rounded" style="line-height: 18px" name="tipoDescuento"
                            id="tipoDescuento" required value="{{ old('tipoDescuento') }}">
                            <option value="">Seleccione tipo descuento</option>
                            @foreach ($tiposdescuentos as $td)
                                <option value="{{ $td->IdTipoDescuento }}"
                                    {{ $td->IdTipoDescuento == $descuento->TipoDescuento ? 'selected' : '' }}>
                                    {{ $td->NomTipoDescuento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Tiendas</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda"
                            value="{{ old('idTienda') }}">
                            <option value="">Seleccione una tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option value="{{ $tienda->IdTienda }}"
                                    {{ $tienda->IdTienda == $descuento->IdTienda ? 'selected' : '' }}>
                                    {{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Plazas</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idPlaza" id="idPlaza"
                            value="{{ old('idPlaza') }}">
                            <option value="">Seleccione una plaza</option>
                            @foreach ($plazas as $plaza)
                                <option value="{{ $plaza->IdPlaza }}"
                                    {{ $plaza->IdPlaza == $descuento->IdPlaza ? 'selected' : '' }}>
                                    {{ $plaza->NomPlaza }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Fecha inicio</label>
                        <input class="form-control rounded" style="line-height: 18px" type="date" name="fechaInicio"
                            id="fechaInicio"required value="{{ $descuento->FechaInicio }}"
                            {{ count($detalle) != 0 ? 'disabled' : '' }}>
                        @if (count($detalle) != 0)
                            <input type="hidden" name="fechaInicio" id="fechaInicio"
                                value="{{ $descuento->FechaInicio }}">
                        @endif
                    </div>
                    <div style="flex: 1; width: 25%; min-width: 290px;">
                        <label class="text-secondary" style="font-weight:500">Fecha fin</label>
                        <input class="form-control rounded" style="line-height: 18px" type="date" name="fechaFin"
                            id="fechaFin" required value="{{ $descuento->FechaFin }}"
                            {{ count($detalle) != 0 ? 'disabled' : '' }}>
                        @if (count($detalle) != 0)
                            <input type="hidden" name="fechaFin" id="fechaFin" value="{{ $descuento->FechaFin }}">
                        @endif
                    </div>
                </div>
                <div class="d-flex mt-4 justify-content-end">
                    <button id="btnGenerarObject" class="btn btn-warning">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="row mb-2">
                <div class="container">
                    <form id="formPaquete" action="/EditarDescuentoExistente/{{ $descuento->IdEncDescuento }}"
                        method="POST">
                        @csrf
                        <div id="contenedorPaquete" class="container">

                        </div>
                    </form>
                </div>
                <div class="col-auto">
                    <label class="text-secondary" style="font-weight:500">Codigo de articulo</label>
                    <input class="form-control rounded" style="line-height: 18px" type="text" name="codArticulo"
                        id="codArticulo" placeholder="Código del articulo" autofocus>
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
                                <button class="btn-table btn-table-delete btnEliminarArticulo" title="Eliminar articulo">
                                    @include('components.icons.delete')
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
                    document.querySelector('tbody').insertRow(-1).innerHTML = `<tr>
                            <td> ${codArticulo.value}</td>
                            <td> ${nomArticulo.textContent}</td>
                            <td> ${select}</td>
                            <td><input class="form-control form-control-sm" type="number" name="precioArticulo[]" id="precioArticulo" placeholder="Precio" required></td>
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
