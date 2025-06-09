<div class="mb-3">
    <form action="{{ url('importExcel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="excel_file">
        <button type="submit">Importar Excel</button>
    </form>
</div>

