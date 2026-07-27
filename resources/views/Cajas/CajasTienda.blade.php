<x-page-container title="Cajas Por Tienda">
    <x-card-gradient-header
        icon="cart3"
        title="Cajas Por Tienda"
        subtitle="Gestione las cajas asignadas a cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/CajasTienda"
            id="formCajaTienda"
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
                    onchange="document.getElementById('formCajaTienda').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-cash-stack me-2"
                                style="color: var(--text-muted);"
                            ></i>
                            Cajas de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Cajas asignadas actualmente a esta tienda
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button
                            class="btn-modern btn-agregar"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalAgregarCajaTienda"
                        >
                            <i class="bi bi-plus-circle me-2"></i>Agregar caja
                        </button>
                    </div>
                </div>

                <div
                    class="card overflow-hidden border-0 shadow-sm"
                    style="border-radius: 16px;"
                >
                    <div class="card-body p-0">
                        <table class="table-hover table-custom mb-0 table">
                            <thead class="table-light">
                                <tr>
                                    <th class="rounded-start ps-4">Tienda</th>
                                    <th class="rounded-end ps-4">Caja(s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cajasTienda->count() == 0)
                                    <tr>
                                        <td colspan="2">
                                            <div class="py-5 text-center">
                                                <i class="bi bi-cash-coin fs-1 text-muted d-block mb-3"></i>
                                                <h6 class="text-muted">Sin cajas asignadas</h6>
                                                <small class="text-muted">No hay cajas configuradas para esta
                                                    tienda</small>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($cajasTienda as $cajaTienda)
                                        <tr class="menu-row">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <i
                                                        class="bi bi-shop me-2"
                                                        style="color: var(--tag-blue-text);"
                                                    ></i>
                                                    <span class="fw-medium">{{ $cajaTienda->NomTienda }}</span>
                                                </div>
                                            </td>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-cash-register me-1"></i>
                                                    Caja {{ $cajaTienda->IdCaja }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: var(--border-light);"
                    ></i>
                </div>
                <h5 style="color: var(--text-primary);">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para ver sus cajas asignadas</p>
            </div>
        @endif
    </x-card-gradient-header>

    @include('Cajas.ModalAgregarCajaTienda')
    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCajaTienda').submit();
        });
    </script>
</x-page-container>
