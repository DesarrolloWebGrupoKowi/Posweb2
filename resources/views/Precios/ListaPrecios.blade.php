@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Módulo de Precios')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="currency-dollar"
            title="Módulo de Precios"
            subtitle="Consulta de precios por artículo"
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
                    action="/DetallePrecios"
                    method="get"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-search me-1"></i>Buscar artículo
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Código, nombre, PLU..."
                                value="{{ $txtFiltro }}"
                                autofocus
                            >
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
                                    href="/DetallePrecios"
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
                            ></i>Listado de Precios
                        </h5>
                        <p class="section-content-subtitle">Consulta de precios actualizados por artículo</p>
                    </div>
                    <a
                        href="/ExportExcelDetallePrecios"
                        class="btn-header-ghost"
                        title="Exportar precios"
                        style="background: #f1f5f9; color: #475569;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                    >
                        <i class="bi bi-file-earmark-excel"></i> Exportar
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-upc me-1"></i>PLU</th>
                                <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                <th><i class="bi bi-box me-1"></i>Nombre Artículo</th>
                                <th><i class="bi bi-cash me-1"></i>Menudeo</th>
                                <th><i class="bi bi-cash-stack me-1"></i>Minorista</th>
                                <th><i class="bi bi-cash-coin me-1"></i>Detalle</th>
                                <th><i class="bi bi-people me-1"></i>Empleados y Socios</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($precios as $precio)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $precio->CodEtiqueta }}</td>
                                    <td style="font-weight: 500;">{{ $precio->CodArticulo }}</td>
                                    <td>{{ $precio->NomArticulo }}</td>
                                    <td style="font-weight: 500;">${{ number_format($precio->Menudeo, 2) }}</td>
                                    <td style="font-weight: 500;">${{ number_format($precio->Minorista, 2) }}</td>
                                    <td style="font-weight: 500;">${{ number_format($precio->Detalle, 2) }}</td>
                                    <td style="font-weight: 500;">${{ number_format($precio->EmpySoc, 2) }}</td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="7"
                                    title="Sin datos disponibles"
                                    message="No se encontraron precios con los filtros seleccionados"
                                    icon="currency-dollar"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/DetallePrecios"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $precios])
            </div>
        </x-card-gradient-header>
    </div>
@endsection
