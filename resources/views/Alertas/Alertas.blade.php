<!---Registrado Corectamente -->
@if (Session::has('msjAdd'))
    <div class="alert alert-success" role="alert" id="alerta">
        <strong><i class="fa fa-check"></i> {{ Session::get('msjAdd') }} </strong>
    </div>
@endif

<!-- Eliminado Correctamente --->
@if (Session::has('msjdelete'))
    <div class="alert alert-danger" role="alert" id="alerta">
        <strong><i class="fa fa-exclamation-triangle"></i> {{ Session::get('msjdelete') }}</strong>
    </div>
@endif

<!-- Editado Correctamente -->
@if (Session::has('msjupdate'))
    <div class="alert alert-warning mt-2" role="alert" id="alerta">
        <strong><i class="fa fa-exclamation-triangle"></i> {{ Session::get('msjupdate') }} </strong>
    </div>
@endif

<!-- Alerta para Validar Usuario -->
@if (Session::has('msjErrorValida'))
    <div class="alert alert-danger mt-2" role="alert" id="alerta">
        <strong><i class="fa fa-exclamation-triangle"></i> {{ Session::get('msjErrorValida') }} </strong>
    </div>
@endif

<!-- Eliminado Logico -->
@if (Session::has('msjEliminadoLogico'))
    <div class="alert alert-warning mt-2" role="alert" id="alerta">
        <strong><i class="fa fa-exclamation-triangle"></i> {{ Session::get('msjEliminadoLogico') }} </strong>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger mt-2" role="alert" id="alerta">
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="fa fa-exclamation-triangle"></i> <strong>{{ $error }}</strong></li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('Pos'))
    <div class="alert alert-danger alert-dismissible fade show shadow mt-2" role="alert" id="alerta">
        <i class="fa fa-exclamation-triangle"></i>
        <strong>{{ Session::get('Pos') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (Session::has('IdentificadorSparh'))
    <div class="alert IdentificadorSparh">
        <div style="text-align: right">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="dinCambio">
            <h4> {{ Session::get('IdentificadorSparh') }}</h4>
        </div>
    </div>
@endif

@if (Session::has('Cambio'))
    <div class="alert cambio">
        <div style="text-align: right">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="dinCambio">
            <h2>Cambio</h2>
            <h1><i class="fa fa-usd"></i> {{ Session::get('Cambio') }}</h1>
            <h6>00/100 M.N</h6>
        </div>
    </div>
@endif

@if (Session::has('AlertPedido'))
    <div class="alert pedidoAlert shadow-lg">
        <div style="text-align: right">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="dinCambio">
            <h6><i class="fa fa-exclamation-triangle"></i> {{ Session::get('AlertPedido') }}</h6>
        </div>
    </div>
@endif

@if (Session::has('PedidoGuardado'))
    <div class="alert pedidoAlert shadow-lg">
        <div style="text-align: right">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="dinCambio">
            <h6 style="color: black;"><i style="color: green" class="fa fa-check-circle"></i>
                {{ Session::get('PedidoGuardado') }}</h6>
        </div>
    </div>
@endif

@if (Session::has('Factura'))
    <div class="alert cambio">
        <div style="text-align: right">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="dinCambio">
            <h1>{{ Session::get('Factura') }}</h1>
        </div>
    </div>
@endif
