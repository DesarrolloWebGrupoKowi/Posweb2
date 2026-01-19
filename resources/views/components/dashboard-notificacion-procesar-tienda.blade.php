@props([
    'tiendaActual' => null, // Tienda actual
])

@if (isset($tiendaActual) && $tiendaActual->procesarcorte == 1)
    <div class="process-status-card mb-4"
        style="border-radius: 10px; background: linear-gradient(135deg, #fff7ed 0%, #fffbeb 100%); border-left: 4px solid #f59e0b; border-top: 1px solid #fde68a; border-right: 1px solid #fde68a; border-bottom: 1px solid #fde68a;">
        <div class="p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="status-icon"
                        style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            style="width: 24px; height: 24px; color: #d97706;"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="mb-1"
                            style="color: #92400e; font-weight: 600;">
                            Generación de pedido pendiente
                        </h5>
                        <p class="mb-0"
                            style="color: #b45309;">
                            <span class="fw-500">Proceso detenido:</span>
                            Las ventas de este corte no fueron procesadas debido a que el proceso se encuentra
                            detenido.
                        </p>

                        <!-- Información adicional -->
                        <div class="mt-2 d-flex flex-wrap gap-3"
                            style="font-size: 0.875rem;">
                            <div class="d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    style="width: 14px; height: 14px; color: #92400e;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span style="color: #92400e; font-weight: 500;">Fecha:</span>
                                <span style="color: #b45309;">
                                    @if (isset($tiendaActual->fechaprocesarcorte))
                                        {{ \Carbon\Carbon::parse($tiendaActual->fechaprocesarcorte)->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, h:mm a') }}
                                    @else
                                        No especificada
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    style="width: 14px; height: 14px; color: #92400e;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span style="color: #92400e; font-weight: 500;">Detenido por:</span>
                                <span style="color: #b45309;">
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
    </div>
@endif
