@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Buscar Articulo')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
            @include('components.title', [
                'titulo' => 'Descargar Articulo',
                'options' => [['name' => 'Catálogo de Articulos', 'value' => '/CatArticulos']],
            ])
        </div>
        <form id="form" action="{{ route('EnviarArticulo') }}" target="iframeArticulo">
            @csrf
            <div class="d-flex align-items-center justify-content-end gap-4">
                <div>
                    <input class="form-check-input" type="radio" name="radioBuscar" id="radioCodigo" value="radioCodigo"
                        checked>
                    <label class="form-check-label" for="radioCodigo">
                        Código
                    </label>
                </div>
                <div>
                    <input class="form-check-input" type="radio" name="radioBuscar" id="radioNombre" value="radioNombre">
                    <label class="form-check-label" for="radioNombre">
                        Nombre
                    </label>
                </div>
                <div class="input-group" style="max-width: 300px">
                    <input class="form-control" type="text" name="filtroArticulo" placeholder="Busqueda" required>
                    <div class="input-group-append">
                        <button class="input-group-text Buscar">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </div>

                {{-- <div class="col-auto">
                    <div class="mb-3">
                        <input class="form-control" type="text" name="filtroArticulo" placeholder="Busqueda" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn"><span class="material-icons">search</span></button>
                </div> --}}
            </div>

            {{-- <hr> --}}
            <div class="row pt-4">
                <div class="col-5 card mb-3" style="border-radius: 20px">
                    <label class="mt-2" style="text-align: center"><strong>Articulo</strong></label>
                    <hr>
                    <iframe name="iframeArticulo" height="400"></iframe>
                </div>
                <div class="col-1">

                </div>
                <div class="col-6 card mb-3" id="divMostrar" style="border-radius: 20px">
                    <label class="mt-2" style="text-align: center" for="divMostrar"><strong>Descargar
                            Articulo</strong></label>
                    <hr>
                    <iframe name="iframeMostrar" id="iframeMostrar" height="400"></iframe>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-warning" onclick="send()">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>
            <input type="hidden" name="banNomArticulo" id="banNomArticulo">
            <input type="hidden" name="banCodArticulo" id="banCodArticulo">
            <input type="hidden" name="banCodAmece" id="banCodAmece">
            <input type="hidden" name="banUOM" id="banUOM">
            <input type="hidden" name="banPeso" id="banPeso">
            <input type="hidden" name="banTercero" id="banTercero">
            <input type="hidden" name="banCodEtiqueta" id="banCodEtiqueta">
            <input type="hidden" name="banPrecioRecorte" id="banPrecioRecorte">
            <input type="hidden" name="banFactor" id="banFactor">
            <input type="hidden" name="banIdFamilia" id="banIdFamilia">
            <input type="hidden" name="banIdGrupo" id="banIdGrupo">
            <input type="hidden" name="banIva" id="banIva">
            <input type="hidden" name="banIdTipoArticulo" id="banIdTipoArticulo">

        </form>
    </div>


    @include('Articulos.ModalArticulo')
    <script>
        function send() {
            var frame = document.getElementById('iframeMostrar');
            var txtNomArticulo = frame.contentWindow.document.getElementById('txtNomArticulo').value;
            var txtCodArticulo = frame.contentWindow.document.getElementById('txtCodArticulo').value;
            var txtCodAmece = frame.contentWindow.document.getElementById('txtCodAmece').value;
            var txtUOM = frame.contentWindow.document.getElementById('txtUOM').value;
            var txtPeso = frame.contentWindow.document.getElementById('txtPeso').value;
            var txtTercero = frame.contentWindow.document.getElementById('txtTercero').value;
            var txtPrecioRecorte = frame.contentWindow.document.getElementById('txtPrecioRecorte').value;
            var txtFactor = frame.contentWindow.document.getElementById('txtFactor').value;
            var txtIdFamilia = frame.contentWindow.document.getElementById('txtIdFamilia').value;
            var txtIdGrupo = frame.contentWindow.document.getElementById('txtIdGrupo').value;
            var txtIva = frame.contentWindow.document.getElementById('txtIva').value;
            var idTipoArticulo = frame.contentWindow.document.getElementById('idTipoArticulo').value;

            var banNomArticulo = document.getElementById('banNomArticulo').value = txtNomArticulo;
            var banCodArticulo = document.getElementById('banCodArticulo').value = txtCodArticulo;
            var banCodAmece = document.getElementById('banCodAmece').value = txtCodAmece;
            var banUOM = document.getElementById('banUOM').value = txtUOM;
            var banPeso = document.getElementById('banPeso').value = txtPeso;
            var banTercero = document.getElementById('banTercero').value = txtTercero;
            var banPrecioRecorte = document.getElementById('banPrecioRecorte').value = txtPrecioRecorte;
            var banFactor = document.getElementById('banFactor').value = txtFactor;
            var banIdFamilia = document.getElementById('banIdFamilia').value = txtIdFamilia;
            var banIdGrupo = document.getElementById('banIdGrupo').value = txtIdGrupo;
            var banIva = document.getElementById('banIva').value = txtIva;
            var banIdTipoArticulo = document.getElementById('banIdTipoArticulo').value = idTipoArticulo;

            //alert(banCodAmece);
            document.getElementById('form').action = "/LigarArticulo";
            document.getElementById('form').method = "POST";
            document.getElementById('form').target = "";
            document.getElementById('form').submit();

        }
    </script>
@endsection
