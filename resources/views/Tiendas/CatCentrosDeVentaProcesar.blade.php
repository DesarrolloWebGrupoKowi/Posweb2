@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Procesar Cortes Ecommerce')
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
                @include('components.title', ['titulo' => 'Procesar Cortes Ecommerce'])
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <div
            class="content-table content-table-full card border-0 p-4"
            style="border-radius: 10px"
        >
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Subinventario</th>
                        <th>Nombre Mayoreo</th>
                        <th>Usuario</th>
                        <th>Ultima Actualización</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Activa</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($centrosVenta) <= 0)
                        <tr>
                            <td colspan="6">No Hay Sucursales!</td>
                        </tr>
                    @else
                        @foreach ($centrosVenta as $centroVenta)
                            <tr>
                                <td>{{ $centroVenta->Almacen_Oracle }}</td>
                                <td>{{ $centroVenta->Descripcion }}</td>
                                <td
                                    class="col-usuario"
                                    data-id="{{ $centroVenta->Almacen_Oracle }}"
                                >
                                    {{ $centroVenta->ceNombre }} {{ $centroVenta->ceApellidos }}
                                </td>
                                <td
                                    class="col-fecha"
                                    data-id="{{ $centroVenta->Almacen_Oracle }}"
                                >
                                    @if ($centroVenta->fechaprocesarcorte)
                                        {{ \Carbon\Carbon::parse($centroVenta->fechaprocesarcorte)->format('d/m/Y, h:i A') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($centroVenta->Status == 1)
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
                                            data-id="{{ $centroVenta->Almacen_Oracle }}"
                                            {{ $centroVenta->procesarcorte == 0 ? 'checked' : '' }}
                                            {{ $centroVenta->Status == 0 ? 'disabled' : '' }}
                                        >
                                        <span
                                            class="slider round"
                                            style="{{ $centroVenta->Status == 0 ? 'opacity: 0.3;' : '' }}"
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
                    console.log(id, nuevoEstado);

                    try {
                        const response = await fetch(`/CatCentrosVenta/procesarcorte/${id}`, {
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
                            const centroVenta = data.centroVenta;
                            // Usuario
                            const tdUsuario = document.querySelector(
                                `.col-usuario[data-id="${centroVenta.Almacen_Oracle}"]`
                            );

                            if (tdUsuario && centroVenta.ceNombre) {
                                tdUsuario.textContent =
                                    `${centroVenta.ceNombre} ${centroVenta.ceApellidos}`;
                            }

                            // Fecha
                            const tdFecha = document.querySelector(
                                `.col-fecha[data-id="${centroVenta.Almacen_Oracle}"]`
                            );

                            if (tdFecha && centroVenta.fechaprocesarcorte) {
                                tdFecha.textContent = formatearFecha(centroVenta
                                    .fechaprocesarcorte);
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
