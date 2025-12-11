<div class="position-absolute top-5 end-0 translate-middle badge rounded-pill bg-danger"
    style="transform: translate(-50%, 50%); z-index: 10; font-size: .9rem;">
    <span id="ticketsPendientes"></span>
</div>

<script>
    let ticketsInterval = null;
    let stopTimeout = null;

    function refreshTickets() {
        fetch('/tickets/pendientes')
            .then(response => response.text())
            .then(data => {
                if (data > 0) {
                    let container = document.getElementById('ticketsPosContainer');
                    container.classList.remove('d-none');
                    document.getElementById('ticketsPendientes').textContent = data;
                } else {
                    clearInterval(ticketsInterval);
                    ticketsInterval = null;
                    clearTimeout(stopTimeout);
                    console.log("⛔ Intervalo detenido porque data es 0");
                }
                console.log(data);
            })
            .catch(error => console.error('Error actualizando tickets:', error));
    }

    // Actualizar cada 10 segundos (cámbialo si deseas)
    ticketsInterval = setInterval(refreshTickets, 3000);

    // Detener automáticamente después de 1 minuto (60000ms)
    stopTimeout = setTimeout(() => {
        if (ticketsInterval) {
            clearInterval(ticketsInterval);
            ticketsInterval = null;
            console.log("⛔ Intervalo detenido automáticamente tras 3 minuto");
        }
    }, 180000);
</script>
