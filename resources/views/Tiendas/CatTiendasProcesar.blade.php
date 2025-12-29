@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Procesar Cortes Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 22px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            transition: .4s;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #0d6efd;
        }

        input:checked+.slider:before {
            transform: translateX(23px);
        }
    </style>

    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <div
            class="card border-0 p-4"
            style="border-radius: 10px"
        >
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Procesar Cortes Tiendas'])
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <div
            class="content-table content-table-full card border-0 p-4"
            style="border-radius: 10px"
        >
            <form
                class="d-flex align-items-center justify-content-end flex-wrap gap-2 pb-2"
                action="/CatTiendasProcesar"
                method="get"
            >
                <div
                    class="input-group"
                    style="max-width: 300px"
                >
                    <input
                        type="text"
                        class="form-control rounded"
                        style="line-height: 18px"
                        name="filtroTienda"
                        id="filtroTienda"
                        placeholder="Buscar tienda..."
                        value="{{ request()->get('filtroTienda', '') }}"
                        autofocus
                    >
                </div>
                <div>
                    <select
                        name="filtroStatus"
                        id="filtroStatus"
                        class="form-select rounded"
                        style="line-height: 18px"
                        style="min-width: 120px;"
                    >
                        <option value="">Estatus</option>
                        <option
                            value="0"
                            {{ request()->get('filtroStatus', '') === '0' ? 'selected' : '' }}
                        >Activa</option>
                        <option
                            value="1"
                            {{ request()->get('filtroStatus', '') === '1' ? 'selected' : '' }}
                        >Inactiva</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tienda</th>
                        <th>Ciudad</th>
                        <th>Usuario</th>
                        <th>Ultima Actualización</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Procesar Cortes</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tiendas) <= 0)
                        <tr>
                            <td colspan="6">No Hay Tiendas!</td>
                        </tr>
                    @else
                        @foreach ($tiendas as $tienda)
                            <tr>
                                <td>{{ $tienda->IdTienda }}</td>
                                <td>{{ $tienda->NomTienda }}</td>
                                <td>{{ $tienda->ccNomCiudad }}</td>
                                <td
                                    class="col-usuario"
                                    data-id="{{ $tienda->IdTienda }}"
                                >
                                    @if ($tienda->ceNombre)
                                        {{ $tienda->ceNombre }} {{ $tienda->ceApellidos }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td
                                    class="col-fecha"
                                    data-id="{{ $tienda->IdTienda }}"
                                >
                                    @if ($tienda->fechaprocesarcorte)
                                        {{ \Carbon\Carbon::parse($tienda->fechaprocesarcorte)->format('d/m/Y, h:i A') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($tienda->Status == 0)
                                        <span
                                            class="tags-green"
                                            title="Activa"
                                        > Activa </span>
                                    @else
                                        <span
                                            class="tags-red"
                                            title="Inactiva"
                                        > Inactiva </span>
                                    @endif
                                </td>
                                <td>
                                    <label class="switch">
                                        <input
                                            type="checkbox"
                                            class="toggle-estado"
                                            data-id="{{ $tienda->IdTienda }}"
                                            {{ $tienda->procesarcorte == 0 ? 'checked' : '' }}
                                            {{ $tienda->Status == 1 ? 'disabled' : '' }}
                                        >
                                        <span
                                            class="slider round"
                                            style="{{ $tienda->Status == 1 ? 'opacity: 0.3;' : '' }}"
                                        ></span>
                                    </label>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".toggle-estado").forEach(input => {
                input.addEventListener("change", async function() {

                    const id = this.dataset.id;
                    const nuevoEstado = this.checked ? 0 : 1;

                    try {
                        const response = await fetch(`/CatTiendas/procesarcorte/${id}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                procesarcorte: nuevoEstado
                            })
                        });

                        const data = await response.json();

                        if (data.ok) {
                            mostrarAlertaMini("Estado actualizado");

                            const tienda = data.tienda;
                            // Usuario
                            const tdUsuario = document.querySelector(
                                `.col-usuario[data-id="${tienda.IdTienda}"]`
                            );

                            if (tdUsuario && tienda.ceNombre) {
                                tdUsuario.textContent =
                                    `${tienda.ceNombre} ${tienda.ceApellidos}`;
                            }

                            // Fecha
                            const tdFecha = document.querySelector(
                                `.col-fecha[data-id="${tienda.IdTienda}"]`
                            );

                            if (tdFecha && tienda.fechaprocesarcorte) {
                                tdFecha.textContent = formatearFecha(tienda.fechaprocesarcorte);
                            }

                        } else {
                            alert("Hubo un error guardando el estado");
                            this.checked = !this.checked;
                        }


                    } catch (error) {
                        console.error(error);
                        alert("Error de comunicación con el servidor");
                        this.checked = !this.checked; // Revertir
                    }
                });
            });
        });

        function mostrarAlertaMini(mensaje = "Cambios guardados") {
            const alerta = document.getElementById("alert-mini");
            const texto = document.getElementById("alert-mini-text");

            texto.textContent = mensaje;

            alerta.classList.remove("d-none");
            alerta.classList.add("show");

            // Ocultar después de 2 segundos
            setTimeout(() => {
                alerta.classList.add("d-none");
                alerta.classList.remove("show");
            }, 2000);
        }

        function formatearFecha(fecha) {
            const d = new Date(fecha.replace(' ', 'T'));

            return d.toLocaleString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).toUpperCase();
        }
    </script>

@endsection
