@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard PosWeb2')
@section('dashboardWidth', 'width-95')
<link rel="stylesheet" href="css/styleDashboardNew.css">
@section('contenido')
    <div class="container-fluid py-4 width-95">
        <div>
            <h2>Dashboard de {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</h2>
            <p class="text-secondary fw-semibold" style="font-size: 17px">
                {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}</p>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        @if ($menus->count() == 0)
            <div class="d-flex justify-content-center">
                <h3><i style="color: red" class="fa fa-exclamation-triangle"></i> No Hay Menus Para Este Tipo de
                    Usuario!
                </h3>
            </div>
        @else
            @foreach ($menus as $headerMenu)
                <h5 class="pb-2 fw-normal">{{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }}</h5>
                <div class="row">
                    @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                        <a href="{{ $detalleMenu->PivotMenu->Link }}" class="col-xxl-2 col-lg-3 col-sm-6 mb-3 menu-item">
                            <div class="card-box bg-white overflow-hidden p-4" style="border-radius: 15px">
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
@endsection
