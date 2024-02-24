document.addEventListener("DOMContentLoaded", (e) => {
    // Para reescribir las urls de la paginacion de algunas paginas
    let idPagination = document.querySelectorAll(".idPagination");
    if (idPagination) {
        idPagination.forEach((pag) => {
            let pagination = document.querySelectorAll(".page-link");
            pagination.forEach((item) => {
                if (item.href) item.href += pag.value;
            });
        });
    }
});
