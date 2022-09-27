const verPassword = document.getElementById('verPassword');
const txtPassword = document.getElementById('editPassword');

verPassword.addEventListener('click', function() {
    txtPassword.type == 'password' ? txtPassword.type = 'text' : txtPassword.type = 'password';
});