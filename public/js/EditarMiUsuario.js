document.getElementById("btnEditarUsuario").addEventListener('click', function() {
    document.getElementById('EditarUsuario').style.display == 'none' ?
        document.getElementById('EditarUsuario').style.display = 'block' :
        document.getElementById('EditarUsuario').style.display = 'none';
});

document.getElementById('showPassword').addEventListener('click', function() {
    document.getElementById('editPassword').style.display == 'none' ?
        document.getElementById('editPassword').style.display = 'block' :
        document.getElementById('editPassword').style.display = 'none';
});