<div class="modal fade" id="ModalSolicitudFe{{ $ticket->IdTicket }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Solicitud de Factura </h5>
            </div>
            <div class="modal-body">
                @foreach ($ticket->SolicitudFactura as $factura)
                    <h5 class="d-flex justify-content-center">{{ $factura->NomCliente }}</h5>
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha Solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($factura->FechaSolicitud)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
            </div>
        </div>
    </div>
</div>
