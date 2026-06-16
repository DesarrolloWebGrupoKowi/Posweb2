@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Límite Crédito')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="credit-card"
            title="Tipos de Nómina y Límites de Crédito"
            subtitle="Gestión de límites de crédito por tipo de nómina"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Límites de Crédito
                        </h5>
                        <p class="section-content-subtitle">Listado de límites de crédito registrados en el sistema</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>Id Tipo Nómina</th>
                                <th><i class="bi bi-person-badge me-1"></i>Tipo Empleado</th>
                                <th><i class="bi bi-cash-stack me-1"></i>Límite Crédito</th>
                                <th><i class="bi bi-graph-up me-1"></i>Ventas Diarias</th>
                                <th><i class="bi bi-gear me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($limitesCredito as $lCredito)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $lCredito->TipoNomina }}</td>
                                    <td style="font-weight: 500;">{{ $lCredito->NomTipoNomina }}</td>
                                    <td style="font-weight: 600;">
                                        <span
                                            style="background: #f0fdf4; color: #10b981; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            ${{ number_format($lCredito->Limite, 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $lCredito->TotalVentaDiaria }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$lCredito->IdCatLimiteCredito"
                                                modal="ModalEditar"
                                                title="Editar límite de crédito"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('LimiteCredito.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="5"
                                    title="Sin datos disponibles"
                                    message="No se encontraron límites de crédito registrados"
                                    icon="credit-card"
                                    :action="true"
                                    actionText="Actualizar"
                                    actionUrl="/CatLimiteCredito"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>
@endsection
