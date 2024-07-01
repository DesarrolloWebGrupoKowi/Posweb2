<form id="search-form" class="d-flex align-items-center justify-content-end gap-4 pb-2" action="/CatClientes">
    <div class="d-flex align-items-center gap-2">
        <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
        <input class="form-control rounded" style="line-height: 18px" type="text" name="txtFiltro" id="txtFiltro"
            value="{{ $txtFiltro }}" autofocus placeholder="{{ isset($placeholder) ? $placeholder : '' }}">
    </div>
</form>

<script>
    document.addEventListener('submit', e => {
        if (e.target.matches('#search-form')) {
            const form = document.getElementById('search-form');
            const url = location.href;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const entries = urlParams.entries();

            for (const entry of entries) {
                if (entry[0] != 'txtFiltro') {
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
