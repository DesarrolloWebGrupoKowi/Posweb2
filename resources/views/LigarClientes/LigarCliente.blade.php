@extends('plantillaBase.masterblade')
@section('title', 'Ligar Cliente')
<style>
    #customers td {
        border: 1px solid rgb(110, 110, 110);
        font-size: 14px;
    }

    #customers th {
        border: .5px solid rgb(49, 49, 49);
    }
</style>
@section('contenido')
    <div class="container">
        <h2 class="titulo card shadow">Ligar Solicitud de Factura con Cliente Oracle</h2>
    </div>
    <div class="container-fluid">
        <table id="customers" class="table table-sm table-striped table-responsive shadow caption-top">
            <div class="row ms-3">
                <h6 class="col-auto bg-warning p-1">Datos En Solicitud</h6>
            </div>
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Tienda</th>
                    <th>Nombre</th>
                    <th>RFC</th>
                    <th>Calle</th>
                    <th>N.Ext</th>
                    <th>N.Int</th>
                    <th>C.P</th>
                    <th>Colonia</th>
                    <th>Ciudad</th>
                    <th>Municipio</th>
                    <th>Estado</th>
                    <th>Pais</th>
                    <th>Correo</th>
                    <th>Pago</th>
                    <th>Banco</th>
                    <th>Cuenta</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $solicitudFactura->IdSolicitudFactura }}</td>
                    <td>{{ $solicitudFactura->NomTienda }}</td>
                    <td>{{ $solicitudFactura->NomCliente }}</td>
                    <td>{{ $solicitudFactura->RFC }}</td>
                    <td>{{ $solicitudFactura->Calle }}</td>
                    <td>{{ $solicitudFactura->NumExt }}</td>
                    <td>{{ $solicitudFactura->NumInt }}</td>
                    <td>{{ $solicitudFactura->CodigoPostal }}</td>
                    <td>{{ $solicitudFactura->Colonia }}</td>
                    <td>{{ $solicitudFactura->Ciudad }}</td>
                    <td>{{ $solicitudFactura->Municipio }}</td>
                    <td>{{ $solicitudFactura->Estado }}</td>
                    <td>{{ $solicitudFactura->Pais }}</td>
                    <td>{{ $solicitudFactura->Email }}</td>
                    <td>{{ $solicitudFactura->NomTipoPago }}</td>
                    <td>{{ $solicitudFactura->NomBanco }}</td>
                    <td>{{ $solicitudFactura->NumTarjeta }}</td>
                    <td>{{ $solicitudFactura->Telefono }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container-fluid">
        <table id="customers" class="table table-co table-sm table-striped shadow caption-top">
            <div class="row ms-3">
                <h6 class="col-auto bg-warning p-1">Datos En Oracle</h6>
            </div>
            <thead class="table-dark">
                <tr>
                    <th>IdClienteCloud</th>
                    <th>Nombre</th>
                    <th>RFC</th>
                    <th>Correo</th>
                    <th>Ship_To</th>
                    <th>Bill_To</th>
                    <th>Calle</th>
                    <th>Numero Cliente</th>
                    <th>Party_Site_Number</th>
                    <th>Locacion</th>
                    <th>Nombre_Alt</th>
                    <th>Ligar</th>
                </tr>
            </thead>
            <tbody>
                @if ($clienteAlta == 1)
                    <tr>
                        <td colspan="12"><i style="color: red" class="fa fa-exclamation-triangle"></i> No se Ha Dado de
                            Alta el
                            Cliente en Oracle!</td>
                    </tr>
                @else
                    @foreach ($clientesOracle as $clienteOracle)
                        <form action="LigarCliente">
                            <input type="hidden" name="idSolicitudFactura"
                                value="{{ $solicitudFactura->IdSolicitudFactura }}">
                            <input type="hidden" name="bill_To" value="{{ $clienteOracle->BILL_TO }}">
                            <tr>
                                <td>{{ $clienteOracle->ID_CLIENTE }}</td>
                                <td>{{ $clienteOracle->NOMBRE }}</td>
                                <td>{{ $clienteOracle->RFC }}</td>
                                <td>
                                    @if ($clienteOracle->Correo->count() == 0)
                                        <i style="color: red" class="fa fa-exclamation-triangle"></i>
                                    @else
                                        @foreach ($clienteOracle->Correo as $correo)
                                            {{ $correo->EMAIL }}
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $clienteOracle->SHIP_TO }}</td>
                                <td>{{ $clienteOracle->BILL_TO }}</td>
                                <td>{{ $clienteOracle->CALLE }}</td>
                                <td>{{ $clienteOracle->NUMERO_CLIENTE }}</td>
                                <td>{{ $clienteOracle->PARTY_SITE_NUMBER }}</td>
                                <td>{{ $clienteOracle->LOCACION }}</td>
                                <td>{{ $clienteOracle->NOMBRE_ALT }}</td>
                                <td>
                                    <button class="btn btn-sm">
                                        <span class="material-icons">add_task</span>
                                    </button>
                                </td>
                            </tr>
                        </form>
                    @endforeach
                @endif
            </tbody>
        </table>
        @if ($ligarCliente == 0)
            <div class="mb-3 container-fluid">
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <ul class="col-auto list-group">
                            <li class="list-group-item bg-dark text-white fw-bold">Dirección a Ligar</li>
                            <li class="list-group-item"><strong>Id Cliente: </strong>{{ $cOracle->ID_CLIENTE }}</li>
                            <li class="list-group-item"><strong>Bill_To: </strong>{{ $cOracle->BILL_TO }}</li>
                            <li class="list-group-item"><strong>Nombre: </strong>{{ $cOracle->NOMBRE }}</li>
                            <li class="list-group-item"><strong>RFC: </strong>{{ $cOracle->RFC }}</li>
                            <li class="list-group-item"><strong>Dirección: </strong>{{ $cOracle->CALLE }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mb-3 d-flex justify-content-center">
                <button class="col-auto btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#ModalConfirmarLigueCliente">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>
            @include('LigarClientes.ModalConfirmarLigueCliente')
        @endif
    </div>
@endsection
