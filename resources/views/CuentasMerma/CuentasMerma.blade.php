@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cuentas Mermas Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Cuentas Merma'])
                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregarCuentaMerma"
                    {{ count($tiposMerma) == 0 ? 'disabled' : '' }}>
                    Agregar cuenta @include('components.icons.plus-circle')
                </button>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
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
                    @include('components.table-empty', ['items' => $cuentasMerma, 'colspan' => 7])
                    @foreach ($cuentasMerma as $cuentaMerma)
                        <tr>
                            {{-- <td>@dump($cuentaMerma)</td> --}}
                            <td>{{ $cuentaMerma->NomTipoMerma }}</td>
                            <td>{{ $cuentaMerma->Libro }}</td>
                            <td>{{ $cuentaMerma->Cuenta }}</td>
                            <td>{{ $cuentaMerma->SubCuenta }}</td>
                            <td>{{ $cuentaMerma->InterCosto }}</td>
                            <td>{{ $cuentaMerma->Futuro }}</td>
                            <td>
                                {{-- IdTipoMerma --}}
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditarCuentaMerma{{ $cuentaMerma->IdCatCuentaMerma }}">
                                    @include('components.icons.edit')
                                </button>
                            </td>
                            @include('CuentasMerma.ModalEditarCuentaMerma')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('CuentasMerma.ModalAgregarCuentaMerma')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formCuentasMerma').submit();
        });
    </script>
@endsection
