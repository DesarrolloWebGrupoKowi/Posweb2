const codEtiqueta = document.getElementById('txtCodEtiqueta');
const formDatPedidos = document.getElementById('formDatPedidos');

formDatPedidos.addEventListener('submit', function() {
    setTimeout(function() {
        codEtiqueta.value = '';
    }, 100);
});