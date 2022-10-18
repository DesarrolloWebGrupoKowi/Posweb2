<!-- Modal Notificaciones -->
<div class="modal fade" data-bs-backdrop="static" id="Notificaciones" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog {!! $notificaciones > 0 ? 'modal-fullscreen' : 'modal-lg' !!}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Movimientos Cliente Cloud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach ($movimientos as $movimiento)
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-center"><h6>{{ $movimiento->NomMovimientoCliente }}</h6></div>
                                <div class="card-body">
                                    @if ($movimiento->Notificaciones->count() == 0)
                                        <ul>
                                            <li> <i style="color: red" class="fa fa-exclamation-triangle"></i> No Hay
                                                Notificaciones!</li>
                                        </ul>
                                    @else
                                        @foreach ($movimiento->Notificaciones as $nMovimiento)
                                            <div class="row">
                                                <div class="col-10">
                                                    <h5 class="card-title">{{ $nMovimiento->NomCliente }}
                                                    </h5>
                                                </div>
                                                <div class="col-2">
                                                    <form action="/GuardarCheckClienteEditado">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="chkIdNotificacion[]"
                                                                value="{{ $nMovimiento->IdDatNotificacionesClienteCloud }}"
                                                                id="flexCheckDefault">
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Listo
                                                            </label>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if ($movimiento->IdMovimiento == 1)
                                                <div class="col-auto">
                                                    <ul>
                                                        <li><strong>Tienda: </strong> {{ $nMovimiento->NomTienda }}</li>
                                                        <li><strong>Id Cliente: </strong>
                                                            {{ $nMovimiento->IdClienteCloud }}</li>
                                                        <li><strong>RFC: </strong> {{ $nMovimiento->RFC }}
                                                        </li>
                                                        <ul>
                                                            @if (!empty($nMovimiento->Calle))
                                                                <li><strong>Calle: </strong>
                                                                    {{ $nMovimiento->Calle }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->NumExt))
                                                                <li><strong>Número Exterior: </strong>
                                                                    {{ $nMovimiento->NumExt }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->NumInt))
                                                                <li><strong>Número Interior: </strong>
                                                                    {{ $nMovimiento->NumInt }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->Colonia))
                                                                <li><strong>Colonia: </strong>
                                                                    {{ $nMovimiento->Colonia }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->Ciudad))
                                                                <li><strong>Ciudad: </strong>
                                                                    {{ $nMovimiento->Ciudad }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->Municipio))
                                                                <li><strong>Municipio: </strong>
                                                                    {{ $nMovimiento->Municipio }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->Estado))
                                                                <li><strong>Estado: </strong>
                                                                    {{ $nMovimiento->Estado }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->CodigoPostal))
                                                                <li><strong>Codigo Postal: </strong>
                                                                    {{ $nMovimiento->CodigoPostal }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->Email))
                                                                <li><strong>Email: </strong>
                                                                    {{ $nMovimiento->Email }}</li>
                                                            @endif
                                                        </ul>
                                                    </ul>
                                                </div>
                                                @else
                                                <div class="col-auto">
                                                    <ul>
                                                        <li><strong>Tienda: </strong> {{ $nMovimiento->NomTienda }}</li>
                                                        <li><strong>RFC: </strong> {{ $nMovimiento->RFC }}</li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                @if ($notificaciones > 0)
                    <button class="btn btn-warning">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                @endif
            </div>
            </form>
        </div>
    </div>
</div>
