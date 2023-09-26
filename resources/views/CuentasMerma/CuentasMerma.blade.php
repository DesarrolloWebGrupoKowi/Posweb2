@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cuentas Mermas Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Cuentas Merma'])
            @if ($cuentasMerma->count() == 0 && !empty($idTipoMerma))
                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregarCuentaMerma">
                    <i class="fa fa-plus-circle"></i> Agregar catálogo
                </button>
            @endif
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-between pb-4 gap-4" id="formCuentasMerma" action="/CuentasMerma"
            method="GET">
            <div class="input-group" style="max-width: 350px">
                <select class="form-select" name="idTipoMerma" id="idTipoMerma" required>
                    <option value="">Seleccione Tipo Merma</option>
                    @foreach ($tiposMerma as $tipoMerma)
                        <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                            {{ $tipoMerma->NomTipoMerma }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if (!empty($idTipoMerma))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Tipo Merma</th>
                            <th>Libro</th>
                            <th>Cuenta</th>
                            <th>Subcuenta</th>
                            <th>Intercosto</th>
                            <th>Futuro</th>
                            <th class="rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @if ($cuentasMerma->count() == 0)
                            <tr>
                                <td colspan="7">No hay cuentas para este tipo de merma <i
                                        class="fa fa-exclamation-circle"></i></td>
                            </tr>
                        @else
                            @foreach ($cuentasMerma as $cuentaMerma)
                                <tr>
                                    <td>{{ $cuentaMerma->NomTipoMerma }}</td>
                                    <td>{{ $cuentaMerma->Libro }}</td>
                                    <td>{{ $cuentaMerma->Cuenta }}</td>
                                    <td>{{ $cuentaMerma->SubCuenta }}</td>
                                    <td>{{ $cuentaMerma->InterCosto }}</td>
                                    <td>{{ $cuentaMerma->Futuro }}</td>
                                    <td>
                                        <button class="btn" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditarCuentaMerma{{ $cuentaMerma->IdCatCuentaMerma }}">
                                            <span class="material-icons">edit</span>
                                        </button>
                                    </td>
                                    @include('CuentasMerma.ModalEditarCuentaMerma')
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @include('CuentasMerma.ModalAgregarCuentaMerma')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formCuentasMerma').submit();
        });
    </script>
@endsection
