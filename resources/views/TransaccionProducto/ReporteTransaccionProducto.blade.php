@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial transacción')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial transacción',
                    'options' => [['name' => 'Transacción de Producto', 'value' => '/TransaccionProducto']],
                ])
                <div>
                    <form action="/HistorialTransaccionExcel" method="GET">
                        <input type="hidden" name="fecha1" value="{{ $fecha1 }}">
                        <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                        <input type="hidden" name="codigo" value="{{ $codigo }}">
                        <input type="hidden" name="idTiendaDestino" value="{{ $idTiendaDestino }}">
                        {{-- <input type="hidden" name="idTienda" value="{{ $idTienda }}"> --}}
                        <button type="submit" class="input-group-text text-decoration-none btn-excel">
                            Exportar @include('components.icons.excel')
                        </button>
                    </form>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                <form id="search-form" class="d-flex align-items-center justify-content-end gap-2 pb-2">
                    <select class="form-select rounded" name="idTiendaDestino">
                        <option value="">Selecciona una tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->IdTienda }}"
                                {{ $idTiendaDestino == $tienda->IdTienda ? 'selected' : '' }}>
                                {{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" class="form-control rounded" name="codigo" value="{{ $codigo }}"
                        placeholder="Codigo">

                    <input type="date" class="form-control rounded" name="fecha1" value="{{ $fecha1 }}" autofocus>

                    <input type="date" class="form-control rounded" name="fecha2" value="{{ $fecha2 }}">

                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </form>
            </div>
            <table>
                <thead class="table-head">
                    <th class="rounded-start">Folio</th>
                    <th>Destino</th>
                    <th>Fecha</th>
                    <th>Código</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th class="rounded-end">Estado</th>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $transferencias, 'colspan' => 9])
                    @foreach ($transferencias as $transferencia)
                        <tr>
                            <td>{{ $transferencia->IdTransferencia }}</td>
                            <td>{{ $transferencia->tiendaDestino }}</td>
                            <td>{{ \Carbon\Carbon::parse($transferencia->FechaTransferencia)->format('d/m/Y') }}</td>
                            <td>{{ $transferencia->CodArticulo }}</td>
                            <td>{{ $transferencia->NomArticulo }}</td>
                            <td>{{ $transferencia->CantidadTrasferencia }}</td>
                            <td>
                                @if ($transferencia->Subir == 1)
                                    <span class="tags-green" title="En linea"> @include('components.icons.cloud-check') </span>
                                @else
                                    <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash') </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $transferencias])
        </div>
    </div>
@endsection
