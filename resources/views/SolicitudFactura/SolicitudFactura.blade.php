@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitar Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitar Factura - ' . $tienda->NomTienda])
                <a href="/VerSolicitudesFactura" class="btn btn-dark">
                    Historial Facturación @include('components.icons.text-file')
                </a>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        @include('SolicitudFactura.ModalClienteConfirm')
        <input type="hidden" id="clienteCount" value="{{ $cliente->count() }}">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <form action="/SolicitudFactura">
                <div class="row">
                    <div class="col-5">
                        <div class="input-group">
                            <span class="input-group-text">RFC del Cliente</span>
                            <input style="text-align: center" type="text" class="form-control" name="rfcCliente"
                                placeholder="Escribe" value="{{ $rfcCliente }}" maxlength="13" autofocus required>
                            <button class="btn btn-warning" type="submit"><i class="fa fa-search"></i>
                                Buscar</button>
                        </div>
                    </div>
                    @if ($cliente->count() > 0)
                        <div style="display: {!! $cliente->count() > 0 &&
                        !empty($ticket->ImporteVenta) &&
                        $ticket->StatusVenta == 0 &&
                        $auxTicketFacturado > 0
                            ? 'block'
                            : 'none' !!}" id="divNomCliente" class="col-5">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white">{{ $nomCliente->NomCliente }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>


        <div id="divCliente" style="display: {!! !empty($numTicket) ? 'block' : 'none' !!}; border-radius: 10px"
            class="content-table content-table-full card border-0 p-4">
            <div class="container">
                @if ($cliente->count() > 0)
                    {{-- <div class="mt-3"> --}}
                    <div class="row d-flex justify-content-center">
                        <div class="col-3">
                            <h5 style="display: {!! !empty($ticket->ImporteVenta) && $cliente->count() > 0 && $auxTicketFacturado > 0 ? 'block' : 'none' !!}" id="tituloClienteExistente" class="titulo">
                                Solicitud de Factura</h5>
                        </div>
                    </div>
                    {{-- </div> --}}
                @elseif(!empty($rfcCliente) && $cliente->count() == 0)
                    <div class="row d-flex justify-content-center">
                        <div class="col-3">
                            <h5 style="display: {!! !empty($ticket->ImporteVenta) && $cliente->count() == 0 && $auxTicketFacturado > 0 ? 'block' : 'none' !!}" id="tituloNuevoCliente" class="titulo">
                                Agregar Nuevo Cliente</h5>
                        </div>
                    </div>
                @endif
            </div>
            <form action="/SolicitudFactura">
                <input type="hidden" id="rfcCliente" name="rfcCliente" value="{{ $rfcCliente }}">
                <div class="row">
                    @if (!empty($rfcCliente))
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text"># de Ticket</span>
                                <input type="text" class="form-control" id="numTicket" name="numTicket" placeholder="#"
                                    value="{{ $numTicket }}" required>
                                <button class="btn btn-warning" type="submit" id="button-addon1"><i
                                        class="fa fa-search"></i>
                                    Buscar</button>
                            </div>
                        </div>
                        <div style="display: {!! empty($numTicket) ? 'none' : 'block' !!}" class="col-3">
                            @if (empty($ticket))
                                <div class="input-group mb-3">
                                    <span style="color: red" class="input-group-text fw-bold bg-white"><i
                                            class="fa fa-exclamation-triangle"></i>&nbsp;No se Encontro el Ticket&nbsp;<i
                                            class="fa fa-exclamation-triangle"></i></span>
                                </div>
                            @elseif ($ticket->StatusVenta == 1)
                                <div class="input-group mb-3">
                                    <span style="color: red" class="input-group-text fw-bold bg-white"><i
                                            class="fa fa-exclamation-triangle"></i>&nbsp;Ticket Cancelado&nbsp;<i
                                            class="fa fa-exclamation-triangle"></i></span>
                                </div>
                            @elseif($auxTicketFacturado == 0)
                                <div class="input-group mb-3">
                                    <span class="input-group-text fw-bold bg-white">
                                        <i style="color: rgb(10, 209, 10)" class="fa fa-check-square"></i>&nbsp;Ticket
                                        Facturado&nbsp;
                                        <i style="color: rgb(10, 209, 10)" class="fa fa-check-square"></i></span>
                                </div>
                            @else
                                <div class="input-group mb-3">
                                    <span class="input-group-text fw-bold btn-warning"><i
                                            class="fa fa-usd"></i>&nbsp;Importe</span>
                                    <input style="text-align: center;" type="text"
                                        class="form-control border-warning bg-white"
                                        value="$ {{ number_format($ticket->ImporteVenta, 2) }}" readonly>
                                </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text fw-bold btn-success"><i
                                        class="fa fa-id-badge"></i>&nbsp;RFC</span>
                                <input style="text-align: center;" type="text"
                                    class="form-control border-warning bg-white" value="{{ $rfcCliente }}" readonly>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            </form>
            @if (!empty($ticket->ImporteVenta) && $cliente->count() == 0 && $auxTicketFacturado > 0)
                <form action="/GuardarSolicitudFacturaClienteNuevo" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($banderaMultiPagoFact == 0 && !empty($ticket->ImporteVenta) && $cliente->count() == 0)
                        <div class="table-responsive mb-3">
                            <table style="text-align: center" class="table table-responsive table-striped">
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
                                                    <input class="form-check-input mt-0" type="checkbox"
                                                        id="checkPagoFac" name="chkTipoPagoTicket[]"
                                                        value="{{ $tipoPagoTicket->IdTipoPago }}">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <input type="hidden" name="numTicket" value="{{ $numTicket }}">
                    <input type="hidden" name="rfcCliente" value="{{ $rfcCliente }}">
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="">Tipo de Persona</label>
                            <select class="form-select" name="tipoPersona" id="tipoPersona" required>
                                <option value="PERSON">FISICA</option>
                                <option value="ORGANIZATION">MORAL</option>
                                {{-- <option value="PERSON">PERSON</option>
                                <option value="ORGANIZATION">ORGANIZATION</option> --}}
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="">Nombre</label>
                            <input type="text" class="form-control" name="nomCliente" onkeyup="mayusculas(this)"
                                required placeholder="Escribe el nombre">
                        </div>
                        <div class="col-4">
                            <label for="">Calle</label>
                            <input type="text" class="form-control" name="calle" onkeyup="mayusculas(this)"
                                required placeholder="Escribe la calle">
                        </div>
                        <div class="col-1">
                            <label for="">Número Ext</label>
                            <input type="text" class="form-control" name="numExt" required
                                placeholder="Escribe el númeor exterior">
                        </div>
                        <div class="col-1">
                            <label for="">Número Int</label>
                            <input type="text" class="form-control" name="numInt" required
                                placeholder="Escribe el número interior">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="">Colonia</label>
                            <input type="text" class="form-control" name="colonia" onkeyup="mayusculas(this)"
                                required placeholder="Escribe el nombre de la colonia">
                        </div>
                        <div class="col-3">
                            <label for="">Ciudad</label>
                            <input type="text" class="form-control" name="ciudad" required
                                placeholder="Escribe el nombre de la ciudad">
                        </div>
                        <div class="col-3">
                            <label for="">Municipio</label>
                            <input type="text" class="form-control" name="municipio" onkeyup="mayusculas(this)"
                                required placeholder="Escribe el municipio">
                        </div>
                        <div class="col-3">
                            <label for="">Estado</label>
                            <input type="text" class="form-control" name="estado" required
                                placeholder="Escribe el nombre del estado">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="">Código Postal</label>
                            <input type="text" class="form-control" name="codigoPostal" required
                                placeholder="Escribe el código postal">
                        </div>
                        <div class="col-3">
                            <label for="">Correo</label>
                            <input type="email" class="form-control" name="correo" required
                                placeholder="Escribe el correo">
                        </div>
                        <div class="col-3">
                            <label for="">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" minlength="10" maxlength="10"
                                required placeholder="Escribe el teléfono">
                        </div>
                        <div class="col-3">
                            <label for="">Uso del CFDI</label>
                            <select name="cfdi" id="cfdi" class="form-select" required>
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
                        <div class="col-4">
                            <label for="">Constancia Situación Fiscal</label>
                            <input type="file" class="form-control" name="cSituacionFiscal" required>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-2">
                            <button type="button" data-bs-toggle="modal"
                                data-bs-target="#ModalConfirmarSolicitudCliente" class="btn btn-warning">
                                <i class="fa fa-save"></i> Guardar Solicitud
                            </button>
                        </div>
                    </div>
                    @include('SolicitudFactura.ModalConfirmarSolicitudCliente')
                </form>
            @elseif($cliente->count() > 0 && !empty($ticket->ImporteVenta) && $ticket->StatusVenta == 0 && $auxTicketFacturado > 0)
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Dirección</th>
                            <th>Correo</th>
                            <th class="rounded-end">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cliente as $index => $dCliente)
                            <tr>
                                <td>{{ $dCliente->Calle }}</td>
                                <td>{{ $dCliente->Email }}</td>
                                <td>
                                    <a href="/VerificarSolicitudFactura/{{ $numTicket }}/{{ $rfcCliente }}/{{ $dCliente->Bill_To }}/{{ empty($dCliente->Email) ? 'NoTieneCorreo' : $dCliente->Email }}"
                                        target="_blank" class="btn-table text-dark" style="width: fit-content"
                                        title="Verificar solicitud">
                                        @include('components.icons.list')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>




    <script>
        $(document).ready(function() {
            const rfcCliente = document.getElementById('rfcCliente');
            const clienteCount = document.getElementById('clienteCount');
            const btnNuevoCliente = document.getElementById('btnNuevoCliente');
            const numTicket = document.getElementById('numTicket');
            const tituloNuevoCliente = document.getElementById('tituloNuevoCliente');
            const divNomCliente = document.getElementById('divNomCliente');
            const btnClienteExistente = document.getElementById('btnClienteExistente');
            const tituloClienteExistente = document.getElementById('tituloClienteExistente');

            if (rfcCliente.value != '' && numTicket.value == '') {
                $('#ModalConfirm').modal('toggle');
            }
        });

        if (clienteCount.value == 0) {
            btnNuevoCliente.addEventListener('click', function() {
                if (divCliente.style.display == 'none') {
                    divCliente.style.display = 'block';
                    tituloNuevoCliente.style.display = 'block';
                }
            });
        }

        if (clienteCount.value > 0) {
            btnClienteExistente.addEventListener('click', function() {
                if (divNomCliente.style.display == 'none') {
                    divNomCliente.style.display = 'block';
                    tituloClienteExistente.style.display = 'block';
                    divCliente.style.display = 'block';
                }
            });
        }
    </script>
@endsection
