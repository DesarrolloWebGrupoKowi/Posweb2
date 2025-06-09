<!-- Modal Agregar Empleado-->
<div class="modal fade" id="ModalAgregarEmpleado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Empleado</h5>
            </div>
            <form action="/CatLimiteCreditoEspecial" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>NumNomina</label>
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="NumNomina" id="NumNomina" placeholder="Número nomina" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Limite Crédito</label>
                            <input type="number" class="form-control rounded" style="line-height: 18px" name="Limite"
                                id="Limite" placeholder="Limite de crédito" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Ventas Diarias</label>
                            <input type="number" class="form-control rounded" style="line-height: 18px"
                                name="TotalVentaDiaria" id="TotalVentaDiaria" placeholder="Ventas diarias" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-warning">Agregar </button>
                </div>
            </form>
        </div>
    </div>
</div>
