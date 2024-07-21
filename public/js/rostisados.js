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

document.addEventListener('change', e => {
    if (e.target.matches('#tipoMerma')) {
        let subTipos = document.querySelectorAll('.subtiposmerma');
        subTipos.forEach(item => {
            item.classList.add('d-none')
        })

        let id = 'subtipomerma' + e.target.value;
        let subtipo = document.getElementById(id);
        if (subtipo) {
            subtipo.classList.remove('d-none');
            subtipo.classList.add('d-block');
        }
    }
})

document.addEventListener('keyup', e => {
    if (e.target.matches('#CodigoEtiqueta')) {
        let codigoEtiqueta = e.target.value;
        let IdDatDetalleRosticero = document.querySelector('input[name="IdDatDetalleRosticero"]:checked');
        let isCodigoEtiqueta = document.getElementById(codigoEtiqueta);

        if (isCodigoEtiqueta && !IdDatDetalleRosticero) {
            isCodigoEtiqueta.classList.replace('d-none', 'd-block');
            e.preventDefault();
        }
        if (!isCodigoEtiqueta) {
            document.querySelectorAll('div[data-CodigoEtiqueta]').forEach(e => {
                e.classList.add('d-none');
            });
        }
    }
    if (e.target.matches('#CodigoEtiquetaAnterior')) {
        let codigoEtiqueta = e.target.value;
        let IdDatDetalleRosticero = document.querySelector('input[name="IdDatDetalleRosticeroAN"]:checked');
        let isCodigoEtiqueta = document.getElementById('ant' + codigoEtiqueta);

        console.log(isCodigoEtiqueta);
        if (isCodigoEtiqueta && !IdDatDetalleRosticero) {
            isCodigoEtiqueta.classList.replace('d-none', 'd-block');
            e.preventDefault();
        }
        if (!isCodigoEtiqueta) {
            document.querySelectorAll('div[data-CodigoEtiquetaAnterior]').forEach(e => {
                e.classList.add('d-none');
            });
        }
    }
})

document.addEventListener('submit', e => {
    if (e.target.matches('#formMermar')) {
        let codigoEtiqueta = e.target.CodigoEtiqueta.value;
        let IdDatDetalleRosticero = document.querySelector('input[name="IdDatDetalleRosticero"]:checked');
        let isCodigoEtiqueta = document.getElementById(codigoEtiqueta);

        if (isCodigoEtiqueta && !IdDatDetalleRosticero) {
            e.preventDefault();
        }
    }
    if (e.target.matches('#formRecalentado')) {
        let codigoEtiqueta = e.target.CodigoEtiquetaAnterior.value;
        let IdDatDetalleRosticero = document.querySelector('input[name="IdDatDetalleRosticeroAN"]:checked');
        let isCodigoEtiqueta = document.getElementById('ant' + codigoEtiqueta);

        if (isCodigoEtiqueta && !IdDatDetalleRosticero) {
            e.preventDefault();
        }
    }
})
