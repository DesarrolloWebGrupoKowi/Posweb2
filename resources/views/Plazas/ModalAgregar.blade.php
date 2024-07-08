<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Plaza</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearPlaza" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de la Plaza</label>
                        <input type="text" id="NomPlaza" name="NomPlaza" class="form-control rounded"
                            style="line-height: 18px" tabindex="1" onkeyup="mayusculas(this)" required
                            placeholder="Escribe el nombre de la plaza">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Ciudad</label>
                        <select name="IdCiudad" id="IdCiudad" class="form-select rounded" style="line-height: 18px">
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->IdCiudad }}">{{ $ciudad->NomCiudad }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Crear Plaza">
                </form>
            </div>
        </div>
    </div>
</div>
