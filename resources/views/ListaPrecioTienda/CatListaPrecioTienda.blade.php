<x-page-container title="Listas de Precios Tienda">
    <x-card-gradient-header
        icon="tags"
        title="Lista de Precios por Tienda"
        subtitle="Gestione las listas de precios asignadas a cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <form
            id="formListaP"
            action="/CatListaPrecioTienda"
            method="GET"
        >
            <x-form.form>
                <x-form.group>
                    <x-form.select
                        name="filtroIdTienda"
                        label="Tienda"
                        icon="shop"
                        col="col-md-4"
                        placeholder="Seleccione tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                        :selected="$idTienda ?? ''"
                        onchange="mostrarListas()"
                    />
                </x-form.group>
            </x-form.form>
        </form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-tags me-2"
                                style="color: #64748b;"
                            ></i>
                            Listas de Precio de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Agregue o remueva listas de precios para esta tienda
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Listas de Precio Asignadas -->
                    <div class="col-md-6">
                        <form
                            id="formRemover"
                            action="/RemoverLista"
                            method="POST"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="filtroIdTienda"
                                value="{{ $idTienda }}"
                            >

                            <div class="card-modern">
                                <div
                                    class="card-header border-bottom p-3"
                                    style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);"
                                >
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6
                                                class="fw-bold mb-0"
                                                style="color: #0f172a;"
                                            >
                                                <i
                                                    class="bi bi-tag fs-5 me-2"
                                                    style="color: #ef4444;"
                                                ></i>
                                                Listas de Precio Asignadas
                                            </h6>
                                            <small class="text-muted ms-4">{{ count($listasPrecioTienda) }}
                                                asignadas</small>
                                        </div>
                                        <span class="badge bg-danger text-danger rounded-pill bg-opacity-10 px-3 py-2">
                                            <i class="bi bi-collection me-1"></i>{{ count($listasPrecioTienda) }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="card-body p-0"
                                    style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                >
                                    @if (count($listasPrecioTienda) == 0)
                                        <div class="py-5 text-center">
                                            <i class="bi bi-tag fs-1 text-muted d-block mb-3"></i>
                                            <h6 class="text-muted">Sin listas asignadas</h6>
                                            <small class="text-muted">Agregue listas de precios desde la columna
                                                disponible</small>
                                        </div>
                                    @else
                                        <table class="table-hover table-custom mb-0 table">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="rounded-start ps-4">Lista de Precio</th>
                                                    <th
                                                        width="80"
                                                        class="rounded-end text-center"
                                                    >Remover</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($listasPrecioTienda as $listaPrecioTienda)
                                                    <tr class="menu-row">
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="bi bi-tag me-2"
                                                                    style="color: #ef4444;"
                                                                ></i>
                                                                <span
                                                                    class="fw-medium">{{ $listaPrecioTienda->NomListaPrecio }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input
                                                                class="form-check-input-modern chk-remover"
                                                                type="checkbox"
                                                                name="chkRemover[]"
                                                                value="{{ $listaPrecioTienda->IdListaPrecio }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>

                                @if (count($listasPrecioTienda) > 0)
                                    <div class="card-footer border-top bg-white p-3">
                                        <div class="d-flex justify-content-end">
                                            <button
                                                type="submit"
                                                class="btn-modern btn-remover btn-disabled"
                                                id="btnRemover"
                                                disabled
                                            >
                                                <i class="bi bi-trash me-2"></i>Remover
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Listas de Precio Disponibles -->
                    <div class="col-md-6">
                        <form
                            id="formAgregar"
                            action="/AgregarLista"
                            method="POST"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="filtroIdTienda"
                                value="{{ $idTienda }}"
                            >

                            <div class="card-modern">
                                <div
                                    class="card-header border-bottom p-3"
                                    style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);"
                                >
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6
                                                class="fw-bold mb-0"
                                                style="color: #0f172a;"
                                            >
                                                <i
                                                    class="bi bi-plus-circle fs-5 me-2"
                                                    style="color: #10b981;"
                                                ></i>
                                                Listas de Precio Disponibles
                                            </h6>
                                            <small class="text-muted ms-4">{{ count($listasPrecio) }}
                                                disponibles</small>
                                        </div>
                                        <span
                                            class="badge bg-success text-success rounded-pill bg-opacity-10 px-3 py-2">
                                            <i class="bi bi-collection me-1"></i>{{ count($listasPrecio) }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="card-body p-0"
                                    style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                >
                                    @if (count($listasPrecio) == 0)
                                        <div class="py-5 text-center">
                                            <i class="bi bi-check-circle fs-1 text-muted d-block mb-3"></i>
                                            <h6 class="text-muted">Sin listas disponibles</h6>
                                            <small class="text-muted">Todas las listas de precios están
                                                asignadas</small>
                                        </div>
                                    @else
                                        <table class="table-hover table-custom mb-0 table">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="rounded-start ps-4">Lista de Precio</th>
                                                    <th
                                                        width="80"
                                                        class="rounded-end text-center"
                                                    >Agregar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($listasPrecio as $listaPrecio)
                                                    <tr class="menu-row">
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="bi bi-tag me-2"
                                                                    style="color: #10b981;"
                                                                ></i>
                                                                <span
                                                                    class="fw-medium">{{ $listaPrecio->NomListaPrecio }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input
                                                                class="form-check-input-modern chk-agregar"
                                                                type="checkbox"
                                                                name="chkAgregar[]"
                                                                value="{{ $listaPrecio->IdListaPrecio }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>

                                @if (count($listasPrecio) > 0)
                                    <div class="card-footer border-top bg-white p-3">
                                        <div class="d-flex justify-content-end">
                                            <button
                                                type="submit"
                                                class="btn-modern btn-agregar btn-disabled"
                                                id="btnAgregar"
                                                disabled
                                            >
                                                <i class="bi bi-plus-circle me-2"></i>Agregar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para gestionar sus listas de precios</p>
            </div>
        @endif
    </x-card-gradient-header>
    <script>
        function mostrarListas() {
            document.getElementById('formListaP').submit();
        }

        // Control de botones según checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const chkRemover = document.querySelectorAll('.chk-remover');
            const chkAgregar = document.querySelectorAll('.chk-agregar');
            const btnRemover = document.getElementById('btnRemover');
            const btnAgregar = document.getElementById('btnAgregar');

            function actualizarBoton(checkboxes, boton) {
                if (!boton) return;
                const algunoSeleccionado = Array.from(checkboxes).some(cb => cb.checked);
                boton.disabled = !algunoSeleccionado;
                if (algunoSeleccionado) {
                    boton.classList.remove('btn-disabled');
                } else {
                    boton.classList.add('btn-disabled');
                }
            }

            if (chkRemover.length > 0 && btnRemover) {
                chkRemover.forEach(cb => {
                    cb.addEventListener('change', () => actualizarBoton(chkRemover, btnRemover));
                });
            }

            if (chkAgregar.length > 0 && btnAgregar) {
                chkAgregar.forEach(cb => {
                    cb.addEventListener('change', () => actualizarBoton(chkAgregar, btnAgregar));
                });
            }
        });
    </script>
</x-page-container>
