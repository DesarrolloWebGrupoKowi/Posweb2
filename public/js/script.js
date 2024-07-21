$("#alerta").fadeTo(4000, 1000).slideUp(1000, function () {
    $("alerta").slideUp(3000);
});

function mostrarPass1(id) {
    var t = document.getElementById("Password1" + id);
    if (t.type == "password") {
        t.type = "text";
    } else {
        t.type = "password";
    }
}

function mostrarPass2(id) {
    var m = document.getElementById("Password2" + id);
    if (m.type == "password") {
        m.type = "text";
    } else {
        m.type = "password";
    }
}

function mayusculas(e) {
    e.value = e.value.toUpperCase();
}

function remove() {
    document.getElementById("formChange").action = "/RemoverMenu";
    document.getElementById("formChange").submit();
}

function agregar() {
    document.getElementById("formChange").action = "/AgregarMenu";
    document.getElementById("formChange").submit();
}

function MenuTipoUsuario() {
    document.getElementById("formChange").submit();
}

function mostrarListas() {
    document.getElementById("formListaP").submit();
}

function removerLista() {
    document.getElementById("formListaP").action = "/RemoverLista";
    document.getElementById("formListaP").submit();
}

function agregarLista() {
    document.getElementById("formListaP").action = "/AgregarLista";
    document.getElementById("formListaP").submit();
}

function Opciones() {
    var radioTodas = document.getElementById("radioTodas");
    var radioPlaza = document.getElementById("radioPlaza");
    var radioTienda = document.getElementById("radioTienda");
    if (radioPlaza.checked) {
        document.getElementById("divPlaza").style.display = 'block';
        document.getElementById("divTienda").style.display = 'none';
    }
    if (radioTienda.checked) {
        document.getElementById("divPlaza").style.display = 'none';
        document.getElementById("divTienda").style.display = 'block';
    }
    if (radioTodas.checked) {
        document.getElementById("divPlaza").style.display = 'none';
        document.getElementById("divTienda").style.display = 'none';
    }

}

function OpcionesEdit(id) {
    var radioTodasEdit = document.getElementById("radioTodasEdit" + id);
    var radioPlazaEdit = document.getElementById("radioPlazaEdit" + id);
    var radioTiendaEdit = document.getElementById("radioTiendaEdit" + id);

    if (radioPlazaEdit.checked) {
        document.getElementById("divPlazaEdit" + id).style.display = 'block';
        document.getElementById("divTiendaEdit" + id).style.display = 'none';
    }
    if (radioTiendaEdit.checked) {
        document.getElementById("divPlazaEdit" + id).style.display = 'none';
        document.getElementById("divTiendaEdit" + id).style.display = 'block';
    }
    if (radioTodasEdit.checked) {
        document.getElementById("divPlazaEdit" + id).style.display = 'none';
        document.getElementById("divTiendaEdit" + id).style.display = 'none';
    }
}

// Ayuda a abrir el modal de manera dinámica
// y a poner automáticamente el foco en el input con la clase form-control-codigo
document.addEventListener('DOMContentLoaded', () => {
    let button = document.querySelector('.modalOpen');
    if (button) {
        button.click();

        let id = button.getAttribute('data-bs-target').substring(1);
        let modal = document.getElementById(id);
        let input = modal.querySelector('.form-control-codigo');
        setTimeout(() => input.focus(), 500);

    }
});
