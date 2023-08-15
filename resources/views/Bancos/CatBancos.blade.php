@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Bancos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid py-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Bancos'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarBanco">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar banco
                </button>
            </div>
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Banco</th>
                        <th class="rounded-end">Banco</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bancos as $banco)
                        <tr>
                            <td>{{ $banco->IdBanco }}</td>
                            <td>{{ $banco->NomBanco }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('Bancos.ModalAgregarBanco')
@endsection
