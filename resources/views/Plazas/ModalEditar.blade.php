<!-- Modal Editar Plaza-->
<div class="modal fade" id="ModalEditar{{ $plaza->IdPlaza }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Plaza</h5>
            </div>
            <div class="modal-body">
                <form action="/EditarPlaza/{{ $plaza->IdPlaza }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre de la Plaza</label>
                        <input type="text" id="NomPlaza" name="NomPlaza" class="form-control rounded"
                            style="line-height: 18px" tabindex="1" value="{{ $plaza->NomPlaza }}"
                            onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Ciudad</label>
                        <select name="IdCiudad" id="IdCiudad" class="form-select rounded" style="line-height: 18px">
                            @foreach ($ciudades as $ciudad)
                                <option {!! $ciudad->IdCiudad == $plaza->IdCiudad ? 'selected' : '' !!} value="{{ $ciudad->IdCiudad }}">{{ $ciudad->NomCiudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="" class="form-label">Estatus</label>
                        <select name="Status" id="Status" class="form-select rounded" style="line-height: 18px">
                            <option {!! $plaza->Status == 0 ? 'selected' : '' !!} value="0">Activa</option>
                            <option {!! $plaza->Status == 1 ? 'selected' : '' !!} value="1">Inactiva</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Editar Plaza">
                </form>
            </div>
        </div>
    </div>
</div>
