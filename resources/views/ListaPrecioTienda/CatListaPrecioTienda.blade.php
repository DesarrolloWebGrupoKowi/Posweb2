@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Listas de Precios Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <form id="formListaP" action="/CatListaPrecioTienda">
        <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">
            <div class="card border-0 p-4" style="border-radius: 10px">
                <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                    @include('components.title', ['titulo' => 'Catálogo de Lista de Precios por Tienda'])
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="form-group" style="min-width: 300px">
                            <select class="form-select rounded" style="line-height: 18px" name="filtroIdTienda"
                                onchange="mostrarListas()" autofocus>
                                <option {!! $idTienda != '' ? 'disabled' : '' !!} value="">Seleccione Tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    @include('Alertas.Alertas')
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
                        <table>
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Lista de Precio ({{ count($listasPrecioTienda) }})</th>
                                    <th class="rounded-end">Seleccionar</th>
                            </thead>
                            <tbody>
                                @foreach ($listasPrecioTienda as $listaPrecioTienda)
                                    <tr>
                                        <td>{{ $listaPrecioTienda->NomListaPrecio }}</td>
                                        <td class="d-flex align-items-center"><input class="form-check-input"
                                                type="checkbox" name="chkRemover[]"
                                                value="{{ $listaPrecioTienda->IdListaPrecio }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pt-2 d-flex justify-content-end">
                            <button class="btn btn-sm btn-danger" onclick="removerLista()"
                                {{ count($listasPrecioTienda) == 0 ? 'disabled' : '' }}>
                                Remover @include('components.icons.delete')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
                        <table>
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Lista de Precio ({{ count($listasPrecio) }})</th>
                                    <th class="rounded-end">Seleccionar</th>
                            </thead>
                            <tbody>
                                @foreach ($listasPrecio as $listaPrecio)
                                    <tr>
                                        <td>{{ $listaPrecio->NomListaPrecio }}</td>
                                        <td class="d-flex align-items-center"><input class="form-check-input"
                                                type="checkbox" name="chkAgregar[]"
                                                value="{{ $listaPrecio->IdListaPrecio }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pt-2 d-flex justify-content-end">
                            <button class="btn btn-sm btn-warning" onclick="agregarLista()"
                                {{ count($listasPrecio) == 0 ? 'disabled' : null }}>
                                Agregar @include('components.icons.plus-circle')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
