<!--Modal Editar Estado-->
<div class="modal fade" id="ModalEditar{{ $estado->IdEstado }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $estado->NomEstado }}</h5>
            </div>
            <div class="modal-body">
                <form action="EditarEstado/{{ $estado->IdEstado }}" method="POST">
                    @csrf
                    <div>
                        <label for="" class="form-label">Status</label>
                        <select name="Status" id="Status" class="form-select  rounded" style="line-height: 18px">
                            <option {!! $activo == '0' ? 'selected' : '' !!} value="0">Activo</option>
                            <option {!! $activo == '1' ? 'selected' : '' !!} value="1">Inactivo</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Editar">
                </form>
            </div>
        </div>
    </div>
</div>
