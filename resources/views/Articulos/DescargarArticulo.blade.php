@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Descargar Artículo')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="cloud-download"
            title="Descargar Artículo"
            subtitle="Búsqueda y descarga de artículos del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
                <a
                    href="/CatArticulos"
                    class="btn-header-ghost"
                    title="Catálogo de artículos"
                    style="background: #f1f5f9; color: #475569;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i class="fa fa-list"></i> Catálogo de Artículos
                </a>
            </x-slot:buttons>

            <div class="row g-4 p-4">
                {{-- Columna Izquierda: Buscador y Tabla --}}
                <div class="col-12 col-lg-6">
                    <div
                        class="card border-0 p-4"
                        style="border-radius: 10px"
                    >
                        {{-- Buscador --}}
                        <form
                            id="form-buscar"
                            action="{{ route('BuscarArticulo') }}"
                            method="GET"
                            class="mb-3"
                        >
                            <div class="row g-2 align-items-end">
                                <div class="col-md-9">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: #475569; font-size: 0.85rem;"
                                    >
                                        <i class="fa fa-search me-1"></i>Buscar artículo
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                        name="txtFiltro"
                                        id="txtFiltro"
                                        value="{{ $txtFiltro }}"
                                        autofocus
                                        placeholder="Código o nombre del artículo..."
                                    >
                                </div>
                                <div class="col-md-3">
                                    <button
                                        type="submit"
                                        class="btn btn-sm d-flex align-items-center w-100 gap-2"
                                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                                    >
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Tabla de resultados --}}
                        <div class="table-responsive">
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-barcode me-1"></i>Código</th>
                                        <th><i class="fa fa-font me-1"></i>Nombre</th>
                                        <th class="text-center"><i class="fa fa-cloud-download me-1"></i>Descargar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($articulos as $item)
                                        <tr>
                                            <td style="font-weight: 600; color: #0f172a;">{{ $item->ITEM_NUMBER }}</td>
                                            <td>{{ $item->DESCRIPTION }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('BuscarArticulo') }}"
                                                    method="GET"
                                                    class="search-form"
                                                    data-id="{{ $item->ITEM_NUMBER }}"
                                                >
                                                    <input
                                                        type="hidden"
                                                        name="Item_number"
                                                        value="{{ $item->ITEM_NUMBER }}"
                                                    >
                                                    <button
                                                        class="btn btn-sm d-flex align-items-center mx-auto gap-1"
                                                        title="Descargar artículo"
                                                        style="background: #fffbeb; color: #f59e0b; border: none; border-radius: 8px; padding: 6px 12px; font-size: 0.8rem; transition: all 0.3s ease;"
                                                        onmouseover="this.style.background='#fef3c7'; this.style.transform='translateY(-1px)'"
                                                        onmouseout="this.style.background='#fffbeb'; this.style.transform='translateY(0)'"
                                                    >
                                                        <i class="fa fa-cloud-download"></i> Descargar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <x-table-empty-data
                                            colspan="3"
                                            title="Sin resultados"
                                            message="No se encontraron artículos con el filtro ingresado"
                                            icon="search"
                                            :action="false"
                                        />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @include('components.paginate', ['items' => $articulos])
                    </div>
                </div>

                {{-- Columna Derecha: Formulario o Estado vacío --}}
                <div class="col-12 col-lg-6">
                    @if ($articulo)
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px"
                        >
                            <h5 class="section-content-title mb-3">
                                <i
                                    class="fa fa-edit me-2"
                                    style="color: #64748b;"
                                ></i>Datos del Artículo
                            </h5>

                            <form
                                id="formArticulo"
                                action="/LigarArticulo"
                                method="POST"
                            >
                                @csrf

                                {{-- Nombre y Código --}}
                                <div
                                    class="mb-4 rounded p-3"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; text-align: center;"
                                >
                                    <span style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                        {{ $articulo->ITEM_NUMBER }} - {{ $articulo->DESCRIPTION }}
                                    </span>
                                </div>

                                <input
                                    type="hidden"
                                    name="txtNomArticulo"
                                    value="{{ $articulo->DESCRIPTION }}"
                                >
                                <input
                                    type="hidden"
                                    name="txtCodArticulo"
                                    value="{{ $articulo->ITEM_NUMBER }}"
                                >

                                {{-- Amece --}}
                                <div class="mb-3">
                                    <label
                                        for="txtCodAmece"
                                        class="form-label fw-medium mb-2"
                                        style="color: #475569; font-size: 0.85rem;"
                                    >
                                        Amece <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="txtCodAmece"
                                        name="txtCodAmece"
                                        maxlength="13"
                                        class="form-control"
                                        style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        placeholder="Amece"
                                        required
                                    >
                                </div>

                                {{-- UOM, Peso, Tipo Artículo --}}
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtUOM"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Unidad de Medida
                                        </label>
                                        <select
                                            class="form-select"
                                            name="txtUOM"
                                            id="txtUOM"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            <option value="KG">Kilogramo</option>
                                            <option value="LT">Litro</option>
                                            <option value="PZA">Pieza</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtPeso"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Peso <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="txtPeso"
                                            name="txtPeso"
                                            class="form-control"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            placeholder="Peso"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            for="idTipoArticulo"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Tipo Artículo
                                        </label>
                                        <select
                                            class="form-select"
                                            name="idTipoArticulo"
                                            id="idTipoArticulo"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            @foreach ($tiposArticulo as $tipoArticulo)
                                                <option value="{{ $tipoArticulo->IdTipoArticulo }}">
                                                    {{ $tipoArticulo->NomTipoArticulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Precio Recorte, Factor, Familia --}}
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtPrecioRecorte"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Precio Recorte <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="txtPrecioRecorte"
                                            name="txtPrecioRecorte"
                                            class="form-control"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            placeholder="Precio Recorte"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtFactor"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Factor <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="txtFactor"
                                            name="txtFactor"
                                            class="form-control"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            placeholder="Factor"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            for="txtIdFamilia"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Familia
                                        </label>
                                        <select
                                            name="txtIdFamilia"
                                            id="txtIdFamilia"
                                            class="form-select"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            @foreach ($familias as $familia)
                                                <option value="{{ $familia->IdFamilia }}">{{ $familia->NomFamilia }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Tercero, Grupo, IVA --}}
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtTercero"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Tercero
                                        </label>
                                        <select
                                            name="txtTercero"
                                            id="txtTercero"
                                            class="form-select"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            <option value="0">Sí</option>
                                            <option
                                                selected
                                                value="1"
                                            >No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-md-0 mb-3">
                                        <label
                                            for="txtIdGrupo"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Grupo
                                        </label>
                                        <select
                                            name="txtIdGrupo"
                                            id="txtIdGrupo"
                                            class="form-select"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            for="txtIva"
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            IVA
                                        </label>
                                        <select
                                            name="txtIva"
                                            id="txtIva"
                                            class="form-select"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                        >
                                            <option value="0">Sí</option>
                                            <option
                                                selected
                                                value="1"
                                            >No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Botones --}}
                                <div class="d-flex justify-content-end gap-2">
                                    <button
                                        type="button"
                                        class="btn d-flex align-items-center gap-1"
                                        onclick="ejecutarFormulario()"
                                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                                        onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                                    >
                                        <i class="fa fa-times"></i> Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        class="btn d-flex align-items-center gap-1"
                                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.3)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                                    >
                                        <i class="fa fa-cloud-download"></i> Descargar
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div
                            class="card d-flex align-items-center justify-content-center border-0 p-4"
                            style="border-radius: 10px; min-height: 400px;"
                        >
                            <div class="text-center">
                                <div class="mb-3">
                                    <i
                                        class="fa fa-cloud-download"
                                        style="font-size: 4rem; color: #94a3b8;"
                                    ></i>
                                </div>
                                <h5 style="color: #64748b; font-weight: 600;">Sin artículo</h5>
                                <p style="color: #94a3b8; font-size: 0.85rem;">
                                    No se ha seleccionado ningún artículo para descargar
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

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
