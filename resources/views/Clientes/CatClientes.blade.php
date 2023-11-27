@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Clientes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Clientes'])
            <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatClientes" method="get">
                <input type="hidden" class="idPagination" value="&txtFiltro={{ $txtFiltro }}">
                <div class="input-group" style="max-width: 300px">
                    <input class="form-control" type="text" name="txtFiltro" id="txtFiltro"
                        placeholder="Buscar por nombre o RFC" value="{{ $txtFiltro }}">
                    <div class="input-group-append">
                        <button class="input-group-text"><span class="material-icons">search</span></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="">
            @include('Alertas.Alertas')
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Cliente</th>
                        <th>RFC</th>
                        <th>Nombre</th>
                        <th>Tipo de Cliente</th>
                        {{-- <th>Locacion</th> --}}
                        {{-- <th>Metodo Pago</th> --}}
                        <th class="rounded-end">Locacion</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($clientes->count() == 0)
                        <tr>
                            <td colspan="3">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->IdClienteCloud }}</td>
                                <td>{{ $cliente->RFC }}</td>
                                <td>{{ $cliente->NomCliente }}</td>
                                <td>{{ $cliente->TipoPersona }}</td>
                                <td>{{ $cliente->Locacion }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {!! $clientes->links() !!}
    </div>
@endsection
