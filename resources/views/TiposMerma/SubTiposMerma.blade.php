@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Sub Tipos de Merma')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Catálogo de Sub Tipos de Merma</h2>
        </div>
    </div>
    <div class="container mb-3">
        <form id="formTipoMerma" action="/SubTiposMerma " method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTipoMerma" id="idTipoMerma">
                        <option value="">Seleccione Tipo de Merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($idTipoMerma))
        <div class="container">
            <div class="d-flex justify-content-end mb-2 me-3">
                <div class="col-auto">
                    <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarSubTipoMerma">
                        <span class="material-icons">add_circle</span>
                    </button>
                </div>
            </div>
            <table class="table table-striped table-responsiv shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo de Merma</th>
                        <th>Sub Tipo de Merma</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($subTiposMerma->count() == 0)
                        <tr>
                            <td colspan="3">No hay sub tipos de merma para este tipo de merma!</td>
                        </tr>
                    @else
                        @foreach ($subTiposMerma as $subTipoMerma)
                            <tr>
                                <td>{{ $subTipoMerma->NomTipoMerma }}</td>
                                <td>{{ $subTipoMerma->NomSubTipoMerma }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    @endif
    @include('TiposMerma.ModalAgregarSubTipoMerma')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMerma').submit();
        });
    </script>
@endsection
