@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Listas de Precio')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="tags"
            title="Catálogo de Listas de Precio"
            subtitle="Gestión de listas de precio del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <div
                class="border-bottom p-4"
                style="border-color: #f1f5f9 !important;"
            >
                <form
                    method="GET"
                    action="/CatListasPrecio"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-search me-1"></i>Buscar lista de precio
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Nombre de lista..."
                                value="{{ request('txtFiltro') }}"
                                autofocus
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-percent me-1"></i>IVA
                            </label>
                            <select
                                name="Iva"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todos</option>
                                <option
                                    value="0"
                                    {{ request('Iva') == '0' ? 'selected' : '' }}
                                >0%</option>
                                <option
                                    value="8"
                                    {{ request('Iva') == '8' ? 'selected' : '' }}
                                >8%</option>
                                <option
                                    value="16"
                                    {{ request('Iva') == '16' ? 'selected' : '' }}
                                >16%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-weight me-1"></i>Rango de peso
                            </label>
                            <select
                                name="rangoPeso"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todos</option>
                                <option
                                    value="0-10"
                                    {{ request('rangoPeso') == '0-10' ? 'selected' : '' }}
                                >0 - 10 kg</option>
                                <option
                                    value="10-50"
                                    {{ request('rangoPeso') == '10-50' ? 'selected' : '' }}
                                >10 - 50 kg</option>
                                <option
                                    value="50-100"
                                    {{ request('rangoPeso') == '50-100' ? 'selected' : '' }}
                                >50 - 100 kg</option>
                                <option
                                    value="100+"
                                    {{ request('rangoPeso') == '100+' ? 'selected' : '' }}
                                >Más de 100 kg</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-sm d-flex align-items-center flex-grow-1 gap-2"
                                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="bi bi-funnel"></i> Filtrar
                                </button>
                                <a
                                    href="/CatListasPrecio"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Listas de Precio
                        </h5>
                        <p class="section-content-subtitle">Listado de listas de precio registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="bi bi-plus-circle"></i> Agregar lista de precio
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>Id</th>
                                <th><i class="bi bi-tag me-1"></i>Nombre</th>
                                <th><i class="bi bi-weight me-1"></i>Peso Mínimo</th>
                                <th><i class="bi bi-weight me-1"></i>Peso Máximo</th>
                                <th><i class="bi bi-percent me-1"></i>IVA</th>
                                <th><i class="bi bi-gear me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listasPrecio as $listaPrecio)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $listaPrecio->IdListaPrecio }}</td>
                                    <td style="font-weight: 500;">{{ $listaPrecio->NomListaPrecio }}</td>
                                    <td>{{ $listaPrecio->PesoMinimo }} kg</td>
                                    <td>{{ $listaPrecio->PesoMaximo }} kg</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $listaPrecio->PorcentajeIva }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$listaPrecio->IdListaPrecio"
                                                modal="ModalEditar"
                                                title="Editar lista de precio"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('ListasPrecio.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="6"
                                    title="Sin datos disponibles"
                                    message="No se encontraron listas de precio con los filtros seleccionados"
                                    icon="tags"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatListasPrecio"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $listasPrecio])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Lista de Precio -->
    @include('ListasPrecio.ModalAgregar')

    <script src="js/ListasPrecioScript.js"></script>
@endsection
