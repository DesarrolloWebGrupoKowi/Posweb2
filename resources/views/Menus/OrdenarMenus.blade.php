<x-page-container title="Ordenar Menús">
    <x-card-gradient-header
        icon="sort-numeric-down-alt"
        title="Ordenar Menús"
        subtitle="Configure el orden de visualización de los menús por tipo de usuario"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/OrdenarMenus"
            id="formTipoUsuario"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTipoUsuario"
                    label="Tipo de usuario"
                    icon="person-badge"
                    col="col-md-4"
                    placeholder="Seleccione tipo de usuario"
                    :options="$tiposUsuario->pluck('NomTipoUsuario', 'IdTipoUsuario')->toArray()"
                    :selected="$idTipoUsuario ?? '0'"
                    onchange="document.getElementById('formTipoUsuario').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if ($idTipoUsuario)
            @if ($menus->count())
                <div class="p-4">
                    <div
                        class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                        <div>
                            <h5 class="section-content-title">
                                <i
                                    class="bi bi-list-ol me-2"
                                    style="color: #64748b;"
                                ></i>
                                Configuración de orden para {{ $tipoUsuarioFind->NomTipoUsuario ?? '' }}
                            </h5>
                            <p class="section-content-subtitle">
                                Modifique las posiciones numéricas para reordenar los menús
                            </p>
                        </div>
                        <a
                            href="/DatMenuTipoUsuario?IdTipoUsuario={{ $idTipoUsuario }}"
                            target="_blank"
                            class="btn-modern btn-outline-modern"
                        >
                            <i class="bi bi-plus-circle me-2"></i>Asignar Menús
                        </a>
                    </div>

                    <ul
                        class="nav nav-pills-modern mb-0 gap-1"
                        id="menuTabs"
                        role="tablist"
                    >
                        @foreach ($menus as $index => $menu)
                            <li
                                class="nav-item"
                                role="presentation"
                            >
                                <button
                                    class="nav-link {{ $index === 0 ? 'active' : '' }} rounded-pill px-3 py-1"
                                    id="tab-{{ Str::slug($menu->NomTipoMenu) }}"
                                    data-bs-toggle="pill"
                                    data-bs-target="#content-{{ Str::slug($menu->NomTipoMenu) }}"
                                    type="button"
                                    role="tab"
                                >
                                    {{ $menu->NomTipoMenu }}
                                    <span class="badge bg-primary text-primary ms-1 bg-opacity-10">
                                        {{ count($menu->Ordenar) }}
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div
                        class="tab-content"
                        id="menuTabsContent"
                    >
                        @foreach ($menus as $index => $menu)
                            <div
                                class="tab-pane fade {{ $index === 0 ? 'show active' : '' }} mt-4"
                                id="content-{{ Str::slug($menu->NomTipoMenu) }}"
                                role="tabpanel"
                                style="border-radius: 16px;"
                            >
                                <form
                                    action="/EditarPosicionMenu"
                                    method="POST"
                                >
                                    @csrf
                                    <input
                                        type="hidden"
                                        name="idTipoUsuario"
                                        value="{{ $idTipoUsuario }}"
                                    >

                                    <div
                                        class="card overflow-hidden border-0 shadow-sm"
                                        style="border-radius: 16px;"
                                    >
                                        <div class="card-body p-0">
                                            <table class="table-hover table-custom mb-0 table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="rounded-start ps-4">Menú</th>
                                                        <th
                                                            width="120"
                                                            class="rounded-end text-center"
                                                        >Posición</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($menu->Ordenar as $nMenu)
                                                        <tr class="menu-row">
                                                            <td class="ps-4">
                                                                <i
                                                                    class="bi bi-dot me-2"
                                                                    style="color: #3b82f6;"
                                                                ></i>
                                                                {{ $nMenu->PivotMenu->NomMenu }}
                                                            </td>
                                                            <td class="text-center">
                                                                <input
                                                                    style="width: 65px; height: 28px; padding: 2px 4px; text-align: center; font-size: 0.8rem; border: 1px solid #e2e8f0; border-radius: 20px; transition: all 0.2s ease;"
                                                                    class="mx-auto"
                                                                    type="number"
                                                                    min="1"
                                                                    name="posicion[{{ $nMenu->PivotMenu->IdMenu }}]"
                                                                    value="{{ $nMenu->Posicion }}"
                                                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'; this.style.outline='none';"
                                                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                                                                >
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer border-top bg-white p-2 py-4">
                                            <div class="d-flex justify-content-end">
                                                <button
                                                    type="submit"
                                                    class="btn-modern btn-agregar btn-sm"
                                                >
                                                    <i class="bi bi-save me-2"></i>Guardar Cambios
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-5 text-center">
                    <i
                        class="bi bi-inbox display-1"
                        style="color: #cbd5e1;"
                    ></i>
                    <h5
                        style="color: #0f172a;"
                        class="mt-3"
                    >Sin menús asignados</h5>
                    <p class="text-muted mb-4">Este tipo de usuario no cuenta con menús configurados</p>
                    <a
                        href="/DatMenuTipoUsuario?IdTipoUsuario={{ $idTipoUsuario }}"
                        target="_blank"
                        class="btn-modern btn-agregar"
                    >
                        <i class="bi bi-plus-circle me-2"></i>Asignar Menús
                    </a>
                </div>
            @endif
        @else
            <div class="p-5 text-center">
                <i
                    class="bi bi-person-badge display-1"
                    style="color: #cbd5e1;"
                ></i>
                <h5
                    style="color: #0f172a;"
                    class="mt-3"
                >Seleccione un tipo de usuario</h5>
                <p class="text-muted">Elija un tipo de usuario del filtro para configurar el orden de sus menús</p>
            </div>
        @endif
    </x-card-gradient-header>
</x-page-container>
