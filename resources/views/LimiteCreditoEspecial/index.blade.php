@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Límite Crédito Para Empleados')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="person-badge"
            title="Límites de Crédito Para Empleados"
            subtitle="Gestión de límites de crédito por empleado"
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
                        <p class="section-content-subtitle">Listado de empleados con límite de crédito asignado</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarEmpleado"
                    >
                        <i class="bi bi-plus-circle"></i> Agregar Empleado
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>Núm. Nómina</th>
                                <th><i class="bi bi-person me-1"></i>Empleado</th>
                                <th><i class="bi bi-cash-stack me-1"></i>Límite Crédito</th>
                                <th><i class="bi bi-graph-up me-1"></i>Ventas Diarias</th>
                                <th><i class="bi bi-gear me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($limitesCredito as $lCredito)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $lCredito->NumNomina }}</td>
                                    <td style="font-weight: 500;">{{ $lCredito->Nombre }} {{ $lCredito->Apellidos }}</td>
                                    <td>
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
                                                :id="$lCredito->IdCatLimiteCreditoEspecial"
                                                modal="ModalEditarEmpleado"
                                                title="Editar límite de crédito"
                                                label="Editar"
                                            />
                                            <x-table.buttons.delete-button
                                                :id="$lCredito->IdCatLimiteCreditoEspecial"
                                                modal="ModalEliminarEmpleado"
                                                title="Eliminar límite de crédito"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('LimiteCreditoEspecial.ModalEditar')
                                @include('LimiteCreditoEspecial.ModalEliminar')
                            @empty
                                <x-table-empty-data
                                    colspan="5"
                                    title="Sin datos disponibles"
                                    message="No se encontraron empleados con límite de crédito asignado"
                                    icon="person-check"
                                    :action="true"
                                    actionText="Agregar Empleado"
                                    actionUrl="#"
                                    actionIcon="plus-circle"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $limitesCredito])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('LimiteCreditoEspecial.ModalAgregar')
@endsection
