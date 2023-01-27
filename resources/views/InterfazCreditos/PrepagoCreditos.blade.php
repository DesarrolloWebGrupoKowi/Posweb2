@extends('plantillaBase.masterblade')
@section('title', 'Prepago de Créditos')
@section('contenido')
    <style>
        i:active {
            transform: scale(1.4);
        }
    </style>
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto card shadow p-1">
                <h2>Pagar/Abonar Deudas de Empleados</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            @include('Alertas.Alertas')
        </div>
    </div>
    <div class="container">
        <div class="row ms-2 mb-1">
            <div class="col-auto">
                <div class="input-group input-group-sm shadow">
                    <span class="input-group-text shadow">
                        <input checked class="form-check-input mt-0" type="radio" name="radioFiltro" id="nomina">
                    </span>
                    <span class="input-group-text card shadow">Nómina</span>
                    <span class="input-group-text shadow">
                        <input class="form-check-input mt-0" type="radio" name="radioFiltro" id="nombre">
                    </span>
                    <span class="input-group-text card shadow">Nombre</span>
                    <input type="text" class="form-control shadow" name="filtro" id="filtro"
                        placeholder="Buscar empleado...">
                </div>
            </div>
            <div class="col me-3">
                <div class="input-group input-group-sm d-flex justify-content-end">
                    @if ($nomEmpleado == 'No')
                        <span class="input-group-text shadow fw-bold">
                            Tipo Nómina:
                        </span>
                        <span class="input-group-text card shadow">{{ $nomTipoNomina }}</span>
                    @else
                        <span class="input-group-text shadow fw-bold">
                            Empleado:
                        </span>
                        <span class="input-group-text card shadow">{{ $nomEmpleado }}</span>
                    @endif
                    <span class="input-group-text shadow fw-bold">
                        Del:
                    </span>
                    <span class="input-group-text card shadow">{{ strftime('%d %B %Y', strtotime($fecha1)) }}</span>
                    <span class="input-group-text shadow fw-bold">
                        Al:
                    </span>
                    <span class="input-group-text card shadow">{{ strftime('%d %B %Y', strtotime($fecha2)) }}</span>
                </div>
            </div>
        </div>
        <div style="height: 65vh" class="table-responsive card shadow">
            <table id="tblCreditos" class="table table-sm table-striped table-responsive">
                <thead class="table-dark">
                    <tr>
                        <th>Nómina</th>
                        <th>Empleado</th>
                        <th>Deuda</th>
                        <th>Pago</th>
                        <th>Saldo</th>
                        <th>Ajuste</th>
                        <th>Sistema</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($creditos))
                        <tr>
                            <th colspan="6">No hay créditos pendientes por exportar!</th>
                        </tr>
                    @else
                        @php
                            $totalPago = 0;
                            $totalDeuda = 0;
                            $totalSaldo = 0;
                        @endphp
                        @foreach ($creditos as $credito)
                            <tr>
                                <td>{{ $credito->NumNomina }}</td>
                                <td>{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                                <td>$ {{ number_format($credito->ImporteCredito, 2) }}</td>
                                @if (empty($credito->PagoDeuda))
                                    <td>$ {{ number_format($credito->ImporteCredito, 2) }}</td>
                                @else
                                    <td style="color: green; font-weight: bold">$
                                        {{ number_format($credito->PagoDeuda, 2) }}
                                    </td>
                                @endif
                                @if (empty($credito->PagoDeuda))
                                    <td style="color: green">$
                                        {{ number_format($credito->ImporteCredito - $credito->ImporteCredito, 2) }}</td>
                                @else
                                    <td style="color: red; font-weight: bold">$
                                        {{ number_format($credito->ImporteCredito - $credito->PagoDeuda, 2) }}</td>
                                @endif
                                <th>
                                    <i style="cursor: pointer; font-size: 18px; color: green" class="fa fa-usd"
                                        data-bs-toggle="modal" data-bs-target="#ModalAjuste{{ $credito->IdEncabezado }}">
                                    </i>
                                    @if (!empty($credito->PagoDeuda))
                                        | <i class="fa fa-close" style="cursor: pointer; font-size: 18px; color: red"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarAjuste{{ $credito->IdEncabezado }}"></i>
                                    @endif
                                </th>
                                <td>
                                    @if ($credito->isSistemaNuevo)
                                        <i style="color: blue" class="fa fa-chrome"></i>
                                    @else
                                        <i style="color: red" class="fa fa-internet-explorer"></i>
                                    @endif
                                </td>
                                <td>
                                    <input {!! !empty($credito->PagoDeuda) ? 'disabled' : '' !!} checked class="form-check-input" type="checkbox"
                                        name="chkPagar" id="chkPagar"
                                        value="{{ empty($credito->PagoDeuda) ? $credito->ImporteCredito : $credito->PagoDeuda }}">
                                </td>
                                @include('InterfazCreditos.ModalEliminarAjuste')
                                @include('InterfazCreditos.ModalAjuste')
                            </tr>
                            @php
                                $totalPago = $totalPago + (empty($credito->PagoDeuda) ? $credito->ImporteCredito : $credito->PagoDeuda);
                                $totalDeuda = $totalDeuda + $credito->ImporteCredito;
                                $totalSaldo = $totalSaldo + (empty($credito->PagoDeuda) ? $credito->ImporteCredito - $credito->ImporteCredito : $credito->ImporteCredito - $credito->PagoDeuda);
                            @endphp
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th style="font-size: 18px">${{ number_format($totalDeuda, 2) }}</th>
                        <th style="font-size: 18px; color: green">${{ number_format($totalPago, 2) }}</th>
                        <th style="font-size: 18px; color: red">${{ number_format($totalSaldo, 2) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('filtro').addEventListener('keyup', (e) => {
            const radioCodigo = document.getElementById('nomina').checked;

            var input, filter, table, tr, td, i, txtValue;
            let deudaTotal = 0;
            let pagoTotal = 0;
            let saldoTotal = 0;

            input = document.getElementById("filtro");
            filter = input.value.toUpperCase();
            table = document.getElementById("tblCreditos");
            tr = table.getElementsByTagName("tr");
            var tfoof = table.getElementsByTagName("tfoot")
            var rowTfoot = tfoof[0].getElementsByTagName("tr")[0];

            for (i = 0; i < tr.length; i++) {
                td = radioCodigo == true ? tr[i].getElementsByTagName("td")[0] : tr[i].getElementsByTagName("td")[
                    1];

                const deuda = tr[i].getElementsByTagName("td")[2];
                const pago = tr[i].getElementsByTagName("td")[3];
                const saldo = tr[i].getElementsByTagName("td")[4];

                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        //deuda
                        const importeDeuda = deuda.textContent.toString();
                        const deudaE = importeDeuda.slice(2);
                        deudaTotal = deudaTotal + parseFloat(deudaE);
                        rowTfoot.cells[2].textContent = '$' + deudaTotal.toFixed(2);
                        //pago
                        const importePago = pago.textContent.toString();
                        const pagoE = importePago.slice(2);
                        pagoTotal = pagoTotal + parseFloat(pagoE);
                        rowTfoot.cells[3].textContent = '$' + pagoTotal.toFixed(2);
                        //saldo
                        const importeSaldo = saldo.textContent.toString();
                        const saldoE = importeSaldo.slice(2);
                        saldoTotal = saldoTotal + parseFloat(saldoE);
                        rowTfoot.cells[4].textContent = '$' + saldoTotal.toFixed(2);
                    } else {
                        tr[i].style.display = "none";
                        rowTfoot.cells[2].textContent = '$' + deudaTotal.toFixed(2);
                        rowTfoot.cells[3].textContent = '$' + pagoTotal.toFixed(2);
                        rowTfoot.cells[4].textContent = '$' + saldoTotal.toFixed(2);
                    }
                }
            }
        });

        // sumar credito que este checked
        var tblCredi = document.getElementById("tblCreditos");
        var tblTtfoof = tblCredi.getElementsByTagName("tfoot")
        var rowTblTfoot = tblTtfoof[0].getElementsByTagName("tr")[0];

        const chkPagar = document.querySelectorAll('#chkPagar');
        chkPagar.forEach(element => {
            element.addEventListener('click', (e) => {
                var pagoTotal = 0; // inicializar el pagoTotal en cero, para empezar a contar los checked
                for (let i = 0; i < chkPagar.length; i++) {
                    if (chkPagar[i].checked) {
                        var pagoTotal = pagoTotal + parseFloat(chkPagar[i].value);
                    }
                }
                rowTblTfoot.cells[3].textContent = '$' + pagoTotal.toFixed(2);
                var finalPago = '<?php echo $totalDeuda; ?>' - pagoTotal;
                rowTblTfoot.cells[4].textContent = '$' + finalPago.toFixed(2);
            });
        });
    </script>
@endsection
