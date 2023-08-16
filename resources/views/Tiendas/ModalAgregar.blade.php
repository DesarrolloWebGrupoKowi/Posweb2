<!-- Modal Agregar-->
<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    .form {
        width: clamp(600px, 30%, 400px);
        margin: 0 auto;
        border: 2px solid #ccc;
        border-radius: 0.35rem;
        padding: 1.5rem;
    }

    .form-step {
        display: none;
        transform-origin: top;
        animation: animate .5s;
    }

    .form-step-active {
        display: block;
    }


    .progressbar {
        position: relative;
        display: flex;
        justify-content: space-between;
        counter-reset: step;
        margin: .5rem 0 3rem;
    }

    .progressbar::before,
    .progress {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        width: 100%;
        background-color: #dcdcdc;
        z-index: -1;
    }

    .progress {
        background-color: rgb(49, 49, 247);
        width: 0%;
    }

    .progress-step {
        width: 2.1875rem;
        height: 2.1875rem;
        background-color: #dcdcdc;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;

    }

    .progress-step::before {
        counter-increment: step;
        content: counter(step);

    }

    .progress-step::after {
        content: attr(data-title);
        position: absolute;
        top: calc(100% + .3rem);
        font-size: 0.85rem;
        color: #666;
    }

    .progress-step-active {
        background-color: orange;
        color: #f3f3f3;

    }

    .siguienteBtn {
        float: right;
    }

    .Crear {
        float: right;
    }
</style>
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearTienda" method="POST" class="form">
                    @csrf
                    <!---Barra de progreso-->
                    <div class="progressbar">
                        <div class="progress" id="progress">

                        </div>
                        <div class="progress-step progress-step-active" data-title="General"></div>
                        <div class="progress-step" data-title="Dirección"></div>
                        <div class="progress-step" data-title="Lista Precios"></div>
                        <div class="progress-step" data-title="Plaza"></div>
                        <div class="progress-step" data-title="Cloud"></div>
                        <div class="progress-step" data-title="Servicio"></div>
                    </div>
                    <!---Pasos-->
                    <div class="container">
                        <div class="form-step form-step-active">
                            <div>
                                <label for="" class="form-label">Nombre de Tienda</label>
                                <input type="text" id="NomTienda" name="NomTienda" class="form-control"
                                    tabindex="1" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Correo</label>
                                <input type="email" id="Correo" name="Correo" class="form-control" tabindex="2"
                                    required>
                            </div>
                            <div>
                                <label for="" class="form-label">RFC</label>
                                <input type="text" id="RFC" name="RFC" class="form-control" tabindex="2"
                                    onkeyup="mayusculas(this)" required>
                            </div>
                            <div class="mb-3">
                                <a class="btn btn-default siguienteBtn"><span
                                        class="material-icons">arrow_forward</span></a>
                            </div>
                        </div>
                        <div class="form-step">
                            <div>
                                <label for="" class="form-label">Dirección</label>
                                <input type="text" id="Direccion" name="Direccion" class="form-control"
                                    tabindex="2" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Colonia</label>
                                <input type="text" id="Colonia" name="Colonia" class="form-control" tabindex="2"
                                    onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Telefono</label>
                                <input type="text" id="Telefono" name="Telefono" class="form-control" tabindex="2"
                                    required>
                            </div>
                            <div>
                                <a class="btn atrasBtn"><span class="material-icons">arrow_back</span></a>
                                <a class="btn siguienteBtn"><span class="material-icons">arrow_forward</span></a>
                            </div>
                        </div>
                        <div class="form-step">
                            <div>
                                <label for="" class="form-label">Lista de Precios</label>
                                <input type="text" id="IdListaPrecios" name="IdListaPrecios" class="form-control"
                                    tabindex="2" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Tienda Activa Local</label>
                                <select class="form-select" name="TiendaActiva" id="TiendaActiva">
                                    <option value="0">Si</option>
                                    <option selected value="1">No</option>
                                </select>
                            </div>
                            <div>
                                <label for="" class="form-label">Inventario</label>
                                <select class="form-select" name="Inventario" id="Inventario">
                                    <option value="0">Si</option>
                                    <option value="1">No</option>
                                </select>
                            </div>
                            <div>
                                <a class="btn atrasBtn"><span class="material-icons">arrow_back</span></a>
                                <a class="btn siguienteBtn"><span class="material-icons">arrow_forward</span></a>
                            </div>
                        </div>
                        <div class="form-step">
                            <div>
                                <label for="" class="form-label">Centro de Costo</label>
                                <input type="text" id="CentroCosto" name="CentroCosto" class="form-control"
                                    tabindex="2" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Ciudad</label>
                                <select name="IdCiudad" id="IdCiudad" class="form-select">
                                    @foreach ($ciudades as $ciudad)
                                        <option value="{{ $ciudad->IdCiudad }}">{{ $ciudad->NomCiudad }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="" class="form-label">Plaza</label>
                                <select name="IdPlaza" id="IdPlaza" class="form-select">
                                    @foreach ($plazas as $plaza)
                                        <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <a class="btn atrasBtn"><span class="material-icons">arrow_back</span></a>
                                <a class="btn siguienteBtn"><span class="material-icons">arrow_forward</span></a>
                            </div>
                        </div>
                        <div class="form-step">
                            <div>
                                <label for="" class="form-label">Almacen</label>
                                <input type="text" id="Almacen" name="Almacen" class="form-control"
                                    tabindex="2" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Organización Nombre</label>
                                <input type="text" id="Organization_Name" name="Organization_Name"
                                    class="form-control" tabindex="2" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Subinventario</label>
                                <input type="text" id="Subinventory_Code" name="Subinventory_Code"
                                    class="form-control" tabindex="2" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <label for="" class="form-label">Tipo de Orden</label>
                                <input type="text" id="Order_Type_Cloud" name="Order_Type_Cloud"
                                    class="form-control" tabindex="2" onkeyup="mayusculas(this)" required>
                            </div>
                            <div>
                                <a class="btn atrasBtn"><span class="material-icons">arrow_back</span></a>
                                <a class="btn siguienteBtn"><span class="material-icons">arrow_forward</span></a>
                            </div>
                        </div>
                        <div class="form-step">
                            <div>
                                <label for="" class="form-label">Servicio a Domicilio</label>
                                <select class="form-select" name="ServicioaDomicilio" id="ServicioaDomicilio">
                                    <option value="0">Activo</option>
                                    <option value="1">Inactivo</option>
                                </select>
                            </div>
                            <div>
                                <label for="" class="form-label">Costo a Domicilio</label>
                                <input type="number" id="CostoaDomicilio" name="CostoaDomicilio"
                                    class="form-control" tabindex="2">
                            </div>
                            <div>
                                <label for="" class="form-label">Comentario</label>
                                <input type="text" id="Comentario" name="Comentario" class="form-control"
                                    tabindex="2" onkeyup="mayusculas(this)">
                            </div>
                            <div>
                                <a class="btn btn-default my-1 atrasBtn"><span
                                        class="material-icons">arrow_back</span></a>
                                <button class="btn btn-warning my-2 Crear">
                                    <i class="fa fa-save"></i> Crear Tienda
                                </button>
                                <!--<a href="#" class="btn siguienteBtn">Finalizar</a>-->
                            </div>

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    const atrasBtns = document.querySelectorAll(".atrasBtn");
    const siguienteBtns = document.querySelectorAll(".siguienteBtn");
    const progress = document.getElementById("progress");
    const formSteps = document.querySelectorAll(".form-step");
    const progressSteps = document.querySelectorAll(".progress-step");

    let formStepsNum = 0;
    siguienteBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            formStepsNum++;
            updateFomrSteps();
            updateProgressbar();
        });
    });


    atrasBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            formStepsNum--;
            updateFomrSteps();
            updateProgressbar();
        });
    });


    function updateFomrSteps() {
        formSteps.forEach(formStep => {
            formStep.classList.contains("form-step-active") &&
                formStep.classList.remove("form-step-active");
        });


        formSteps[formStepsNum].classList.add("form-step-active");
    }

    function updateProgressbar() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add("progress-step-active");
            } else {
                progressStep.classList.remove("progress-step-active");
            }
        });

        const progressActive = document.querySelectorAll(".progress-step-active");
        progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
    }
</script>
