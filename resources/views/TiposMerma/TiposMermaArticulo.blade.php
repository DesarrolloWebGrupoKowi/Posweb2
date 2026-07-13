<x-page-container title="Artículos por Tipo de Merma">
    <x-card-gradient-header
        icon="exclamation-triangle"
        title="Artículos por Tipo de Merma"
        subtitle="Gestione los artículos asignados a cada tipo de merma"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/TiposMermaArticulo"
            id="formTipoMermaArticulo"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTipoMerma"
                    label="Tipo de merma"
                    icon="exclamation-triangle"
                    col="col-md-4"
                    placeholder="Seleccione tipo de merma"
                    :options="$tiposMerma->pluck('NomTipoMerma', 'IdTipoMerma')->toArray()"
                    :selected="$idTipoMerma ?? ''"
                    onchange="document.getElementById('formTipoMermaArticulo').submit()"
                    :autofocus="true"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTipoMerma))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-box-seam me-2"
                                style="color: #64748b;"
                            ></i>
                            Artículos de
                            {{ $tiposMerma->where('IdTipoMerma', $idTipoMerma)->first()->NomTipoMerma ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Gestione los artículos asignados a este tipo de merma
                        </p>
                    </div>
                    <button
                        class="btn-modern btn-agregar"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarArticulo"
                    >
                        <i class="bi bi-plus-circle me-2"></i>Agregar Artículo
                    </button>
                </div>

                <!-- Tabla de artículos -->
                <div class="card-modern">
                    <div
                        class="card-header border-bottom p-3"
                        style="background: #f8fafc;"
                    >
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span
                                        class="input-group-text"
                                        style="background: white; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                    >
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarArticulo"
                                        class="form-control border-start-0"
                                        style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                        placeholder="Filtrar artículos..."
                                    >
                                </div>
                            </div>
                            <div class="col-md-8 text-end">
                                <span class="badge bg-primary text-primary rounded-pill bg-opacity-10 px-3 py-2">
                                    <i class="bi bi-collection me-1"></i>
                                    <span id="contadorArticulos">{{ $tiposMermaArticulo->count() }}</span> artículos
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table
                            class="table-hover table-custom mb-0 table"
                            id="tablaArticulos"
                        >
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th>Tipo Merma</th>
                                    <th width="100">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tiposMermaArticulo->count() == 0)
                                    <tr id="sinArticulos">
                                        <td colspan="4">
                                            <div class="py-5 text-center">
                                                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                                                <h6 class="text-muted">Sin artículos registrados</h6>
                                                <small class="text-muted">No hay artículos asignados a este tipo de
                                                    merma</small>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($tiposMermaArticulo as $tipoMermaArticulo)
                                        <tr class="menu-row fila-articulo">
                                            <td
                                                style="font-weight: 600; color: #0f172a;"
                                                class="codigo-articulo"
                                            >
                                                {{ $tipoMermaArticulo->CodArticulo }}
                                            </td>
                                            <td class="nombre-articulo">{{ $tipoMermaArticulo->NomArticulo }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $tipoMermaArticulo->NomTipoMerma }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    class="btn-table-action btn-table-delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalEliminarArticuloTipoMerma{{ $tipoMermaArticulo->CodArticulo }}"
                                                    title="Eliminar artículo"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @foreach ($tiposMermaArticulo as $tipoMermaArticulo)
                            @include('TiposMerma.ModalEliminarArticuloTipoMerma')
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-exclamation-triangle display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione un tipo de merma</h5>
                <p class="text-muted">Elija un tipo de merma del filtro para gestionar sus artículos</p>
            </div>
        @endif
    </x-card-gradient-header>

    <!-- Modal Agregar Artículo -->
    <div
        class="modal fade"
        id="ModalAgregarArticulo"
        tabindex="-1"
        aria-labelledby="ModalAgregarArticuloLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog"
            style="margin-top: 10vh;"
        >
            <div
                class="modal-content border-0 shadow"
                style="border-radius: 10px; overflow: hidden;"
            >
                <!-- Modal Header -->
                <div
                    class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
                >
                    <h5
                        class="mb-0 text-white"
                        style="font-weight: 600; font-size: 1.1rem;"
                        id="ModalAgregarArticuloLabel"
                    >
                        <div class="d-flex align-items-center gap-3 pb-2">
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                            >
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <span>Agregar Artículo</span>
                        </div>
                    </h5>
                </div>

                <!-- Modal Body -->
                <form
                    action="/AgregarArticuloMerma/{{ $idTipoMerma }}"
                    method="POST"
                >
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label
                                for="codArticulo"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Código o Artículo
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-upc"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    list="articulos"
                                    name="codArticulo"
                                    id="codArticulo"
                                    placeholder="Código o Artículo"
                                    autocomplete="off"
                                    required
                                >
                                <datalist id="articulos">
                                    @foreach ($articulos as $articulo)
                                        <option value="{{ $articulo->CodArticulo }}">{{ $articulo->NomArticulo }}
                                        </option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                        <button
                            type="button"
                            class="btn d-flex align-items-center gap-1"
                            data-bs-dismiss="modal"
                            style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                        >
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn d-flex align-items-center gap-1"
                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                        >
                            <i class="bi bi-floppy"></i>
                            Agregar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMermaArticulo').submit();
        });

        // Filtro de artículos en tiempo real
        document.getElementById('buscarArticulo').addEventListener('input', function() {
            const filtro = this.value.toLowerCase().trim();
            const filas = document.querySelectorAll('.fila-articulo');
            let visibles = 0;

            filas.forEach(fila => {
                const codigo = fila.querySelector('.codigo-articulo').textContent.toLowerCase();
                const nombre = fila.querySelector('.nombre-articulo').textContent.toLowerCase();

                if (codigo.includes(filtro) || nombre.includes(filtro)) {
                    fila.style.display = '';
                    visibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            document.getElementById('contadorArticulos').textContent = visibles;

            // Mostrar/ocultar mensaje sin resultados
            const sinArticulos = document.getElementById('sinArticulos');
            if (visibles === 0 && filas.length > 0) {
                if (!sinArticulos) {
                    const tbody = document.getElementById('tablaArticulos').querySelector('tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'sinArticulos';
                    tr.innerHTML = `
                        <td colspan="4">
                            <div class="py-5 text-center">
                                <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                                <h6 class="text-muted">Sin resultados</h6>
                                <small class="text-muted">No se encontraron artículos con ese filtro</small>
                            </div>
                        </td>`;
                    tbody.appendChild(tr);
                }
            } else if (sinArticulos && visibles > 0) {
                sinArticulos.remove();
            }
        });
    </script>
</x-page-container>
