@extends('PlantillaBase.masterTailwind')
@section('title', 'Dashboard PosWeb2')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="flex p-4 gap-4 h-[100vh] bg-[#DFECF1]">

        {{-- NAV BAR LEFT --}}
        <div class="bg-slate-950 text-slate-50 rounded-xl w-20 h-auto p-4 flex flex-col justify-center items-center">
            <div class="flex flex-col items-center mb-4 space-y-2">

                <a href="/Dashboard" data-tooltip-target="tooltip-download" data-tooltip-placement="left"
                    class="flex justify-center items-center w-[52px] h-[52px] text-gray-300 hover:text-gray-900 rounded-lg hover:bg-gray-50">
                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11H4m15.5 5a.5.5 0 0 0 .5-.5V8a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44l-1.436-2.12a1 1 0 0 0-.828-.44H8a1 1 0 0 0-1 1M4 9v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44L9.985 8.44A1 1 0 0 0 9.157 8H5a1 1 0 0 0-1 1Z" />
                    </svg>

                    <span class="sr-only">Menus</span>
                </a>
                <div id="tooltip-download" role="tooltip"
                    class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
                    Menus
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <a href="/DashboardGrafics" data-tooltip-target="tooltip-share" data-tooltip-placement="left"
                    class="flex justify-center items-center w-[52px] h-[52px] text-gray-300 hover:text-gray-900 rounded-lg hover:bg-gray-50">
                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" />
                    </svg>

                    <span class="sr-only">Administración</span>
                </a>
                <div id="tooltip-share" role="tooltip"
                    class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
                    Dashboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                {{--
                <a href="/DashboardInterfaz" data-tooltip-target="tooltip-print" data-tooltip-placement="left"
                    class="flex justify-center items-center w-[52px] h-[52px] text-gray-300 hover:text-gray-900 rounded-lg hover:bg-gray-50">
                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                    </svg>

                    <span class="sr-only">Interfaz</span>
                </a>
                <div id="tooltip-print" role="tooltip"
                    class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
                    Interfaz
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <a href="/DashboardReportes" data-tooltip-target="tooltip-copy" data-tooltip-placement="left"
                    class="flex justify-center items-center w-[52px] h-[52px] text-gray-300 hover:text-gray-900 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 18 20">
                        <path
                            d="M5 9V4.13a2.96 2.96 0 0 0-1.293.749L.879 7.707A2.96 2.96 0 0 0 .13 9H5Zm11.066-9H9.829a2.98 2.98 0 0 0-2.122.879L7 1.584A.987.987 0 0 0 6.766 2h4.3A3.972 3.972 0 0 1 15 6v10h1.066A1.97 1.97 0 0 0 18 14V2a1.97 1.97 0 0 0-1.934-2Z" />
                        <path
                            d="M11.066 4H7v5a2 2 0 0 1-2 2H0v7a1.969 1.969 0 0 0 1.933 2h9.133A1.97 1.97 0 0 0 13 18V6a1.97 1.97 0 0 0-1.934-2Z" />
                    </svg>
                    <span class="sr-only">Reportes</span>
                </a>
                <div id="tooltip-copy" role="tooltip"
                    class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
                    Reportes
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div> --}}

            </div>
        </div>

        {{-- overflow-y-scroll --}}
        <div class="flex flex-col gap-4 rounded-lg p-4" style="width: 100%;">

            {{-- <div class=" gap-4 overflow-y-auto">
                @foreach ($menus as $headerMenu)
                    <div class="mb-4">
                        <h1 class="text-4xl font-bold">{{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }} </h1>
                    </div>

                    <div class="flex flex-wrap gap-4 mb-12">
                        @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                            <a href="{{ $detalleMenu->PivotMenu->Link }}"
                                class="bg-[#D0E1E9] p-4 flex flex-col justify-center space-y-2 rounded-2xl">
                                <span
                                    class="w-[42px] h-[42px] rounded-2xl flex items-center justify-center text-gray-600 {{ $detalleMenu->PivotMenu->BgColor }}-400"
                                    style="font-size: 24px">
                                    <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                                </span>
                                <h3 class="w-[130px] ">
                                    {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                </h3>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div> --}}
            {{-- <div class="gap-4 overflow-x-visible overflow-y-auto">
                @foreach ($menus as $headerMenu)
                    <div class="mb-4">
                        <h1 class="text-4xl font-bold">{{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }} </h1>
                    </div>

                    <div class="flex flex-wrap gap-4 mb-12">
                        @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                            <a href="{{ $detalleMenu->PivotMenu->Link }}"
                                class="bg-[#D0E1E9] p-4 flex flex-col justify-center space-y-2 rounded-2xl transition-transform transform hover:scale-105 hover:bg-[#A8C6D9] duration-300 ease-in-out hover:shadow-lg hover:translate-y-[-5px] animate__animated animate__fadeInUp">
                                <span
                                    class="w-[42px] h-[42px] rounded-2xl flex items-center justify-center text-gray-600 {{ $detalleMenu->PivotMenu->BgColor }}-400"
                                    style="font-size: 24px">
                                    <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                                </span>
                                <h3 class="w-[130px] ">
                                    {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                </h3>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div> --}}
            <div class="gap-4 px-4 overflow-y-auto">
                @foreach ($menus as $headerMenu)
                    <div class="mb-4">
                        <h1 class="text-4xl font-bold">{{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }} </h1>
                    </div>

                    <div class="flex flex-wrap gap-4 mb-12">
                        @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                            <a href="{{ $detalleMenu->PivotMenu->Link }}"
                                class="bg-[#D0E1E9] p-4 flex flex-col justify-center space-y-2 rounded-2xl transition-transform transform hover:scale-105 hover:bg-[#A8C6D9] duration-300 ease-in-out hover:shadow-lg animate__animated animate__fadeInUp">
                                <span
                                    class="w-[42px] h-[42px] rounded-2xl flex items-center justify-center text-gray-600 {{ $detalleMenu->PivotMenu->BgColor }}-400"
                                    style="font-size: 24px">
                                    <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                                </span>
                                <h3 class="w-[130px] ">
                                    {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                </h3>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>


        </div>
    </div>
@endsection
