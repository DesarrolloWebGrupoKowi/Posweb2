<x-page-container title="Actualizar Inventario">
    <x-card-gradient-header
        icon="box-seam"
        title="Actualizar Inventario"
        subtitle="Modifique el stock de artículos por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <a
                href="/ReporteStockAdmin"
                class="btn-modern btn-outline-modern"
            >
                <i class="bi bi-bar-chart me-2"></i>Inventario
            </a>
        </x-slot:buttons>

        <x-form.form
            action="/UpdateStockViewAdmin"
            id="formStock"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? ''"
                    onchange="document.getElementById('formStock').submit()"
                    :autofocus="true"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>
                            Stock de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">{{ count($stocks) }} artículos</p>
                    </div>
                </div>

                <form
                    action="/UpdateStockAdmin/{{ $idTienda }}"
                    method="POST"
                >
                    @csrf
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-upc me-1"></i>Código</th>
                                <th><i class="bi bi-box me-1"></i>Artículo</th>
                                <th class="text-center"><i class="bi bi-boxes me-1"></i>Stock Actual</th>
                                <th class="text-center"><i class="bi bi-pencil me-1"></i>Actualizar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $stock)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $stock->CodArticulo }}</td>
                                    <td>{{ $stock->NomArticulo }}</td>
                                    <td class="text-center">
                                        <span
                                            style="font-weight: {{ $stock->StockArticulo <= 0 ? '700' : '500' }}; color: {{ $stock->StockArticulo <= 0 ? '#ef4444' : '#0f172a' }};"
                                        >
                                            {{ $stock->StockArticulo }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <input
                                            name="stock[{{ $stock->CodArticulo }}]"
                                            class="form-control form-control-sm-modern mx-auto"
                                            style="width: 120px; text-align: center;"
                                            type="number"
                                            step="any"
                                            value="{{ $stock->StockArticulo }}"
                                        >
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="py-5 text-center">
                                            <div class="empty-state-icon mx-auto mb-3">
                                                <i
                                                    class="bi bi-box-seam fs-3"
                                                    style="color: #94a3b8;"
                                                ></i>
                                            </div>
                                            <h6 class="text-muted">Sin artículos disponibles</h6>
                                            <small class="text-muted">No se encontraron artículos para esta
                                                tienda</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if (count($stocks) != 0)
                        <div class="d-flex justify-content-end mt-4">
                            <button
                                type="submit"
                                class="btn-modern btn-agregar"
                            >
                                <i class="bi bi-floppy me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para actualizar su inventario</p>
            </div>
        @endif
    </x-card-gradient-header>
</x-page-container>

<script>
    document.getElementById('idTienda').addEventListener('change', (e) => {
        document.getElementById('formStock').submit();
    });
</script>
