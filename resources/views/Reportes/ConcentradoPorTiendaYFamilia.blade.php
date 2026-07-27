<x-page-container title="Concentrado por Tienda y Familia">
    <x-card-gradient-header
        icon="shop"
        title="Concentrado por Tienda y Familia"
        subtitle="Reporte de ventas agrupadas por tienda y familia"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/ReporteConcentradoPorTiendaYFamilia">
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :autofocus="true"
                />
                <x-form.date
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-3"
                    :value="empty($fecha1) ? date('Y-m-d') : $fecha1"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-3"
                    :value="empty($fecha2) ? date('Y-m-d') : $fecha2"
                />
                @if (Auth::user()->IdTipoUsuario == 2)
                    <div class="col-md-1">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >&nbsp;</label>
                        <div class="d-flex gap-1">
                            <input
                                type="radio"
                                class="btn-check"
                                name="optionsOnline"
                                id="online-off"
                                value="off"
                                {{ $optionsOnline == 'off' ? 'checked' : '' }}
                            >
                            <label
                                class="btn btn-sm d-flex align-items-center"
                                for="online-off"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; cursor: pointer; background: {{ $optionsOnline == 'off' ? '#fef2f2' : 'white' }}; color: {{ $optionsOnline == 'off' ? '#ef4444' : '#94a3b8' }};"
                            >
                                <i class="bi bi-cloud-slash"></i>
                            </label>
                            <input
                                type="radio"
                                class="btn-check"
                                name="optionsOnline"
                                id="online-on"
                                value="on"
                                {{ $optionsOnline == 'on' ? 'checked' : '' }}
                            >
                            <label
                                class="btn btn-sm d-flex align-items-center"
                                for="online-on"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; cursor: pointer; background: {{ $optionsOnline == 'on' ? '#f0fdf4' : 'white' }}; color: {{ $optionsOnline == 'on' ? '#10b981' : '#94a3b8' }};"
                            >
                                <i class="bi bi-cloud-check"></i>
                            </label>
                        </div>
                    </div>
                @endif
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- KPIs -->
        <div class="p-4 pb-0">
            <div class="d-flex flex-wrap gap-3">
                @php
                    $kpiColors = [
                        [
                            'bg' => 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)',
                            'circle' => 'rgba(59, 130, 246, 0.08)',
                            'text' => '#1d4ed8',
                            'icon' => 'bi-building',
                        ],
                        [
                            'bg' => 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
                            'circle' => 'rgba(16, 185, 129, 0.08)',
                            'text' => '#059669',
                            'icon' => 'bi-shop',
                        ],
                        [
                            'bg' => 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)',
                            'circle' => 'rgba(245, 158, 11, 0.08)',
                            'text' => '#d97706',
                            'icon' => 'bi-cart',
                        ],
                        [
                            'bg' => 'linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%)',
                            'circle' => 'rgba(139, 92, 246, 0.08)',
                            'text' => '#7c3aed',
                            'icon' => 'bi-people',
                        ],
                        [
                            'bg' => 'linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%)',
                            'circle' => 'rgba(20, 184, 166, 0.08)',
                            'text' => '#0d9488',
                            'icon' => 'bi-tag',
                        ],
                        [
                            'bg' => 'linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%)',
                            'circle' => 'rgba(236, 72, 153, 0.08)',
                            'text' => '#db2777',
                            'icon' => 'bi-star',
                        ],
                    ];
                    $colorIndex = 0;
                @endphp

                @foreach ($totales as $key => $total)
                    @if ($key != 'TOTAL')
                        @php
                            $color = $kpiColors[$colorIndex % count($kpiColors)];
                            $colorIndex++;
                        @endphp
                        <div
                            class="kpi-card"
                            style="background: {{ $color['bg'] }}; border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                        >
                            <div
                                style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: {{ $color['circle'] }}; border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span
                                        style="color: {{ $color['text'] }}; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >{{ $key }}</span>
                                    <i
                                        class="bi {{ $color['icon'] }}"
                                        style="color: {{ $color['text'] }}; font-size: 1.1rem; opacity: 0.6;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                                >${{ number_format($total, 2) }}</h3>
                                <span style="color: #64748b; font-size: 0.75rem;">{{ number_format($kilos[$key], 2) }}
                                    kg</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Tabla -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Concentrado por Tienda y Familia
                    </h5>
                    <p class="section-content-subtitle">Listado de ventas agrupadas por tienda y familia</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-folder me-1"></i>Familia</th>
                            <th class="text-center"><i class="bi bi-box me-1"></i>Kilos</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concentrado as $tConcentrado)
                            <tr>
                                <td style="font-weight: 500;">{{ $tConcentrado->NomTienda }}</td>
                                <td>
                                    <span
                                        style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;"
                                    >
                                        {{ $tConcentrado->NomGrupo }}
                                    </span>
                                </td>
                                <td
                                    class="text-center"
                                    style="font-weight: 500;"
                                >{{ number_format($tConcentrado->kilos, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 600; color: #10b981;"
                                >{{ number_format($tConcentrado->importe, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: #94a3b8;"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: #64748b; font-size: 0.85rem;"
                                    >No hay ventas en el rango de fechas seleccionadas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($concentrado->count() > 0)
                        <tfoot>
                            <tr style="background: #f1f5f9; font-weight: 700;">
                                <td
                                    colspan="2"
                                    class="text-end"
                                    style="color: #0f172a;"
                                >Totales:</td>
                                <td class="text-center">{{ number_format($kilos['TOTAL'], 2) }} kg</td>
                                <td
                                    class="text-end"
                                    style="color: #10b981;"
                                >${{ number_format($totales['TOTAL'], 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
