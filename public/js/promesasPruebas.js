// const NomUsuario = document.getElementById('NomUsuario');
// const btnPromesa = document.getElementById('btnPromesa');
// const titulo = document.getElementById('titulo');
// const optionUsuarios = document.getElementById('optionUsuarios');


// const li = document.createElement('li');

// btnPromesa.addEventListener('click', async() => {
//     const respuesta = await fetch('/promesas?NomUsuario=' + `${NomUsuario.value}`);
//     const usuarios = await respuesta.json();
//     //console.log(usuarios);
//     const nombresUsuario = usuarios.map(usuario => usuario.NomUsuario);
//     //nomUsuario == '' ? optionUsuarios.innerHTML = 'No Hay Coincidencias' : optionUsuarios.innerHTML = nomUsuario;
//     //console.log(nombresUsuario);
//     // nomUsuario.forEach(usuario => {
//     //     optionUsuarios.innerHTML = usuario;
//     // });
//     nombresUsuario.forEach(nomUsuario => {

//         lista.innerHTML = `<li>${nomUsuario}</li>`;
//     })
// });


//console.log(lista);

const lista = document.getElementById('lista');
const arrayElementos = ['1el', '2el', '3el'];
const fragmentElementos = document.createDocumentFragment();

arrayElementos.forEach(item => {
    const li = document.createElement('li');
    li.textContent = item;
    const childNode = fragmentElementos.firstChild
    console.log(childNode);
    //fragmentElementos.insertBefore(item, childNode);
})

lista.appendChild(fragmentElementos);