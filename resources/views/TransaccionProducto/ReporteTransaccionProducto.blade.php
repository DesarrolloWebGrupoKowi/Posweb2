@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial transacción')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial transacción',
                    'options' => [['name' => 'Transacción de Producto', 'value' => '/TransaccionProducto']],
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                <form id="search-form" class="d-flex align-items-center justify-content-end gap-2 pb-2"
                    action="/HistorialTransaccion" method="GET">
                    <div class="d-flex flex-column">
                        <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                            value="{{ $fecha1 }}" autofocus>
                    </div>
                    <div class="d-flex flex-column">
                        <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2" required
                            value="{{ $fecha2 }}">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark-outline">
                            @include('components.icons.search')
                        </button>
                    </div>
                </form>
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        {{-- <th>Origen</th> --}}
                        <th>Destino</th>
                        <th>Fecha transacción</th>
                        {{-- <th>Usuario</th> --}}
                        <th></th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $transferencias, 'colspan' => 9])
                    @foreach ($transferencias as $transferencia)
                        <tr>
                            <td>{{ $transferencia->IdTransferencia }}</td>
                            {{-- <td>{{ $transferencia->tiendaOrigen }}</td> --}}
                            <td>{{ $transferencia->tiendaDestino }}</td>
                            <td>{{ \Carbon\Carbon::parse($transferencia->FechaTransferencia)->format('d/m/Y') }}</td>
                            {{-- <td>{{ $transferencia->NomUsuario }} </td> --}}
                            <td>
                                @if ($transferencia->Subir == 1)
                                    <span class="tags-green" title="En linea"> @include('components.icons.cloud-check') </span>
                                @else
                                    <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash') </span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalShowDetails{{ $transferencia->IdTransferencia }}">
                                    @include('components.icons.list')
                                </button>
                                @include('TransaccionProducto.ModalShowDetails')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $transferencias])
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('submit', e => {
            if (e.target.matches('#search-form')) {
                const form = document.getElementById('search-form');
                const url = location.href;
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const entries = urlParams.entries();

                for (const entry of entries) {
                    if (entry[0] != 'fecha1' && entry[0] != 'fecha2') {
                        let input = document.createElement('input');
                        input.type = "hidden";
                        input.name = entry[0];
                        input.value = entry[1];
                        form.appendChild(input);
                    }
                }

                form.setAttribute('action', url);
                form.submit();
            }
        })
    </script>
@endsection
