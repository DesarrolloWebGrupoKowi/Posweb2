@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Clientes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <!-- HEADER COMPACTO (Solo título y alertas) -->
        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="row gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Catálogo de Clientes'])
                </div>

                <!-- Filtro de Fecha -->
                <form action="CatClientes"
                    method="GET"
                    class="col-12 col-lg d-lg-flex justify-content-end">

                    <div class="row">
                        <!-- Campo de Texto de Busqueda -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Buscar
                                </span>
                                <input type="text"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFiltro"
                                    id="txtFiltro"
                                    value="{{ $txtFiltro }}"
                                    placeholder="RFC, Nombre o Locacion..."
                                    autofocus>
                            </div>
                        </div>

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

            <div class="d-flex justify-content-between">
                @include('components.number-paginate')

                <form action="/CatClientes/Actualizar"
                    method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit"
                        class="btn-loading btn btn-sm"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                        <span id="buttonIcon">
                            @include('components.icons.upload')
                        </span>
                        Actualizar clientes
                    </button>
                </form>
            </div>

            <div class="table-responsive content-table-sm mt-3">
                <table class="table">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Id Cliente</th>
                            <th>RFC</th>
                            <th>Nombre</th>
                            <th>Tipo de Cliente</th>
                            <th class="rounded-end">Locacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $clientes, 'colspan' => 5])
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->IdClienteCloud }}</td>
                                <td>{{ $cliente->RFC }}</td>
                                <td>{{ $cliente->NomCliente }}</td>
                                <td>{{ $cliente->TipoPersona }}</td>
                                <td>{{ $cliente->Locacion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('components.paginate', ['items' => $clientes])
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Ajuste para el mensaje vacío (componente table-empty) */
        .table tbody tr td.py-5 {
            background-color: white;
        }

        /* Hacer el ícono más grande en el mensaje vacío */
        .rounded-circle svg {
            width: 40px;
            height: 40px;
        }
    </style>
@endsection
