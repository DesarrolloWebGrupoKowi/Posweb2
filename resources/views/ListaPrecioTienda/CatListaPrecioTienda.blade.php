@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Listas de Precios Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <form id="formListaP" action="/CatListaPrecioTienda">
            <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
                @include('components.title', ['titulo' => 'Catálogo de Lista de Precios por Tienda'])
                <div class="d-flex align-items-center justify-content-end">
                    <div class="form-group" style="min-width: 300px">
                        <label class="fw-bold text-secondary">Selecciona una tienda</label>
                        <select class="form-select" name="filtroIdTienda" onchange="mostrarListas()">
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

            <div class="row">
                <div class="col-6">
                    <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                            <button class="btn btn-sm btn-danger" onclick="removerLista()">
                                <i class="fa fa-remove"></i> Remover
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                            <button class="btn btn-sm btn-dark" onclick="agregarLista()">
                                <i class="fa fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
