<!-- Modal Agregar-->
<div class="modal fade" id="ModalShowDetails{{ $asignado->IdDatAsignacionPreparado }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle del Preparado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Cantidad de preparado</th>
                            <th>Cantidad formula</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($asignado->Detalle) == 0)
                            <tr>
                                <td colspan="4">No ahi productos agregados al preparado</td>
                            </tr>
                        @endif
                        @foreach ($asignado->Detalle as $detalle)
                            <tr>
                                <td>{{ $detalle->CodArticulo }}</td>
                                <td>{{ $detalle->NomArticulo }}</td>
                                <td>{{ $detalle->CantidadPaquete }}</td>
                                <td>{{ number_format($detalle->CantidadFormula, 3, '.', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
