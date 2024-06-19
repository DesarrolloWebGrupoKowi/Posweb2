// Evento para cuando se quiere agregar un recalentado, en el modal
document.addEventListener('click', e => {
    if (e.target.matches('.btn-mermar-rosticero') || e.target.matches('.btn-mermar-rosticero *')) {

        let button = e.target.parentNode.parentNode.parentNode.querySelector('.btn-mermar-rosticero');
        let element = e.target.parentNode.parentNode.parentNode.querySelector('.form-mermar-rosticero');

        if (element.classList.contains('d-none')) {
            element.classList.remove('d-none');
            button.innerHTML = `
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
            `;
        } else {
            element.classList.add('d-none');
            button.innerHTML = `
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3" />
                </svg>
            `;
        }
    }
})
