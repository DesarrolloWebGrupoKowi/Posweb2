@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Clientes Cloud')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Clientes Cloud'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar familia
                </button>
            </div>
        </div>
        <div class="">
            @include('Alertas.Alertas')
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                            <td colspan="3">No Hay Coincidencias!</td>
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
