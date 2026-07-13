<x-page-container title="Tipos de Pago por Tienda">
    <x-card-gradient-header
        icon="credit-card"
        title="Tipos de Pago por Tienda"
        subtitle="Gestione los tipos de pago asignados a cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/DatTipoPagoTienda"
            id="formTipoPagoTienda"
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
                    onchange="document.getElementById('formTipoPagoTienda').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-credit-card me-2"
                                style="color: #64748b;"
                            ></i>
                            Tipos de Pago de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Agregue o remueva tipos de pago para esta tienda
                        </p>
                    </div>
                </div>

                @foreach ($tiposPagoTienda as $tipoPagoTienda)
                    <div class="row g-4">
                        <!-- Tipos de Pago Asignados -->
                        <div class="col-md-6">
                            <form
                                action="/RemoverDatTipoPagoTienda"
                                method="POST"
                            >
                                @csrf
                                <input
                                    type="hidden"
                                    name="idTienda"
                                    value="{{ $idTienda }}"
                                >

                                <div class="card-modern">
                                    <div
                                        class="card-header border-bottom p-3"
                                        style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);"
                                    >
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6
                                                    class="fw-bold mb-0"
                                                    style="color: #0f172a;"
                                                >
                                                    <i
                                                        class="bi bi-credit-card fs-5 me-2"
                                                        style="color: #ef4444;"
                                                    ></i>
                                                    Tipos de Pago Asignados
                                                </h6>
                                                <small class="text-muted ms-4">{{ $tipoPagoTienda->TiposPago->count() }}
                                                    asignados</small>
                                            </div>
                                            <span
                                                class="badge bg-danger text-danger rounded-pill bg-opacity-10 px-3 py-2"
                                            >
                                                <i
                                                    class="bi bi-collection me-1"></i>{{ $tipoPagoTienda->TiposPago->count() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="card-body p-0"
                                        style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                    >
                                        @if ($tipoPagoTienda->TiposPago->count() == 0)
                                            <div class="py-5 text-center">
                                                <i class="bi bi-credit-card-2-front fs-1 text-muted d-block mb-3"></i>
                                                <h6 class="text-muted">Sin tipos de pago asignados</h6>
                                                <small class="text-muted">Agregue tipos de pago desde la lista de
                                                    disponibles</small>
                                            </div>
                                        @else
                                            <table class="table-hover table-custom mb-0 table">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th class="rounded-start ps-4">Tipo de Pago</th>
                                                        <th
                                                            width="80"
                                                            class="rounded-end text-center"
                                                        >Remover</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tipoPagoTienda->TiposPago as $tPago)
                                                        <tr class="menu-row">
                                                            <td class="ps-4">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="bi bi-cash-stack me-2"
                                                                        style="color: #ef4444;"
                                                                    ></i>
                                                                    <span
                                                                        class="fw-medium">{{ $tPago->NomTipoPago }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <input
                                                                    class="form-check-input-modern chk-remover"
                                                                    type="checkbox"
                                                                    name="chkIdTipoPagoRemove[]"
                                                                    value="{{ $tPago->IdTipoPago }}"
                                                                >
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>

                                    @if ($tipoPagoTienda->TiposPago->count() > 0)
                                        <div class="card-footer border-top bg-white p-3">
                                            <div class="d-flex justify-content-end">
                                                <button
                                                    type="submit"
                                                    class="btn-modern btn-remover btn-disabled"
                                                    id="btnRemover"
                                                    disabled
                                                >
                                                    <i class="bi bi-trash me-2"></i>Remover
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <!-- Tipos de Pago Disponibles -->
                        <div class="col-md-6">
                            <form
                                action="/AgregarDatTipoPagoTienda"
                                method="POST"
                            >
                                @csrf
                                <input
                                    type="hidden"
                                    name="idTienda"
                                    value="{{ $idTienda }}"
                                >

                                <div class="card-modern">
                                    <div
                                        class="card-header border-bottom p-3"
                                        style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);"
                                    >
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6
                                                    class="fw-bold mb-0"
                                                    style="color: #0f172a;"
                                                >
                                                    <i
                                                        class="bi bi-plus-circle fs-5 me-2"
                                                        style="color: #10b981;"
                                                    ></i>
                                                    Tipos de Pago Disponibles
                                                </h6>
                                                <small class="text-muted ms-4">{{ $tiposPagoFaltantes->count() }}
                                                    disponibles</small>
                                            </div>
                                            <span
                                                class="badge bg-success text-success rounded-pill bg-opacity-10 px-3 py-2"
                                            >
                                                <i class="bi bi-collection me-1"></i>{{ $tiposPagoFaltantes->count() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="card-body p-0"
                                        style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                    >
                                        @if ($tiposPagoFaltantes->count() == 0)
                                            <div class="py-5 text-center">
                                                <i class="bi bi-check-circle fs-1 text-muted d-block mb-3"></i>
                                                <h6 class="text-muted">Sin tipos de pago disponibles</h6>
                                                <small class="text-muted">Todos los tipos de pago están
                                                    asignados</small>
                                            </div>
                                        @else
                                            <table class="table-hover table-custom mb-0 table">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th class="rounded-start ps-4">Tipo de Pago</th>
                                                        <th
                                                            width="80"
                                                            class="rounded-end text-center"
                                                        >Agregar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tiposPagoFaltantes as $tPagoFaltante)
                                                        <tr class="menu-row">
                                                            <td class="ps-4">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="bi bi-cash-stack me-2"
                                                                        style="color: #10b981;"
                                                                    ></i>
                                                                    <span
                                                                        class="fw-medium">{{ $tPagoFaltante->NomTipoPago }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <input
                                                                    class="form-check-input-modern chk-agregar"
                                                                    type="checkbox"
                                                                    name="chkIdTipoPagoAdd[]"
                                                                    value="{{ $tPagoFaltante->IdTipoPago }}"
                                                                >
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>

                                    @if ($tiposPagoFaltantes->count() > 0)
                                        <div class="card-footer border-top bg-white p-3">
                                            <div class="d-flex justify-content-end">
                                                <button
                                                    type="submit"
                                                    class="btn-modern btn-agregar btn-disabled"
                                                    id="btnAgregar"
                                                    disabled
                                                >
                                                    <i class="bi bi-plus-circle me-2"></i>Agregar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
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
                <p class="text-muted">Elija una tienda del filtro para gestionar sus tipos de pago</p>
            </div>
        @endif
    </x-card-gradient-header>
    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formTipoPagoTienda').submit();
        });

        // Control de botones según checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const chkRemover = document.querySelectorAll('.chk-remover');
            const chkAgregar = document.querySelectorAll('.chk-agregar');
            const btnRemover = document.getElementById('btnRemover');
            const btnAgregar = document.getElementById('btnAgregar');

            function actualizarBoton(checkboxes, boton) {
                if (!boton) return;
                const algunoSeleccionado = Array.from(checkboxes).some(cb => cb.checked);
                boton.disabled = !algunoSeleccionado;
                if (algunoSeleccionado) {
                    boton.classList.remove('btn-disabled');
                } else {
                    boton.classList.add('btn-disabled');
                }
            }

            if (chkRemover.length > 0) {
                chkRemover.forEach(cb => {
                    cb.addEventListener('change', () => actualizarBoton(chkRemover, btnRemover));
                });
            }

            if (chkAgregar.length > 0) {
                chkAgregar.forEach(cb => {
                    cb.addEventListener('change', () => actualizarBoton(chkAgregar, btnAgregar));
                });
            }
        });
    </script>
</x-page-container>
