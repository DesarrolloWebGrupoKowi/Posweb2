@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Lista de Promociones')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="content-table content-table-full card border-0 p-4"
            style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Lista de Promociones'])
            </div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card border-0 p-4"
            style="border-radius: 10px">
            {{-- <form class="d-flex align-items-center justify-content-end gap-4 pb-2"
                action="/DetallePrecios"
                method="get">
                <div class="d-flex align-items-center gap-2">
                    <label for="txtFiltro"
                        class="text-secondary"
                        style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded"
                        style="line-height: 18px"
                        type="text"
                        name="txtFiltro"
                        id="txtFiltro"
                        value="{{ $txtFiltro }}"
                        autofocus>
                </div>
            </form> --}}

            {{-- @if ($tickets->where('Subir', 0)->count() > 0) --}}
            <div class="d-flex justify-content-end align-items-center mb-3">
                <form action="/DetallePromociones/update"
                    method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit"
                        class="btn-loading btn btn-sm"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                        <span id="buttonIcon">
                            @include('components.icons.upload')
                        </span>
                        Actualizar promociones
                    </button>
                </form>
            </div>
            {{-- @endif --}}


            <table>
                <thead class="table-head">
                    <tr>
                        <th>PLU</th>
                        <th>Código</th>
                        <th>Nombre artículo</th>
                        <th>Precio descuento</th>
                        <th>Tienda</th>
                        <th>Plaza</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Fecha desactivar</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle">
                    @include('components.table-empty', ['items' => $descuentos, 'colspan' => 9])
                    @foreach ($descuentos as $item)
                        <tr>
                            <td>{{ $item->CodEtiqueta }}</td>
                            <td>{{ $item->CodArticulo }}</td>
                            <td>{{ $item->NomArticulo }}</td>
                            <td>{{ number_format($item->PrecioDescuento, 2) }}</td>
                            <td>{{ $item->NomTienda ?? '-' }}</td>
                            <td>{{ $item->NomPlaza ?? '-' }}</td>
                            <td>
                                {{ $item->FechaInicio ? strftime('%d %B %Y', strtotime($item->FechaInicio)) : '-' }}
                            </td>
                            <td>
                                {{ $item->FechaFin ? strftime('%d %B %Y', strtotime($item->FechaFin)) : '-' }}
                            </td>
                            <td>
                                {{ $item->FechaDesactivar ? strftime('%d %B %Y', strtotime($item->FechaDesactivar)) : '-' }}
                            </td>
                            <td>
                                @php
                                    $hoy = date('Y-m-d');
                                    $inactivo = $item->StatusDescuento || $item->Status;
                                @endphp
                                @if ($inactivo)
                                    <span class="tags-red">Deshabilitado</span>
                                @elseif ($item->FechaFin < $hoy)
                                    <span class="tags-blue">Expirado</span>
                                @else
                                    <span class="tags-green">Activo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $descuentos])
        </div>
    </div>
@endsection
