@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Verificar Solicitud de Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Verificar Solicitud de Factura - ' . $nomCliente->NomCliente,
                    'options' => [
                        [
                            'name' => 'Solicitud Factura',
                            'value' =>
                                '/SolicitudFactura?rfcCliente=' . $rfcCliente . '&numTicket=' . $ticket->IdTicket,
                        ],
                    ],
                ])
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <form action="/GuardarSolicitudFactura" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="rfcCliente" value="{{ $rfcCliente }}">
            <input type="hidden" name="bill_To" value="{{ $bill_To }}">
            <div class="card border-0 p-4" style="border-radius: 10px">
                <div class="d-flex justify-content-end gap-4 mb-3">
                    <div class="col-2">
                        <div class="input-group">
                            <span class="input-group-text">Ticket</span>
                            <input type="text" class="form-control bg-white" name="numTicket"
                                value="{{ $ticket->IdTicket }}" readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <span class="input-group-text">Importe</span>
                            <input type="text" class="form-control bg-white"
                                value="{{ number_format($ticket->ImporteVenta, 2) }}" readonly>
                        </div>
                    </div>
                </div>

                @if ($banderaMultiPagoFact == 0)
                    <div class="row d-flex justify-content-center">
                        <div class="col-6">
                            <h5 class="titulo card p-1">Seleccione Metódos de Pago a Facturar</h5>
                        </div>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <table style="width: 65%" class="table table-responsive table-striped shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tipo de Pago</th>
                                    <th>Importe</th>
                                    <th>Dispnible P/ Facturar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tiposPagoTicket as $tipoPagoTicket)
                                    <tr>
                                        <td>{{ $tipoPagoTicket->NomTipoPago }}</td>
                                        <td>{{ number_format($tipoPagoTicket->ImporteArticulo, 2) }}</td>
                                        <td>
                                            @if ($tipoPagoTicket->IdSolicitudFactura == null)
                                                <input class="form-check-input mt-0" type="checkbox" id="checkPagoFac"
                                                    name="chkTipoPagoTicket[]" value="{{ $tipoPagoTicket->IdTipoPago }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-3">
                        <h5 class="titulo text-center p-1 mb-3">Datos del Cliente</h5>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-8">
                        <label for="">Calle</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Calle) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" id="editCalle"
                                    name="chkEdit[]" value="calle">
                            </div>
                            <input type="text" name="calle" id="calle" class="form-control bg-white"
                                value="{{ $cliente->Calle }}" required {!! !empty($cliente->Calle) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="">Número Exterior</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->NumExt) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" id="editNumExt"
                                    name="chkEdit[]" value="numExt">
                            </div>
                            <input type="text" id="numExt" name="numExt" class="form-control bg-white"
                                value="{{ $cliente->NumExt }}" required {!! !empty($cliente->NumExt) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="">Número Interior</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="editNumInt" name="chkEdit[]"
                                    value="numInt">
                            </div>
                            <input type="text" id="numInt" name="numInt" class="form-control bg-white"
                                value="{{ $cliente->NumInt }}" {!! !empty($cliente->NumInt) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="">Colonia</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Colonia) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editColonia" name="chkEdit[]" value="colonia">
                            </div>
                            <input type="text" id="colonia" name="colonia" class="form-control bg-white"
                                value="{{ $cliente->Colonia }}" required {!! !empty($cliente->Colonia) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Ciudad</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Ciudad) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editCiudad" name="chkEdit[]" value="ciudad">
                            </div>
                            <input type="text" id="ciudad" name="ciudad" class="form-control bg-white"
                                value="{{ $cliente->Ciudad }}" required {!! !empty($cliente->Ciudad) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Municipio</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Municipio) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editMunicipio" name="chkEdit[]" value="municipio">
                            </div>
                            <input type="text" id="municipio" name="municipio" class="form-control bg-white"
                                value="{{ $cliente->Municipio }}" required {!! !empty($cliente->Municipio) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="">Estado</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Estado) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editEstado" name="chkEdit[]" value="estado">
                            </div>
                            <input type="text" id="estado" name="estado" class="form-control bg-white"
                                value="{{ $cliente->Estado }}" required {!! !empty($cliente->Estado) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Código Postal</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->CodigoPostal) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editCodigoPostal" name="chkEdit[]" value="codigoPostal">
                            </div>
                            <input type="text" id="codigoPostal" name="codigoPostal" class="form-control bg-white"
                                value="{{ $cliente->CodigoPostal }}" required {!! !empty($cliente->CodigoPostal) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                    <div class="col-4">
                        <label style="color: {!! empty($cliente->Email) ? 'red' : '' !!}" for="">
                            {!! empty($cliente->Email) ? 'Agregar Correo' : 'Correo' !!}</label>
                        @if (empty($cliente->Email))
                            <i style="color: red" class="fa fa-exclamation-triangle"></i>
                        @else
                        @endif
                        <div class="input-group">
                            <div class="input-group-text">
                                <input {!! empty($cliente->Email) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox"
                                    id="editEmail" name="chkEdit[]" value="email">
                            </div>
                            <input type="text" id="email" name="email" class="form-control bg-white"
                                value="{{ empty($cliente->Email) ? old('email') : $cliente->Email }}" required
                                {!! !empty($cliente->Email) ? 'readonly' : '' !!}>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="">Telefono</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="editTelefono" name="chkEdit[]"
                                    value="telefono">
                            </div>
                            <input type="tel" id="telefono" name="telefono" class="form-control bg-white"
                                value="{{ $cliente->Telefono }}" minlength="10" maxlength="10" {!! !empty($cliente->Telefono) ? 'readonly' : '' !!}
                                pattern="[0-9]{10}">
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Constancia Situación Fiscal</label>
                        <input type="file" class="form-control" name="cSituacionFiscal">
                    </div>
                    <div class="col-4">
                        <label for="">Uso del CFDI</label>
                        <select class="form-select" name="cfdi" id="cfdi" required>
                            @foreach ($usosCFDI as $usoCFDI)
                                <option value="{{ $usoCFDI->UsoCFDI }}">{{ $usoCFDI->NomCFDI }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="">Método de pago</label>
                        <select class="form-select" name="metodopag" id="metodopag" required>
                            @foreach ($metodosPago as $metodopago)
                                <option value="{{ $metodopago->MetPago }}">{{ $metodopago->Descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center mb-3 mt-3">
                <div class="col-2">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarSolicitudCliente">
                        <i class="fa fa-save"></i> Guardar Solicitud
                    </button>
                </div>
            </div>
            @include('SolicitudFactura.ModalConfirmarSolicitudCliente')
        </form>
    </div>

    <script>
        const editCalle = document.getElementById('editCalle');
        editCalle.addEventListener('click', function() {
            const calle = document.getElementById('calle');
            if (calle.readOnly) {
                calle.readOnly = false;
            } else {
                calle.value = "<?php echo $cliente->Calle; ?>";
                calle.readOnly = true;
            }
        })

        const editNumExt = document.getElementById('editNumExt');
        editNumExt.addEventListener('click', function() {
            const numExt = document.getElementById('numExt');
            if (numExt.readOnly) {
                numExt.readOnly = false;
            } else {
                numExt.value = '<?php echo $cliente->NumExt; ?>';
                numExt.readOnly = true;
            }
        })

        const editNumInt = document.getElementById('editNumInt');
        editNumInt.addEventListener('click', function() {
            const numInt = document.getElementById('numInt');
            if (numInt.readOnly) {
                numInt.readOnly = false;
            } else {
                numInt.value = '<?php echo $cliente->NumInt; ?>';
                numInt.readOnly = true;
            }
        })

        const editColonia = document.getElementById('editColonia');
        editColonia.addEventListener('click', function() {
            const colonia = document.getElementById('colonia');
            if (colonia.readOnly) {
                colonia.readOnly = false;
            } else {
                colonia.value = '<?php echo $cliente->Colonia; ?>';
                colonia.readOnly = true;
            }
        })

        const editCiudad = document.getElementById('editCiudad');
        editCiudad.addEventListener('click', function() {
            const ciudad = document.getElementById('ciudad');
            if (ciudad.readOnly) {
                ciudad.readOnly = false;
            } else {
                ciudad.value = '<?php echo $cliente->Ciudad; ?>';
                ciudad.readOnly = true;
            }
        })

        const editMunicipio = document.getElementById('editMunicipio');
        editMunicipio.addEventListener('click', function() {
            const municipio = document.getElementById('municipio');
            if (municipio.readOnly) {
                municipio.readOnly = false;
            } else {
                municipio.value = '<?php echo $cliente->Municipio; ?>';
                municipio.readOnly = true;
            }
        })

        const editEstado = document.getElementById('editEstado');
        editEstado.addEventListener('click', function() {
            const estado = document.getElementById('estado');
            if (estado.readOnly) {
                estado.readOnly = false;
            } else {
                estado.value = '<?php echo $cliente->Estado; ?>';
                estado.readOnly = true;
            }
        })

        const editCodigoPostal = document.getElementById('editCodigoPostal');
        editCodigoPostal.addEventListener('click', function() {
            const codigoPostal = document.getElementById('codigoPostal');
            if (codigoPostal.readOnly) {
                codigoPostal.readOnly = false;
            } else {
                codigoPostal.value = '<?php echo $cliente->CodigoPostal; ?>';
                codigoPostal.readOnly = true;
            }
        })

        const editEmail = document.getElementById('editEmail');
        editEmail.addEventListener('click', function() {
            const email = document.getElementById('email');
            if (email.readOnly) {
                email.readOnly = false;
            } else {
                email.value = '<?php echo $cliente->Email; ?>';
                email.readOnly = true;
            }
        })

        const editTelefono = document.getElementById('editTelefono');
        editTelefono.addEventListener('click', function() {
            const telefono = document.getElementById('telefono');
            if (telefono.readOnly) {
                telefono.readOnly = false;
            } else {
                telefono.value = '<?php echo $cliente->Telefono; ?>';
                telefono.readOnly = true;
            }
        })
    </script>
@endsection
