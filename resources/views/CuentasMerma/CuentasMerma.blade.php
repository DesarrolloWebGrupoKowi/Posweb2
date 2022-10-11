@extends('plantillaBase.masterblade')
@section('title', 'Cuentas Mermas Por Tienda')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Catálogo de Cuentas Merma</h2>
        </div>
    </div>
    <div class="container mb-3">
        <form id="formCuentasMerma" action="/CuentasMerma" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTipoMerma" id="idTipoMerma" required>
                        <option value="">Seleccione Tipo Merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    @if ($cuentasMerma->count() == 0 && !empty($idTipoMerma))
        <div class="container mb-3">
            <div class="d-flex justify-content-end">
                <button class="btn card shadow me-4" data-bs-toggle="modal" data-bs-target="#ModalAgregarCuentaMerma">
                    <span class="material-icons">add_circle</span>
                </button>
            </div>
        </div>
    @endif
    @if (!empty($idTipoMerma))
        <div class="container">
            <table style="font-size: 16px" class="table table-striped table-responsive shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo Merma</th>
                        <th>Libro</th>
                        <th>Cuenta</th>
                        <th>Subcuenta</th>
                        <th>Intercosto</th>
                        <th>Futuro</th>
                        <th>Acciones</th>
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
                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEditarCuentaMerma{{ $cuentaMerma->IdCatCuentaMerma }}">
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
    @include('CuentasMerma.ModalAgregarCuentaMerma')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formCuentasMerma').submit();
        });
    </script>
@endsection
