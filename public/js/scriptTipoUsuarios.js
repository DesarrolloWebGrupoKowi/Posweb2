const filtroActivo = document.getElementById('filtroActivo');
filtroActivo.addEventListener('change', function() {
    document.getElementById("formTipoUsuarios").submit();
});