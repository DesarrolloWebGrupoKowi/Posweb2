@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Menú Posweb')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="menu-button-wide"
            title="Catálogo de Menús"
            subtitle="Gestión de menús del sistema Posweb"
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
                    action="/CatMenuPosweb"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-search me-1"></i>Buscar menú
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Nombre, link, icono..."
                                value="{{ request('txtFiltro') }}"
                                autofocus
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-tag me-1"></i>Tipo de Menú
                            </label>
                            <select
                                name="IdTipoMenu"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todos</option>
                                @foreach ($tipoMenus ?? [] as $tipo)
                                    <option
                                        value="{{ $tipo->IdTipoMenu }}"
                                        {{ request('IdTipoMenu') == $tipo->IdTipoMenu ? 'selected' : '' }}
                                    >
                                        {{ $tipo->NomTipoMenu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-paint-brush me-1"></i>Color de Fondo
                            </label>
                            <select
                                name="bgColor"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todos</option>
                                <option
                                    value="bg-orange"
                                    {{ request('bgColor') == 'bg-orange' ? 'selected' : '' }}
                                >Orange</option>
                                <option
                                    value="bg-red"
                                    {{ request('bgColor') == 'bg-red' ? 'selected' : '' }}
                                >Red</option>
                                <option
                                    value="bg-green"
                                    {{ request('bgColor') == 'bg-green' ? 'selected' : '' }}
                                >Green</option>
                                <option
                                    value="bg-blue"
                                    {{ request('bgColor') == 'bg-blue' ? 'selected' : '' }}
                                >Blue</option>
                                <option
                                    value="bg-purple"
                                    {{ request('bgColor') == 'bg-purple' ? 'selected' : '' }}
                                >Purple</option>
                                <option
                                    value="bg-dark"
                                    {{ request('bgColor') == 'bg-dark' ? 'selected' : '' }}
                                >Dark</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-sm d-flex align-items-center flex-grow-1 gap-2"
                                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="fa fa-filter"></i> Filtrar
                                </button>
                                <a
                                    href="/CatMenusPosweb"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="fa fa-times-circle"></i> Limpiar
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
                                class="fa fa-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Menús
                        </h5>
                        <p class="section-content-subtitle">Listado de menús registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar menú
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-tag me-1"></i>Tipo Menú</th>
                                <th><i class="fa fa-link me-1"></i>Link</th>
                                <th><i class="fa fa-star me-1"></i>Icono</th>
                                <th><i class="fa fa-paint-brush me-1"></i>Bg Color</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menusPosweb as $menuPosweb)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $menuPosweb->cmpIdMenu }}</td>
                                    <td style="font-weight: 500;">{{ $menuPosweb->cmpNomMenu }}</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $menuPosweb->ctmNomTipoMenu }}
                                        </span>
                                    </td>
                                    <td style="color: #64748b; font-size: 0.85rem;">{{ $menuPosweb->cmpLink }}</td>
                                    <td>
                                        <span
                                            class="px-2"
                                            style="background: #f8fafc; color: #64748b; border-radius: 6px; font-size: 1.1rem; border: 1px solid #e2e8f0; display: inline-block;"
                                        >
                                            <i class="fa {{ $menuPosweb->cmpIcono }}"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="{{ $menuPosweb->cmpBgColor }}"
                                            style="padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500; display: inline-block; min-width: 60px; text-align: center; color: white; text-transform: capitalize;"
                                        >
                                            {{ str_replace('bg-', '', $menuPosweb->cmpBgColor) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$menuPosweb->cmpIdMenu"
                                                modal="ModalEditar"
                                                title="Editar menú"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Menus.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="7"
                                    title="Sin datos disponibles"
                                    message="No se encontraron menús con los filtros seleccionados"
                                    icon="menu-button-wide"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatMenusPosweb"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $menusPosweb])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('Menus.ModalAgregar')

    <style>
        /* Colores de fondo pastel personalizados */
        .bg-orange {
            background: #fed7aa !important;
            /* Naranja pastel */
            color: #9a3412 !important;
            /* Texto naranja oscuro */
        }

        .bg-red {
            background: #fecaca !important;
            /* Rojo pastel */
            color: #991b1b !important;
            /* Texto rojo oscuro */
        }

        .bg-green {
            background: #bbf7d0 !important;
            /* Verde pastel */
            color: #166534 !important;
            /* Texto verde oscuro */
        }

        .bg-blue {
            background: #bfdbfe !important;
            /* Azul pastel */
            color: #1e40af !important;
            /* Texto azul oscuro */
        }

        .bg-purple {
            background: #ddd6fe !important;
            /* Púrpura pastel */
            color: #5b21b6 !important;
            /* Texto púrpura oscuro */
        }

        .bg-dark {
            background: #cbd5e1 !important;
            /* Gris pastel */
            color: #1e293b !important;
            /* Texto gris oscuro */
        }
    </style>
@endsection
