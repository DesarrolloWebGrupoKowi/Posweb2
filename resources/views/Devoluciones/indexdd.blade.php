<x-page-container title="Devoluciones">
    <x-card-gradient-header
        icon="arrow-return-left"
        title="Devoluciones"
        subtitle="Gestión de devoluciones y notas de crédito"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        {{-- @include('devoluciones.components.filtros') --}}

        {{-- <div class="p-4">
            @if ($devolucionSeleccionada)
                <!-- MODO DETALLE -->
                <div id="botones-container">
                    @include('devoluciones.components.detalle.botones-accion')
                </div>

                @include('devoluciones.components.detalle.info-general')

                <div class="row g-4">
                    <div
                        id="paso1-container"
                        class="col-md-6"
                    >
                        @include('devoluciones.components.detalle.paso1-transaccion')
                    </div>
                    <div
                        id="paso2-container"
                        class="col-md-6"
                    >
                        @include('devoluciones.components.detalle.paso2-recepcion')
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-list-ul me-2"
                                style="color: #64748b;"
                            ></i>Líneas de la Devolución
                            <span
                                id="badge-oracle"
                                class="badge ms-2"
                                style="{{ $estatusOracle ? 'background: #fef2f2; color: #dc2626;' : 'background: #f8fafc; color: #94a3b8;' }} font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                            >
                                <i class="bi {{ $estatusOracle ? 'bi-database-fill' : 'bi-database' }} me-1"></i>
                                {{ $estatusOracle ? 'Oracle sincronizado' : 'Sin datos Oracle' }}
                            </span>
                        </h5>
                        <p class="section-content-subtitle">Detalle de artículos devueltos</p>
                    </div>
                    <button
                        type="button"
                        id="btnActualizarDetalle"
                        class="btn d-flex align-items-center gap-2"
                        style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem;"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                        <span class="btn-text">Actualizar</span>
                        <span
                            class="spinner-border spinner-border-sm d-none"
                            style="width: 1rem; height: 1rem;"
                        ></span>
                    </button>
                </div>

                <div id="lineas-container">
                    @include('devoluciones.components.detalle.tabla-lineas')
                </div>
            @else
                <!-- MODO LISTA -->
                <div id="concentrado-container">
                    @include('devoluciones.components.lista.tabla-concentrado')
                </div>
            @endif
        </div> --}}

        {{-- @include('devoluciones.components.modales.confirmar-accion')
        @include('devoluciones.components.modales.resultado-accion') --}}
    </x-card-gradient-header>
</x-page-container>

{{-- Cargar scripts modulares --}}
{{-- <script src="{{ asset('js/devoluciones/toast.js') }}"></script>
<script src="{{ asset('js/devoluciones/api.js') }}"></script>
<script src="{{ asset('js/devoluciones/ui.js') }}"></script>
<script src="{{ asset('js/devoluciones/acciones.js') }}"></script>
<script src="{{ asset('js/devoluciones/app.js') }}"></script> --}}
