<div class="modal fade" data-bs-backdrop="static" id="ModalSolicitudFe{{ $tVenta->IdDatEncabezado}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-id-badge"></i> Solicitud de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($tVenta->SolicitudFactura as $factura)
                <h5 class="d-flex justify-content-center">{{ $factura->NomCliente }}</h5>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Fecha Solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ strftime("%d %B %Y, %H:%M", strtotime($factura->FechaSolicitud)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>