@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard PosWeb2')
@section('dashboardWidth', 'width-95')
<link rel="stylesheet" href="css/styleDashboardNew.css">
<style>
    .container-float {
        border-radius: 3px;
        margin-top: -60px !important;
        background-color: #374151;
        color: white;
        padding: 15px;
        margin: 0px 15px 0;
        padding: 0;
        padding-top: 8px;
        position: relative;
    }
</style>
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-5">
        <div class="card border-0 p-5 pb-3" style="border-radius: 10px">
            <div class="container-float mb-5" style="text-align: center">
                <p class="fs-4 text-light m-0">Dashboard de {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</p>
                <p class="text-light" style="font-weight: 500">
                    {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                </p>
            </div>
            {{-- </div>
    </div>

    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <div class="card border-0 p-4" style="border-radius: 10px"> --}}
            <div>
                @include('Alertas.Alertas')
            </div>

            @if ($menus->count() == 0)
                <div class="text-center p-4">
                    <span class="fw-bold" style="color: #FF8300">500 error</span>
                    <h1>Menú no encontrado</h1>
                    <p class="text-secondary">
                        Este usuario no cuenta con menus asignados, favor de hablar con el administador
                    </p>
                </div>
            @else
                @foreach ($menus as $headerMenu)
                    <p class="mb-0 text-uppercase mb-2" style="font-weight: 500;">
                        {{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }}
                    </p>
                    <div class="row">
                        @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                            <a href="{{ $detalleMenu->PivotMenu->Link }}"
                                class="col-xxl-2 col-lg-3 col-sm-6 mb-3 menu-item">
                                <div class="card-box overflow-hidden p-3"
                                    style="border-radius: 15px; background: #f1f5f9b0; box-shadow: 1px 1px 8px lightgray">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon flex-shrink-0 {{ $detalleMenu->PivotMenu->BgColor }}">
                                            <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                                        </div>
                                        {{-- <h6 class="text-secondary"> {{ $detalleMenu->PivotMenu->NomMenu }} </h6> --}}
                                        <h6 class="text-secondary">
                                            {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>
    </div>
@endsection
