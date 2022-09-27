var slider = document.getElementById("rangePorcentaje");
var output = document.getElementById("porciento");
output.innerHTML = slider.value;

slider.oninput = function() {
    output.innerHTML = this.value + "%";
}

function cantidad(id_input, operacion) {
    var numero = $('#' + id_input).val();
    if (operacion == '1') {
        numero++;
    } else {
        numero--;
    }
    $('#' + id_input).val(numero);
}

function visualizarCambios() {
    const listaChecks = document.querySelectorAll("#codigosCheck");
    const precios = document.querySelectorAll("#precios");
    var porcentaje = document.getElementById('rangePorcentaje');
    const pArticulo = document.querySelectorAll('#pArticulo');
    var radioPorcentaje = document.getElementById('porcentaje');
    var radioPesos = document.getElementById('pesos');
    var checkUno = document.getElementById('codigosCheck');
    var peso = document.getElementById('3');

    const porcentajePrecio = [];
    const cantidadPrecio = [];
    var precioFinal = 0;
    var precioFinalPeso = 0;
    var cont = 0;

    if (radioPorcentaje.checked) {
        for (let i = 0; i < listaChecks.length; i++) {
            if (listaChecks[i].checked) {
                cont++;
                //alert(pArticulo[i].textContent);
                porcentajePrecio[i] = Math.floor(pArticulo[i].textContent * porcentaje.value) / 100;
                //alert(porcentajePrecio[i]);
                if (porcentajePrecio[i] == 0) {
                    precios[i].value = 0;
                } else {
                    precioFinal = parseFloat(pArticulo[i].textContent) + parseFloat(porcentajePrecio[i]);
                    precios[i].value = precioFinal.toPrecision(4);
                }
            }
        }
        if (cont == 0) {
            alert('Seleccione Articulo(s) a Actualizar!');
        }
    } else if (radioPesos.checked) {
        for (let i = 0; i < listaChecks.length; i++) {
            if (listaChecks[i].checked) {
                cont++;
                //alert(pArticulo[i].textContent);
                cantidadPrecio[i] = (parseFloat(pArticulo[i].textContent) + parseFloat(peso.value));
                precioFinalPeso = cantidadPrecio[i];
                precios[i].value = precioFinalPeso;
            }
        }
        if (cont == 0) {
            alert('Seleccione Articulo(s) a Actualizar!');
        }
    } else {
        alert('Seleccione un Movimiento!');
    }
}

function enable() {
    var radioPesos = document.getElementById("pesos");
    var radioPorcentaje = document.getElementById("porcentaje");

    var range = document.getElementById('rangePorcentaje');
    var menos = document.getElementById("menos");
    var txt = document.getElementById("3");
    var mas = document.getElementById("mas");

    if (radioPorcentaje.checked == true) {
        range.disabled = false;
        menos.disabled = true;
        txt.disabled = true;
        mas.disabled = true;
    }
    if (radioPesos.checked == true) {
        range.disabled = true;
        menos.disabled = false;
        txt.disabled = false;
        mas.disabled = false;
    }
}

function ActualizarPrecios() {
    document.getElementById("formPrecios").action = "/ActualizarPrecios";
    document.getElementById("formPrecios").method = 'POST';
    document.getElementById("formPrecios").submit();
}


const radioActualizarPara = document.getElementById('radioActualizarPara');
radioActualizarPara.addEventListener("click", function() {
    document.getElementById('btnActualizar').disabled = false;
    document.getElementById('Fecha').disabled = false;
});

const radioActualizarAhora = document.getElementById('radioActualizarAhora');
radioActualizarAhora.addEventListener('click', function() {
    document.getElementById('btnActualizar').disabled = false;
    document.getElementById('Fecha').disabled = true;
});

function enableMovimientos() {
    var enableMovimiento = document.getElementById('TodaLaLista').checked;

    if (enableMovimiento) {
        document.getElementById('divMovimientos').style.display = 'block';
    } else {
        document.getElementById('divMovimientos').style.display = 'none';
    }
}

function seleccionarTodo() {
    const listaChecks = document.querySelectorAll("#codigosCheck");

    for (let i = 0; i < listaChecks.length; i++) {
        listaChecks[i].checked == false ? listaChecks[i].checked = true : listaChecks[i].checked = false
    }
}