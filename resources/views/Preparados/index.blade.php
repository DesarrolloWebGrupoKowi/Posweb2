@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Creación de preparados')
@section('dashboardWidth', 'width-95')
@section('contenido')

    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', [
                'titulo' => 'Creación de Preparados',
                'options' => [['name' => 'Preparados', 'value' => '/AsignarPreparados']],
            ])
            <div>
                <button type="button" class="btn btn-primary" role="tooltip" title="Agregar preparado" data-bs-toggle="modal"
                    data-bs-target="#ModalAgregarPreparado">
                    <small><i class="fa fa-plus-circle pe-1"></i> Agregar preparado</small>
                </button>
                @if ($idPreparado)
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar detalle"
                        data-bs-toggle="modal" data-bs-target="#ModalAgregarDetalle">
                        <i class="fa fa-plus-circle pe-1"></i> Agregar detalle
                    </button>
                @endif
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-6">
                    <div style="height: 70vh">
                        <div class="content-table content-table-full card p-4 pt-3" style="border-radius: 20px">
                            <h5 class="pb-1" style="text-align: center">Preparados en Proceso</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Costo</th>
                                        <th class="text-center">Ver</th>
                                        <th class="rounded-end text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($preparados) == 0)
                                        <tr>
                                            <td colspan="6">No se encuentra ningun preparado en proceso</td>
                                        </tr>
                                    @else
                                        @foreach ($preparados as $preparado)
                                            <a>
                                                <tr
                                                    class="{{ $preparado->IdPreparado == $idPreparado ? 'table-active' : '' }}">
                                                    <td>{{ $preparado->Nombre }}</td>
                                                    <td>{{ $preparado->Cantidad }}</td>
                                                    <td>${{ $preparado->Total == 0 ? '0.00' : number_format($preparado->Total, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <form class="d-inline-block" action="/Preparados">
                                                            <input type="hidden" name="idPreparado"
                                                                value="{{ $preparado->IdPreparado }}">
                                                            <button class="btn btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#ModalEliminar"><span
                                                                    class="material-icons">visibility</span></button>
                                                        </form>
                                                    </td>
                                                    <td class="bg-opacity-75 d-flex justify-content-center">
                                                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#ModalOpciones{{ $preparado->IdPreparado }}"><i
                                                                class="fa fa-bars"></i>
                                                        </button>
                                                        @include('Preparados.ModalOpciones')
                                                        @include('Preparados.ModalEliminarPreparado')
                                                        @include('Preparados.ModalEditar')
                                                        @include('Preparados.ModalEnviar')
                                                    </td>
                                                </tr>
                                            </a>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div style="height: 70vh">
                        <div class="content-table content-table-full card p-3" style="border-radius: 20px">
                            <h5 class="pb-1" style="text-align: center">Detalle de Preparado</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Codigo</th>
                                        <th>Nombre</th>
                                        <th>Lista de precio</th>
                                        <th>Cantidad</th>
                                        <th>Formula</th>
                                        <th>Importe</th>
                                        <th class="rounded-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$idPreparado)
                                        <tr>
                                            <td colspan="7">Selecciona un preparado</td>
                                        </tr>
                                    @elseif (count($detallePreparado) == 0)
                                        <tr>
                                            <td colspan="7">No ahi productos agregados al preparado</td>
                                        </tr>
                                    @endif
                                    @foreach ($detallePreparado as $detalle)
                                        <tr>
                                            <td>{{ $detalle->CodArticulo }}</td>
                                            <td>{{ $detalle->NomArticulo }}</td>
                                            <td>
                                                <select name="IdListaPrecio" id="IdListaPrecio" class="form-select">
                                                    @foreach ($listaPrecios as $lista)
                                                        <option value="{{ $lista->IdListaPrecio }}"
                                                            {{ $lista->IdListaPrecio == $detalle->IDLISTAPRECIO ? 'selected' : '' }}>
                                                            {{ $lista->NomListaPrecio }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ number_format($detalle->CantidadPaquete, 3, '.', '.') }}</td>
                                            <td>{{ number_format($detalle->CantidadFormula, 3, '.', '.') }}</td>
                                            <td>${{ number_format($detalle->PrecioArticulo * $detalle->CantidadFormula, 2, '.', '.') }}
                                            </td>
                                            <td>
                                                <form class="d-inline-block"
                                                    action="/EliminarArticuloDePreparados/{{ $detalle->IdDatPreparado }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm">
                                                        <span class="material-icons eliminar">delete_forever</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td colspan="2"><b>Costo preparado:</b></td>
                                        <td>${{ $total == 0 ? '0.00' : number_format($total, 2, '.', '.') }}</td>
                                        <td> </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form-update" action="/EditarListaPreciosPreparados/{{ $idPreparado }}" method="POST">
                @csrf
                <input type="hidden" name="IdListaPrecio" value="5">
            </form>
        </div>
        @include('Preparados.ModalAgregarPreparado')
        @include('Preparados.ModalAgregarDetalle')
    </div>

    <script>
        document.addEventListener('change', e => {
            if (e.target.matches('.form-select')) {
                document.querySelectorAll('.form-select').forEach(element => {
                    element.value = e.target.value;
                });
                let form = document.getElementById('form-update');
                form.IdListaPrecio.value = e.target.value;
                form.submit();
            }
        })
    </script>

@endsection
