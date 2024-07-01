<form id="form-paginate" class="d-flex align-items-center justify-content-end p-0 gap-2 text-secondary" action="/"
    style="font-weight: 500; font-size: small;">
    <label class="d-flex align-items-center gap-1">
        Mostrar
        <select name="paginate" class="form-select form-select-sm" id="number-pagination">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="100">100</option>
            <option value="-1">Todos</option>
        </select>
        <span class="text-nowrap">registros por página</span>
    </label>
</form>


<script>
    document.addEventListener('DOMContentLoaded', e => {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const entries = urlParams.entries();

        for (const entry of entries) {
            if (entry[0] == 'paginate') {
                document.getElementById('number-pagination').value = entry[1];
            }
        }
    })

    document.addEventListener('change', e => {
        if (e.target.matches('#number-pagination')) {
            const form = document.getElementById('form-paginate');
            const url = location.href;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const entries = urlParams.entries();

            for (const entry of entries) {
                if (entry[0] != 'paginate') {
                    let input = document.createElement('input');
                    input.type = "hidden";
                    input.name = entry[0];
                    input.value = entry[1];
                    form.appendChild(input);
                }
            }

            form.setAttribute('action', url);
            form.submit();
        }
    })
</script>
