@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ver Solicitudes de Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general d-flex flex-column gap-4">

        <!-- HEADER COMPACTO (Solo título) -->
        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="row gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', [
                        'titulo' => 'Solicitudes de Factura',
                        'options' => [['name' => 'Solicitar factura', 'value' => '/SolicitudFactura']],
                    ])
                </div>

                <!-- Filtro de Fechas -->
                <form action="/VerSolicitudesFactura"
                    method="GET"
                    class="col-12 col-lg d-lg-flex justify-content-end">
                    <div class="row">
                        <!-- Campo Fecha Inicio -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Fecha Inicio
                                </span>
                                <input type="date"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFecha1"
                                    id="fecha1"
                                    value="{{ $fecha1 }}"
                                    autofocus>
                            </div>
                        </div>

                        <!-- Campo Fecha Fin -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Fecha Fin
                                </span>
                                <input type="date"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFecha2"
                                    id="fecha2"
                                    value="{{ $fecha2 }}">
                            </div>
                        </div>

                        <!-- Botón Buscar -->
                        <div class="col-12 col-md-auto">
                            <div>
                                <button type="submit"
                                    class="btn btn-outline-dark bg-dark text-white w-100"
                                    title="Buscar">
                                    @include('components.icons.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-2">
                @include('Alertas.Alertas')
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">

            @if ($solicitudesFactura->where('Subir', 0)->count() > 0)
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <form action="/SolicitudesFactura/Subir"
                        method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit"
                            class="btn btn-sm"
                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                            <span class="d-flex align-items-center gap-1">
                                @include('components.icons.upload')
                                Subir solicitudes
                            </span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- TABLA DE SOLICITUDES -->
            <div class="table-responsive content-table-sm">
                <table class="table">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Folio</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Correo</th>
                            <th class="rounded-end text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solicitudesFactura as $solicitudFactura)
                            <tr class="align-middle">
                                <td>
                                    {{ $solicitudFactura->IdSolicitudFactura }}
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($solicitudFactura->FechaSolicitud)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                    </span>
                                </td>
                                <td> {{ $solicitudFactura->NomCliente }} </td>
                                <td> {{ $solicitudFactura->RFC }} </td>
                                <td> {{ $solicitudFactura->Email }} </td>
                                <td class="text-center">
                                    @if ($solicitudFactura->Subir == 1)
                                        <span class="tags-green d-inline-flex align-items-center gap-1"
                                            style="padding: 4px 8px; font-size: 0.7rem;"
                                            title="En línea">
                                            @include('components.icons.cloud-check')
                                            <span class="d-none d-md-inline">Subido</span>
                                        </span>
                                    @else
                                        <span class="tags-red d-inline-flex align-items-center gap-1"
                                            style="padding: 4px 8px; font-size: 0.7rem;"
                                            title="Fuera de línea">
                                            @include('components.icons.cloud-slash')
                                            <span class="d-none d-md-inline">No subido</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <!-- Icono -->
                                        <div class="mb-3 p-3 rounded-circle"
                                            style="background-color: rgba(30, 41, 59, 0.05); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                            @include('components.icons.file-text')
                                        </div>

                                        <!-- Mensaje principal -->
                                        <h6 class="fw-600 mb-2"
                                            style="color: #1e293b;">No hay solicitudes para mostrar</h6>

                                        <!-- Mensaje secundario -->
                                        <p class="text-muted small mb-0">
                                            No se encontraron solicitudes de factura en el período
                                            {{ \Carbon\Carbon::parse($fecha1)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($fecha2)->format('d/m/Y') }}
                                        </p>

                                        <!-- Sugerencia -->
                                        <div class="mt-3 p-2"
                                            style="background-color: #f8f9fa; border-radius: 6px;">
                                            <span class="small text-muted">
                                                @include('components.icons.calendar')
                                                Prueba con un rango de fechas diferente
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($solicitudesFactura->hasPages())
                <div class="d-flex justify-content-end mt-3 pt-2 border-top"
                    style="border-color: #e5e7eb;">
                    @include('components.paginate', ['items' => $solicitudesFactura])
                </div>
            @endif
        </div>
    </div>
@endsection
