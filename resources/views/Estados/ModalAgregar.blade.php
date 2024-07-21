<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Estado</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearEstado" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de Estado</label>
                        <input type="text" id="NomEstado" name="NomEstado" class="form-control rounded"
                            style="line-height: 18px" onkeyup="mayusculas(this)" tabindex="1" required
                            placeholder="Escribe el nombre del estado">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Crear Estado">
                </form>
            </div>
        </div>
    </div>
</div>
