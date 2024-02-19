$(function () {
    $('#ModalPagar').on('shown.bs.modal', function (e) {
        $('#txtPago').focus();
    })
});

$(function () {
    $('#ModalConsultar').on('shown.bs.modal', function (e) {
        $('#filtroArticulo').focus();
    })
});

$(function () {
    $('#ModalEmpleado').on('shown.bs.modal', function (e) {
        $('#numNomina').focus();

        var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $("#numNomina").on("input", function () {
            delay(function () {
                if ($("#numNomina").val().length < 2) {
                    $("#numNomina").val("");
                }
            }, 20);

        });

        //No Permitir que Copie y peque el NumNomina
        /*$(document).on("cut copy paste", "#numNomina", function(e) {
            e.preventDefault();
        });*/
    })
});

const visibleCheckCliente = $("#chkCliente").is(":visible");
if (visibleCheckCliente) {
    const chkCliente = document.getElementById('chkCliente');
    chkCliente.addEventListener('click', function () {
        location.href = '/QuitarEmpleado';
    });
}

const visiblechkCancelarDescuento = $("#chkCancelarDescuento").is(":visible");
if (visiblechkCancelarDescuento) {
    const chkCancelarDescuento = document.getElementById('chkCancelarDescuento');
    chkCancelarDescuento.addEventListener('click', (e) => {
        location.href = '/CancelarDescuento';
    })
}

const btnPedidos = document.getElementById('btnPedidos');

btnPedidos.addEventListener('click', function () {
    location.href = '/PedidosGuardados';
});

//Ocultar alerta pasados 4 segundos
$("#alerta").fadeTo(5000, 1000).slideUp(1000, function () {
    $("alerta").slideUp(1000);
});

const btnPagar = document.getElementById('btnPagar');
const btnConsultar = document.getElementById('btnConsultar');

/*const txtCodigo = document.getElementById('txtCodigo');
txtCodigo.addEventListener('keypress', function(e) {
    if (e.key == 'c') {
        alert('menito');
    }
})*/

function teclas(e) {
    if (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode == 13) {
        return true;
    } else {
        return false;
    }
}

document.addEventListener('keypress', function (e) {
    const esVisible = $("#ModalConsultar").is(":visible");
    const esVisibleModalFactura = $("#ModalFactura").is(":visible");
    if (!esVisible && !esVisibleModalFactura) {
        const key = e.key;
        key == 'p' ? btnPagar.click() : key == 'c' ? btnConsultar.click() : key == 'P' ? btnPagar.click() : key == 'C' ? btnConsultar.click() : '';
    }
});

const formPago = document.getElementById('formPago');
const txtPago = document.getElementById('txtPago');

formPago.addEventListener('submit', function () {
    setTimeout(function () {
        txtPago.disabled = true;
    }, 100)
});

const CorteTienda = document.getElementById('CorteTienda');
const ReimprimirTicket = document.getElementById('ReimprimirTicket');
const ticketDiario = document.getElementById('ticketDiario');
const listadoCodEtiqueta = document.getElementById('listadoCodEtiqueta');
const concentradoVentas = document.getElementById('concentradoVentas');
const stock = document.getElementById('stock');
const btnSolicitudFactura = document.getElementById('btnSolicitudFactura');
const solicitudesFactura = document.getElementById('solicitudesFactura');
const adeudosEmpleado = document.getElementById('adeudosEmpleado');
const paquetes = document.getElementById('paquetes');
const btnReimprimirUltimo = document.getElementById('btnReimprimirUltimo');

CorteTienda.addEventListener('click', function () {
    location.href = '/CorteDiario';
});

btnSolicitudFactura.addEventListener('click', function () {
    location.href = '/SolicitudFactura';
});

ReimprimirTicket.addEventListener('click', function () {
    location.href = '/ReimprimirTicket';
});

ticketDiario.addEventListener('click', function () {
    location.href = '/VentaTicketDiario';
});

listadoCodEtiqueta.addEventListener('click', function () {
    location.href = '/ListaCodEtiquetas';
});

concentradoVentas.addEventListener('click', function () {
    location.href = '/ConcentradoVentas';
});

stock.addEventListener('click', function () {
    location.href = '/ReporteStock';
});

solicitudesFactura.addEventListener('click', function () {
    location.href = '/VerSolicitudesFactura';
});

adeudosEmpleado.addEventListener('click', function () {
    location.href = '/AdeudosEmpleado';
})

paquetes.addEventListener('click', function () {
    location.href = '/Paquetes';
})

btnReimprimirUltimo.addEventListener('click', function () {
    location.href = '/ImprimirTicket';
})

const tipoPago = document.getElementById('tipoPago');
const modalPagoTarjeta = document.getElementById('modalPagoTarjeta');
tipoPago.addEventListener('change', function () {
    const DivTarjeta = document.getElementById('DivTarjeta');
    const numTarjeta = document.getElementById('numTarjeta');
    const idBanco = document.getElementById('idBanco');

    if (tipoPago.value == 4 || tipoPago.value == 5) {
        DivTarjeta.style.display = 'block';
        numTarjeta.setAttribute('required', '');
        idBanco.setAttribute('required', '');
    } else {
        DivTarjeta.style.display = 'none';
        numTarjeta.removeAttribute('required');
        idBanco.removeAttribute('required');
    }
});
