@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard PosWeb2')
@section('dashboardWidth', 'width-95')
<link rel="stylesheet" href="css/styleDashboardNew.css">
<style>
    .container-float {
        border-radius: 3px;
        margin-top: -20px !important;
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
    <div class="container-fluid py-4 width-95">
        <div class="row" style="padding: 20px;">
            <div class="card">
                <div class="container-float" style="text-align: center">
                    <h4 class="card-title">Dashboard de {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</h4>
                    <p class="card-category">
                        {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                    </p>
                </div>
                <div class="container-fluid" style="padding: 20px;">
                    <div>
                        @include('Alertas.Alertas')
                    </div>

                    @if ($menus->count() == 0)
                        <div class="d-flex justify-content-center">
                            <h3><i style="color: red" class="fa fa-exclamation-triangle"></i> No Hay Menus Para Este
                                Tipo de
                                Usuario!
                            </h3>
                        </div>
                    @else
                        @foreach ($menus as $headerMenu)
                            <h5 class="pb-2 fw-normal">
                                {{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }}</h5>
                            <div class="row">
                                @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                                    <a href="{{ $detalleMenu->PivotMenu->Link }}"
                                        class="col-xxl-2 col-lg-3 col-sm-6 mb-3 menu-item">
                                        <div class="card-box overflow-hidden p-4"
                                            style="border-radius: 15px; background: #f1f5f9;">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <h6 class="text-secondary"> {{ $detalleMenu->PivotMenu->NomMenu }}
                                                </h6>
                                                <div class="icon flex-shrink-0 {{ $detalleMenu->PivotMenu->BgColor }}">
                                                    <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
