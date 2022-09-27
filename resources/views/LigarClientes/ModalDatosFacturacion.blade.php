<div class="modal hide fade" data-bs-backdrop="static" id="ModalDatosFacturacion{{$cliente->IdSolicitudFactura}}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">{{ $cliente->NomCliente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>RFC:</strong> {{ $cliente->RFC }}</li>
                    <li class="list-group-item"><strong>Calle:</strong> {{ $cliente->Calle }}</li>
                    <li class="list-group-item"><strong>Número Exterior:</strong> {{ $cliente->NumExt }}</li>
                    <li class="list-group-item"><strong>Número Interior:</strong> {{ $cliente->NumInt }}</li>
                    <li class="list-group-item"><strong>Código Postal:</strong> {{ $cliente->CodigoPostal }}</li>
                    <li class="list-group-item"><strong>Colonia:</strong> {{ $cliente->Colonia }}</li>
                    <li class="list-group-item"><strong>Ciudad:</strong> {{ $cliente->Ciudad }}</li>
                    <li class="list-group-item"><strong>Municipio:</strong> {{ $cliente->Municipio }}</li>
                    <li class="list-group-item"><strong>Estado:</strong> {{ $cliente->Estado }}</li>
                    <li class="list-group-item"><strong>Teléfono:</strong> {{ $cliente->Telefono }}</li>
                    <li class="list-group-item"><strong>Correo:</strong> {{ $cliente->Email }}</li>
                    <li class="list-group-item"><strong>Tipo de Pago:</strong> {{ $cliente->NomTipoPago }}</li>
                    @if (!empty($cliente->NomBanco))
                    <li class="list-group-item"><strong>Banco:</strong> {{ $cliente->NomBanco }}</li>
                    <li class="list-group-item"><strong>Cuenta:</strong> {{ $cliente->NumTarjeta }}</li>
                    @endif
                    <li class="list-group-item"><strong>Uso del CFDI:</strong> {{ $cliente->NomCFDI }} ({{
                        $cliente->UsoCFDI }})</li>
                </ul>
            </div>
            <div class="modal-footer">
                <form action="/LigarCliente">
                    <input type="hidden" name="idSolicitudFactura" value="{{ $cliente->IdSolicitudFactura }}">
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-bookmark"></i> Ligar Cliente
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>