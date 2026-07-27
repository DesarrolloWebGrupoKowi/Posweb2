@props([
    'tiendaActual' => null,
])

{{-- Notificación de procesar tienda --}}
@if (isset($tiendaActual) && $tiendaActual->procesarcorte == 1)
    <div class="px-4 pb-3">
        <div
            class="rounded p-4"
            style="background: linear-gradient(135deg, #fff7ed 0%, #fffbeb 100%); border-left: 4px solid #f59e0b; border-top: 1px solid #fde68a; border-right: 1px solid #fde68a; border-bottom: 1px solid #fde68a; border-radius: 12px;"
        >
            <div class="d-flex align-items-start gap-3">
                <div
                    class="d-flex align-items-center justify-content-center flex-shrink-0 rounded"
                    style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.1); border-radius: 10px;"
                >
                    <i
                        class="bi bi-exclamation-triangle"
                        style="color: #d97706; font-size: 1.3rem;"
                    ></i>
                </div>
                <div class="flex-grow-1">
                    <h5
                        class="mb-1"
                        style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.95rem;"
                    >
                        Generación de pedido pendiente
                    </h5>
                    <p
                        class="mb-2"
                        style="color: var(--text-secondary); font-size: 0.82rem;"
                    >
                        <strong>Proceso detenido:</strong> Las ventas de este corte no fueron procesadas debido a que el
                        proceso se encuentra detenido.
                    </p>

                    {{-- Información adicional --}}
                    <div
                        class="d-flex flex-wrap gap-3"
                        style="font-size: 0.8rem;"
                    >
                        <div class="d-flex align-items-center gap-1">
                            <i
                                class="bi bi-calendar3"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            ></i>
                            <span style="color: var(--text-primary); font-weight: 500;">Fecha:</span>
                            <span style="color: var(--text-secondary);">
                                @if (isset($tiendaActual->fechaprocesarcorte))
                                    {{ \Carbon\Carbon::parse($tiendaActual->fechaprocesarcorte)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, h:mm a') }}
                                @else
                                    No especificada
                                @endif
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-1">
                            <i
                                class="bi bi-person"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            ></i>
                            <span style="color: var(--text-primary); font-weight: 500;">Detenido por:</span>
                            <span style="color: var(--text-secondary);">
                                @if (isset($tiendaActual->EmpleadoProcesarcorte))
                                    {{ $tiendaActual->EmpleadoProcesarcorte->Nombre }}
                                    {{ $tiendaActual->EmpleadoProcesarcorte->Apellidos }}
                                @else
                                    Usuario no identificado
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
