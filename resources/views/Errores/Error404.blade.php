@extends('PlantillaBase.masterbladeNewStyle')
@section('title', '404 Pagina no encontrada')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="d-flex justify-content-center align-items-center align-content-center gap-4 gap-md-0 flex-wrap flex-md-nowrap p-5 flex-row-reverse"
        style="min-height: 80vh; max-width: 1024px; margin: 0 auto;">

        <div class="">
            <img src="/img/404.svg" alt="404" style="max-width: 400px;">
        </div>

        <div class="pe-5">
            <span class="fw-bold" style="color: #FF8300">404 error</span>
            <h1>Pagina no encontrada</h1>
            <p class="text-secondary">Es posible que haya escrito mal la dirección o que la página se haya movido</p>
            <div class="pt-2">
                @guest
                    <a href="/" class="btn btn-warning">Ir al inicio de sesión</a>
                @else
                    <a href="/" class="btn btn-warning">Ir al inicio</a>
                @endguest
            </div>
        </div>
    </div>
@endsection
