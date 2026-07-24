<x-page-container title="Catálogo Productos Cambio de Lista">
    <x-card-gradient-header
        icon="arrow-left-right"
        title="Catálogo Productos Cambio de Lista"
        subtitle="Gestione los productos con cambio de lista de precios"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/CatProdDiez"
            method="GET"
        >
            <x-form.group>
                <x-form.text
                    name="textValue"
                    label="Buscar"
                    icon="search"
                    placeholder="Buscar por código o artículo..."
                    col="col-md-4"
                    :autofocus="true"
                    :value="$textValue ?? ''"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Productos Registrados
                    </h5>
                    <p class="section-content-subtitle">{{ $catProducts->total() }} productos</p>
                </div>
                @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                    <button
                        class="btn-modern btn-agregar"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="bi bi-plus-circle me-2"></i>Agregar Producto
                    </button>
                @endif
            </div>

            <table class="table-hover table-custom table">
                <thead>
                    <tr>
                        <th><i class="bi bi-upc me-1"></i>Código</th>
                        <th><i class="bi bi-box me-1"></i>Artículo</th>
                        <th class="text-center"><i class="bi bi-arrow-down me-1"></i>Peso Mínimo</th>
                        <th class="text-center"><i class="bi bi-arrow-up me-1"></i>Peso Máximo</th>
                        <th><i class="bi bi-list-ol me-1"></i>Lista Precio</th>
                        <th><i class="bi bi-person me-1"></i>Usuario</th>
                        <th><i class="bi bi-calendar me-1"></i>Fecha Creación</th>
                        <th><i
                                class="bi bi-circle-fill me-1"
                                style="font-size: 0.5rem;"
                            ></i>Estatus</th>
                        @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                            <th class="text-center"><i class="bi bi-trash me-1"></i>Eliminar</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catProducts as $item)
                        <tr>
                            <td style="font-weight: 600; color: #0f172a;">{{ $item->CodArticulo }}</td>
                            <td>{{ $item->NomArticulo }}</td>
                            <td class="text-center">{{ $item->Cantidad_Ini }}</td>
                            <td class="text-center">{{ $item->Cantidad_Fin }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->NomListaPrecio }}</span>
                            </td>
                            <td>{{ $item->NomUsuario }}</td>
                            <td style="color: #64748b;">{{ strftime('%d %B %Y', strtotime($item->Creacion)) }}</td>
                            <td>
                                @if ($item->Status == 0)
                                    <span class="badge-status badge-active">
                                        <i class="bi bi-check-circle me-1"></i>Activo
                                    </span>
                                @else
                                    <span class="badge-status badge-inactive">
                                        <i class="bi bi-x-circle me-1"></i>Cancelado
                                    </span>
                                @endif
                            </td>
                            @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                                <td class="text-center">
                                    <button
                                        class="btn-table-action btn-table-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $item->IdCatProdDiez }}"
                                        title="Eliminar artículo"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->tipoUsuario->IdTipoUsuario != 2 ? '9' : '8' }}">
                                <div class="py-5 text-center">
                                    <div class="empty-state-icon mx-auto mb-3">
                                        <i
                                            class="bi bi-box fs-3"
                                            style="color: #94a3b8;"
                                        ></i>
                                    </div>
                                    <h6 class="text-muted">Sin productos registrados</h6>
                                    <small class="text-muted">No se encontraron resultados con los filtros
                                        seleccionados</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('components.paginate', ['items' => $catProducts])
        </div>
    </x-card-gradient-header>

    @foreach ($catProducts as $item)
        @include('CatProdDiez.ModalEliminarConfirm')
    @endforeach
    @include('CatProdDiez.ModalAgregar')
</x-page-container>
