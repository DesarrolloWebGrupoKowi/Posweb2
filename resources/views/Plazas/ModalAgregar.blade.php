<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Plaza</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearPlaza" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de la Plaza</label>
                        <input type="text" id="NomPlaza" name="NomPlaza" class="form-control" tabindex="1"
                            onkeyup="mayusculas(this)" required placeholder="Escribe el nombre de la plaza">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Ciudad</label>
                        <select name="IdCiudad" id="IdCiudad" class="form-select">
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->IdCiudad }}">{{ $ciudad->NomCiudad }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Crear Plaza">
                </form>
            </div>
        </div>
    </div>
</div>
