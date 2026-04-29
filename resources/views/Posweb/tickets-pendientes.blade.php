<div id="cardTicketsPendientes"
    class="border border-danger card p-4 mt-2"
    style="background-color: #fff5f5; display: none;">
    <div class="col-12 mt-1">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6 class="mb-1">
                    <span class="text-danger">
                        <strong>⚠️ ¡ATENCIÓN! VENTAS PENDIENTES POR ENVIAR</strong>
                    </span>
                </h6>
                <h2 class="mb-0">
                    <span id="ticketsPendientes"
                        class="text-danger"
                        style="font-weight: bold;">0</span>
                    <small class="text-muted">tickets sin enviar</small>
                </h2>
            </div>
            <div class="text-center">
                <form action="/VentaTicketDiario/Subir"
                    method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit"
                        class="btn-loading btn btn-sm"
                        style="background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.85rem; font-weight: bold; transition: all 0.3s ease; cursor: pointer;">
                        <span id="buttonIcon">
                            @include('components.icons.upload')
                        </span>
                        <br>
                        SUBIR VENTAS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let ticketsInterval = null;
    let stopTimeout = null;

    function refreshTickets() {
        fetch('/tickets/pendientes')
            .then(response => response.text())
            .then(data => {
                const card = document.getElementById('cardTicketsPendientes');
                const ticketsSpan = document.getElementById('ticketsPendientes');
                const cantidad = parseInt(data);

                if (cantidad > 0) {
                    // Mostrar la card y actualizar el contador
                    card.style.display = 'block';
                    ticketsSpan.textContent = cantidad;

                    // Añadir efecto de atención
                    card.style.animation = 'pulse 1.5s infinite';

                    console.log(`✅ ${cantidad} ticket(s) pendiente(s) - Card visible`);
                } else {
                    // Ocultar la card si no hay tickets pendientes
                    card.style.display = 'none';
                    card.style.animation = 'none';
                    ticketsSpan.textContent = '0';

                    // Detener el intervalo si ya no hay tickets
                    if (ticketsInterval) {
                        clearInterval(ticketsInterval);
                        ticketsInterval = null;
                        clearTimeout(stopTimeout);
                        console.log("⛔ Intervalo detenido - No hay tickets pendientes");
                    }
                }
            })
            .catch(error => console.error('Error actualizando tickets:', error));
    }

    // Iniciar el intervalo (cada 10 segundos)
    ticketsInterval = setInterval(refreshTickets, 10000);

    // Ejecutar inmediatamente al cargar
    refreshTickets();

    // Detener automáticamente después de 10 minutos (600000ms)
    stopTimeout = setTimeout(() => {
        if (ticketsInterval) {
            clearInterval(ticketsInterval);
            ticketsInterval = null;
            console.log("⛔ Intervalo detenido automáticamente tras 10 minutos");
        }
    }, 600000); // 600000 ms = 10 minutos
</script>

<style>
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            transform: scale(1);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            transform: scale(1.02);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            transform: scale(1);
        }
    }

    #cardTicketsPendientes {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .btn-loading:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .btn-loading:active {
        transform: translateY(0);
    }
</style>
