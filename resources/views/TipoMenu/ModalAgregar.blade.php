<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Menu</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearTipoMenu" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Menu</label>
                        <input type="text" id="NomTipoMenu" name="NomTipoMenu" class="form-control rounded"
                            style="line-height: 18px" placeholder="Nombre" onkeyup="mayusculas(this)" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button class="btn btn-warning">Agregar </button>
                </form>
            </div>
        </div>
    </div>
</div>
