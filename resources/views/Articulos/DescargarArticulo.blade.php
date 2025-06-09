@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Descargar Artículo')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Descargar Artículo',
                    'options' => [['name' => 'Catálogo de Artículos', 'value' => '/CatArticulos']],
                ])
            </div>
        </div>
        <div class="row gap-4 gap-lg-0">
            <div class="col-12 col-lg-6">
                <div class="p-4 border-0 card" style="border-radius: 10px">

                    {{-- @include('components.table-search') --}}
                    <form id="form-buscar" action="{{ route('BuscarArticulo') }}"
                        class="gap-2 pb-2 d-flex align-items-center justify-content-end">
                        <div class="gap-2 d-flex align-items-center">
                            <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
                            <input class="rounded form-control" style="line-height: 18px" type="text" name="txtFiltro"
                                id="txtFiltro" value="{{ $txtFiltro }}" autofocus
                                placeholder="{{ isset($placeholder) ? $placeholder : '' }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-dark-outline">
                                @include('components.icons.search')
                            </button>
                        </div>
                    </form>
                    <div class="content-table content-table-full">
                        <table class="w-100">
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Código</th>
                                    <th style="width: 80%;">Nombre</th>
                                    <th class="rounded-end">Descargar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('components.table-empty', [
                                    'items' => $articulos,
                                    'colspan' => 14,
                                ])
                                @foreach ($articulos as $item)
                                    <tr>
                                        <td>{{ $item->ITEM_NUMBER }}</td>
                                        <td>{{ $item->DESCRIPTION }}</td>
                                        <td>
                                            <form action="{{ route('BuscarArticulo') }}" method="GET" class="search-form"
                                                data-id="{{ $item->ITEM_NUMBER }}">
                                                <input type="hidden" name="Item_number" value="{{ $item->ITEM_NUMBER }}">
                                                <button class="btn-table btn-table-outline" title="Descargar artículo"
                                                    style="color: #ffA500">
                                                    @include('components.icons.cloud-down')
                                                </button>
                                            </form>
                                            {{-- <button class="btn-table btn-table-outline" title="Descargar artículo"
                                        style="color: #ffA500" data-bs-toggle="modal" data-bs-target="#ModalDescargarArticulo">
                                        @include('components.icons.cloud-down')
                                    </button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('components.paginate', ['items' => $articulos])
                    </div>
                </div>
            </div>
            @if ($articulo)
                <div class="col-12 col-lg-6">
                    <div class="p-4 border-0 card" style="border-radius: 10px">
                        <form id="formArticulo" action="/LigarArticulo" method="POST">
                            @csrf
                            <div class="mb-4">
                                <!-- Sección de Nombre y Código -->
                                <div style="background-color: #e2e8f0; padding: 1rem; border-radius: 0.375rem;">
                                    <label class="form-label w-100 m-0 text-center"
                                        style="font-size: 1.1rem; font-weight: 600;">
                                        {{ $articulo->ITEM_NUMBER }} - {{ $articulo->DESCRIPTION }}
                                    </label>
                                </div>

                                <!-- Inputs ocultos -->
                                <input type="hidden" name="txtNomArticulo" id="txtNomArticulo"
                                    value="{{ $articulo->DESCRIPTION }}">
                                <input type="hidden" name="txtCodArticulo" id="txtCodArticulo"
                                    value="{{ $articulo->ITEM_NUMBER }}">
                            </div>

                            <!-- Amece Input -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="txtCodAmece" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Amece</label>
                                    <input type="text" id="txtCodAmece" name="txtCodAmece" maxlength="13"
                                        class="form-control" placeholder="Amece" required style="border-radius: 0.25rem;">
                                </div>
                            </div>

                            <!-- Datos del artículo -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="txtUOM" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Unidad de
                                        Medida</label>
                                    <select class="form-select" name="txtUOM" id="txtUOM"
                                        style="border-radius: 0.25rem;">
                                        <option value="KG">Kilogramo</option>
                                        <option value="LT">Litro</option>
                                        <option value="PZA">Pieza</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="txtPeso" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Peso</label>
                                    <input type="text" id="txtPeso" name="txtPeso" class="form-control"
                                        placeholder="Peso" required style="border-radius: 0.25rem;">
                                </div>
                                <div class="col-4">
                                    <label for="idTipoArticulo" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Tipo
                                        Artículo</label>
                                    <select class="form-select" name="idTipoArticulo" id="idTipoArticulo"
                                        style="border-radius: 0.25rem;">
                                        @foreach ($tiposArticulo as $tipoArticulo)
                                            <option value="{{ $tipoArticulo->IdTipoArticulo }}">
                                                {{ $tipoArticulo->NomTipoArticulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Precios y familia -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="txtPrecioRecorte" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Precio
                                        Recorte</label>
                                    <input type="number" id="txtPrecioRecorte" name="txtPrecioRecorte"
                                        class="form-control" placeholder="Precio Recorte" required
                                        style="border-radius: 0.25rem;">
                                </div>
                                <div class="col-4">
                                    <label for="txtFactor" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Factor</label>
                                    <input type="number" id="txtFactor" name="txtFactor" class="form-control"
                                        placeholder="Factor" required style="border-radius: 0.25rem;">
                                </div>
                                <div class="col-4">
                                    <label for="txtIdFamilia" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Familia</label>
                                    <select name="txtIdFamilia" id="txtIdFamilia" class="form-select"
                                        style="border-radius: 0.25rem;">
                                        @foreach ($familias as $familia)
                                            <option value="{{ $familia->IdFamilia }}">{{ $familia->NomFamilia }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Opciones de Tercero, Grupo, IVA -->
                            <div class="row mb-4">
                                <div class="col-4">
                                    <label for="txtTercero" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Tercero</label>
                                    <select name="txtTercero" id="txtTercero" class="form-select"
                                        style="border-radius: 0.25rem;">
                                        <option value="0">Sí</option>
                                        <option selected value="1">No</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="txtIdGrupo" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">Grupo</label>
                                    <select name="txtIdGrupo" id="txtIdGrupo" class="form-select"
                                        style="border-radius: 0.25rem;">
                                        @foreach ($grupos as $grupo)
                                            <option value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="txtIva" class="form-label"
                                        style="font-weight: 600; color: #475569; margin-bottom: .375rem">IVA</label>
                                    <select name="txtIva" id="txtIva" class="form-select"
                                        style="border-radius: 0.25rem;">
                                        <option value="0">Sí</option>
                                        <option selected value="1">No</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-end gap-4">
                                <button type="button" class="btn" onclick="ejecutarFormulario()"
                                    style="border-radius: 0.25rem; border: 1px solid black; color: black; background-color: white;">Cancelar</button>
                                <button type="submit" class="btn btn-warning"
                                    style="border-radius: 0.25rem;">Descargar</button>
                            </div>
                        </form>

                    </div>
                </div>
            @else
                <div class="col-12 col-lg-6">
                    <div class="p-4 border-0 card d-flex align-items-center"
                        style="border-radius: 10px; min-height: 400px; justify-content: center">
                        <span style="color: grey;">@include('components.icons.cloud-down-lg')</span>
                        <h2 class="text-center" style="color: grey; user-select: none;">Sin artículo</h2>
                        <span class="text-center" style="color: grey; user-select: none;">
                            No se ha seleccionado ningún artículo para descargar
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- @include('Articulos.ModalDescargarArticulo') --}}

    <script>
        document.addEventListener('submit', e => {
            if (e.target.matches('.search-form')) {
                const form = document.querySelector(`.search-form[data-id='${e.target.dataset.id}']`);
                const url = location.href;
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const entries = urlParams.entries();

                for (const entry of entries) {
                    if (entry[0] != 'Item_number') {
                        let input = document.createElement('input');
                        input.type = "hidden";
                        input.name = entry[0];
                        input.value = entry[1];
                        form.appendChild(input);
                    }
                }

                form.setAttribute('action', url);
                form.submit();
            }
        })

        function ejecutarFormulario() {
            document.getElementById('form-buscar').submit();
        }
    </script>
@endsection
