<!-- Modal Agregar Bloqueo -->
<div class="modal fade" id="AgregarBloqueo" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Agregar Nuevo Bloqueo</h5>
            </div>
            <form action="/AgregarBloqueoEmpleado" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg">
                            <div class="form-floating mb-2">
                                <input type="number" class="form-control validInput" id="numNominaEmpleado"
                                    placeholder="# Nomina" name="numNomina" required>
                                <label for="numNomina"># Nómina</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button id="btnBuscarEmpleado" type="button" class="btn btn-warning mt-2">
                                @include('components.icons.search') Buscar
                            </button>
                        </div>
                    </div>
                    <div hidden id="divNomEmpleado" class="row">
                        <h3 style="text-align: center">
                            <h4 style="text-align: center">
                                <span id="nomEmpleadoFetch" class=""></span>
                            </h4>
                        </h3>
                    </div>
                    <div hidden id="divMotivoBloqueo" class="row">
                        <div class="col-12">
                            <textarea class="form-control" name="motivoBloqueo" cols="30" rows="10" placeholder="Motivo de Bloqueo"
                                required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button hidden id="btnBloquear" class="btn btn-warning">Bloquear Empleado </button>
                </div>
            </form>
        </div>
    </div>
</div>
