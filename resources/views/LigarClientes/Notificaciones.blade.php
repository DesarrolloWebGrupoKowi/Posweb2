<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="Notificaciones" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog {!! $notificaciones > 0 ? 'modal-fullscreen' : 'modal-lg' !!}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Movimientos Cliente Cloud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($movimientos as $movimiento)
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ $movimiento->NomMovimientoCliente }}</h6>
                                </div>
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
                                                    <h5 class="card-title">{{ $nMovimiento->PivotCliente->NomCliente }}
                                                    </h5>
                                                </div>
                                                <div class="col-2">
                                                    <form action="/GuardarCheckClienteEditado">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="chkIdNotificacion[]"
                                                                value="{{ $nMovimiento->PivotCliente->IdDatNotificacionesClienteCloud }}"
                                                                id="flexCheckDefault">
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Listo
                                                            </label>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul>
                                                        <li><strong>Tienda: </strong> {{ $nMovimiento->NomTienda }}</li>
                                                        <li><strong>Id Cliente: </strong>
                                                            {{ $nMovimiento->PivotCliente->IdClienteCloud }}</li>
                                                        <li><strong>RFC: </strong> {{ $nMovimiento->PivotCliente->RFC }}
                                                        </li>
                                                        <ul>
                                                            @if (!empty($nMovimiento->PivotCliente->Calle))
                                                                <li><strong>Calle: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Calle }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->NumExt))
                                                                <li><strong>Número Exterior: </strong>
                                                                    {{ $nMovimiento->PivotCliente->NumExt }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->NumInt))
                                                                <li><strong>Número Interior: </strong>
                                                                    {{ $nMovimiento->PivotCliente->NumInt }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->Colonia))
                                                                <li><strong>Colonia: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Colonia }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->Ciudad))
                                                                <li><strong>Ciudad: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Ciudad }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->Municipio))
                                                                <li><strong>Municipio: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Municipio }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->Estado))
                                                                <li><strong>Estado: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Estado }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->CodigoPostal))
                                                                <li><strong>Codigo Postal: </strong>
                                                                    {{ $nMovimiento->PivotCliente->CodigoPostal }}</li>
                                                            @endif
                                                            @if (!empty($nMovimiento->PivotCliente->Email))
                                                                <li><strong>Email: </strong>
                                                                    {{ $nMovimiento->PivotCliente->Email }}</li>
                                                            @endif
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
