<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearFamilia" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Familia</label>
                        <input type="text" id="NomFamilia" name="NomFamilia" class="form-control" onkeyup="mayusculas(this)" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Crear Familia">
                </form>
            </div>
        </div>
    </div>
</div>