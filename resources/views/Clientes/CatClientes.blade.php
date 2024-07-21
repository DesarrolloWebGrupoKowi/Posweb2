@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Clientes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Clientes'])
            </div>

            <div class="">
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                @include('components.table-search')
            </div>
            <table>
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
            @include('components.paginate', ['items' => $clientes])
        </div>
    </div>
@endsection
