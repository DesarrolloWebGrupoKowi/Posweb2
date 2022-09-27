<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Ciudad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/CrearCiudad" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Nombre de Ciudad</label>
                            <input type="text" id="NomCiudad" name="NomCiudad" class="form-control" tabindex="1" onkeyup="mayusculas(this)" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Estado</label>
                            <select name="IdEstado" id="IdEstado" class="form-select">
                                @foreach($estados as $estado)
                                <option value="{{$estado->IdEstado}}">{{$estado->NomEstado}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="" class="form-label">Status</label>
                            <select name="Status" id="Status" class="form-select">
                                <option value="0">Activo</option>
                                <option value="1">Inactivo</option>
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" value="Crear Ciudad">
                    </form>
                </div>
            </div>
        </div>
    </div>