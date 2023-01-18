<!-- Modal Empleados -->
<div class="modal fade" id="ModalEmpleado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="fa fa-user"></i> Empleados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/BuscarEmpleado" target="ifrEmpleado">
                    <div class="row mb-3">
                        <div class="col-4">
                            <input type="text" class="form-control" name="numNomina" id="numNomina"
                                placeholder="Escanee Credencial" autocomplete="off" required>
                        </div>
                        <!--<div class="col-2">
                            <button class="btn card rounded-3">
                                <span style="color: orange" class="material-icons">search</span>
                            </button>
                        </div>-->
                        <div class="col d-flex justify-content-end">
                            <a href="/LigarSocioFrecuente" type="button" class="btn btn-warning shadow">
                                <i class="fa fa-user-circle"></i> Ligar Socio/Frecuente
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <iframe name="ifrEmpleado" frameborder="0"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
