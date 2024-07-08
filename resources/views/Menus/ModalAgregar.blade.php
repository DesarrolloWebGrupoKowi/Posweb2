<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Menú Posweb</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearMenuPosweb" method="POST">
                    @csrf
                    <div class="d-flex gap-2 flex-column flex-sm-row">
                        <div style="flex: 1">
                            <label for="" class="form-label">Nombre de Menú</label>
                            <input type="text" id="NomMenu" name="NomMenu" class="form-control rounded"
                                style="line-height: 18px" onkeyup="mayusculas(this)" required
                                placeholder="Escribe el nombre del menu">
                        </div>
                        <div class="mb-3" style="flex: 1">
                            <label for="" class="form-label">Menú</label>
                            <select name="IdTipoMenu" id="IdTipoMenu" class="form-select rounded"
                                style="line-height: 18px">
                                @foreach ($tipoMenus as $tipoMenu)
                                    <option value="{{ $tipoMenu->IdTipoMenu }}">{{ $tipoMenu->NomTipoMenu }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Link</label>
                        <input class="form-control rounded" style="line-height: 18px" type="text" name="Link"
                            id="Link" placeholder="/CatMenuPosweb" required>
                    </div>
                    <div class="d-flex gap-2 flex-column flex-sm-row">
                        <div style="flex: 1">
                            <label for="" class="form-label">Icono</label>
                            <input class="form-control rounded" style="line-height: 18px" type="text" name="Icono"
                                id="Icono" placeholder="fa fa-icons" required>
                        </div>
                        <div style="flex: 1" class="mb-3">
                            <label for="" class="form-label">Background Color</label>
                            <input class="form-control rounded" style="line-height: 18px" type="text" name="BgColor"
                                id="BgColor" placeholder="bg-orange" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-warning">Guardar </button>
                </form>
            </div>
        </div>
    </div>
</div>
