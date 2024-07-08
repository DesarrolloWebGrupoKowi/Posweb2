@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Clientes Cloud')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Clientes Cloud'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar familia @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div class="">
                @include('Alertas.Alertas')
            </div>
        </div>
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Cliente</th>
                        <th>Nombre</th>
                        <th>Tipo de Cliente</th>
                        <th>Uso CFDI</th>
                        <th>Metodo Pago</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($clientesCloud->count() == 0)
                        <tr>
                            <td colspan="6">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($clientesCloud as $clienteCloud)
                            <tr>
                                <td>{{ $clienteCloud->IdClienteCloud }}</td>
                                <td>{{ $clienteCloud->NomClienteCloud }}</td>
                                <td>{{ $clienteCloud->TipoCliente }}</td>
                                <td>{{ $clienteCloud->UsoCfdi }}</td>
                                <td>{{ $clienteCloud->MetodoPago }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('ClientesCloud.ModalAgregar')
@endsection
