<!-- Modal Editar-->
<div class="modal fade" id="ModalEditar{{$ciudad->IdCiudad}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Ciudad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarCiudad/{{$ciudad->IdCiudad}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de Ciudad</label>
                        <input type="text" id="NomCiudad" name="NomCiudad" class="form-control" tabindex="1" value="{{$ciudad->NomCiudad}}" onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Estado</label>
                        <select name="IdEstado" id="IdEstado" class="form-select">
                            @foreach($estados as $estado)
                            <option {!! $estado->IdEstado == $ciudad->IdEstado ? 'selected' : '' !!} value="{{$estado->IdEstado}}">{{$estado->NomEstado}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="" class="form-label">Status</label>
                        <select name="Status" id="Status" class="form-select">
                            <option {!! $ciudad->Status == 0 ? 'selected' : '' !!} value="0">Activo</option>
                            <option {!! $ciudad->Status == 1 ? 'selected' : '' !!} value="1">Inactivo</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary" value="Editar Ciudad">
                </form>
            </div>
        </div>
    </div>
</div>