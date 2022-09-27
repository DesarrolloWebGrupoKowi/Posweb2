const checkListaExistente = document.getElementById('checkExistente');
const divSelectListaPrecio = document.getElementById('divSelectListaPrecio');
checkListaExistente.addEventListener('click', function() {
    checkListaExistente.checked == true ? divSelectListaPrecio.style.display = 'block' : divSelectListaPrecio.style.display = 'none';
})