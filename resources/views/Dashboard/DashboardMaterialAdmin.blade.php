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
                    <span class="sr-only">Dashboard</span>
                </a>
                <div id="tooltip-share" role="tooltip"
                    class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
                    Dashboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
        {{-- overflow-y-scroll --}}
        <div class="flex flex-col gap-4 rounded-lg p-4" style="width: 100%;">
            <div class="border-b-2">
                <h1 class="text-4xl font-bold">Dashboard</h1>
            </div>

            <div class="flex gap-4 overflow-y-scroll">
                <div class="flex-1">
                    <h2 class="text-2xl mb-4 font-bold">Concentrados de ventas</h2>

                    @php
                        $stats = [
                            [
                                'icon' =>
                                    'M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z',
                                'value' => number_format($ventasData->total_ventas, 2, '.', ','),
                                'label' => 'Ventas diarias',
                            ],
                            [
                                'icon' =>
                                    'M6 12c.263 0 .524-.06.767-.175a2 2 0 0 0 .65-.491c.186-.21.333-.46.433-.734.1-.274.15-.568.15-.864a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 12 9.736a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 16 9.736c0 .295.052.588.152.861s.248.521.434.73a2 2 0 0 0 .649.488 1.809 1.809 0 0 0 1.53 0 2.03 2.03 0 0 0 .65-.488c.185-.209.332-.457.433-.73.1-.273.152-.566.152-.861 0-.974-1.108-3.85-1.618-5.121A.983.983 0 0 0 17.466 4H6.456a.986.986 0 0 0-.93.645C5.045 5.962 4 8.905 4 9.736c.023.59.241 1.148.611 1.567.37.418.865.667 1.389.697Zm0 0c.328 0 .651-.091.94-.266A2.1 2.1 0 0 0 7.66 11h.681a2.1 2.1 0 0 0 .718.734c.29.175.613.266.942.266.328 0 .651-.091.94-.266.29-.174.537-.427.719-.734h.681a2.1 2.1 0 0 0 .719.734c.289.175.612.266.94.266.329 0 .652-.091.942-.266.29-.174.536-.427.718-.734h.681c.183.307.43.56.719.734.29.174.613.266.941.266a1.819 1.819 0 0 0 1.06-.351M6 12a1.766 1.766 0 0 1-1.163-.476M5 12v7a1 1 0 0 0 1 1h2v-5h3v5h7a1 1 0 0 0 1-1v-7m-5 3v2h2v-2h-2Z',
                                'value' => $ventasData->total_tiendas_activas,
                                'label' => 'Tiendas activas',
                            ],
                            [
                                'icon' =>
                                    'M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z',
                                'value' => $ventasData->solicitudes_factura,
                                'label' => 'Solicitudes de factura',
                            ],
                            [
                                'icon' =>
                                    'M5.5 21h13M12 21V7m0 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm2-1.8c3.073.661 2.467 2.8 5 2.8M5 8c3.359 0 2.192-2.115 5.012-2.793M7 9.556V7.75m0 1.806-1.95 4.393a.773.773 0 0 0 .37.962.785.785 0 0 0 .362.089h2.436a.785.785 0 0 0 .643-.335.776.776 0 0 0 .09-.716L7 9.556Zm10 0V7.313m0 2.243-1.95 4.393a.773.773 0 0 0 .37.962.786.786 0 0 0 .362.089h2.436a.785.785 0 0 0 .643-.335.775.775 0 0 0 .09-.716L17 9.556Z',
                                'value' => number_format($ventasData->total_kilos, 2, '.', ','),
                                'label' => 'Kilos vendidos',
                            ],
                        ];
                    @endphp

                    <div class="flex gap-4 mb-4">
                        @foreach ($stats as $stat)
                            <div class="bg-[#D0E1E9] p-4 flex flex-col justify-center space-y-2 rounded-2xl">
                                <svg class="w-[36px] h-[36px] text-gray-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.5" d="{{ $stat['icon'] }}" />
                                </svg>
                                <span class="font-bold text-2xl">{{ $stat['value'] }}</span>
                                <h3 class="w-[130px] text-sm">{{ $stat['label'] }}</h3>
                            </div>
                        @endforeach
                    </div>


                    <h2 class="text-2xl mt-8 mb-4 font-bold">Ventas diarias</h2>
                    <div class="flex gap-4">
                        <div class="flex-1 max-w-[800px] bg-[#D0E1E9] p-4 rounded-2xl">
                            <canvas id="ventasTiendas" width="200" height="100"></canvas>
                        </div>
                        <div class="bg-[#D0E1E9] p-4 rounded-2xl">
                            <h3 class="px-4 text-xl font-bold">Solicitudes de factura</h3>
                            <table class="min-w-full">
                                <thead class="">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                                            Cliente
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                                            Tienda
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitudes as $solicitud)
                                        <tr class="hover:bg-[#BBCAD1]">
                                            <td class="px-6 py-2 text-xs font-medium text-gray-900">
                                                {{ $solicitud->NomCliente }}
                                            </td>
                                            <td class="px-6 py-2 text-xs text-gray-800">
                                                {{ $solicitud->NomTienda }}
                                            </td>
                                            <td class="px-6 py-2 text-xs text-end text-gray-800">
                                                @if ($solicitud->Editar == 1)
                                                    <span
                                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">Pendiente
                                                        de ligar</span>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h2 class="text-2xl mt-8 mb-4 font-bold">Ultimo mes</h2>
                    <div class="flex gap-4">
                        <div class="flex-1 max-w-[800px] bg-[#D0E1E9] p-4 rounded-2xl">
                            <canvas id="ventasChart" width="200" height="100"></canvas>
                        </div>

                        <div class="bg-[#D0E1E9] p-4 rounded-2xl">
                            <h3 class="px-4 text-xl font-bold">Productos mas vendidos</h3>
                            <table class="min-w-full">
                                <thead class="">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                                            Producto
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                                            Cantidad Vendida (KG)
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Total
                                            Ventas ($)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProductos as $producto)
                                        <tr class="hover:bg-[#BBCAD1]">
                                            <td class="px-6 py-2 text-xs font-medium text-gray-900">
                                                {{ $producto->NomArticulo }}
                                            </td>
                                            <td class="px-6 py-2 text-xs text-end text-gray-800">
                                                {{ number_format($producto->total_vendido, 2) }}
                                            </td>
                                            <td class="px-6 py-2 text-xs text-end text-gray-800">
                                                $ {{ number_format($producto->total_ventas, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-[300px]">
                    <ul class="flex flex-col gap-4">
                        <li>
                            <button type="button"
                                class="flex items-center w-[52px] h-[52px] bg-gray-900 text-gray-300 hover:text-gray-900 rounded-2xl hover:bg-gray-50">
                                <div class="px-3 w-[52px]">
                                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 11H4m15.5 5a.5.5 0 0 0 .5-.5V8a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44l-1.436-2.12a1 1 0 0 0-.828-.44H8a1 1 0 0 0-1 1M4 9v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44L9.985 8.44A1 1 0 0 0 9.157 8H5a1 1 0 0 0-1 1Z" />
                                    </svg>
                                </div>
                                <span
                                    class="ms-2 px-3 py-2 text-gray-900 font-medium hover:bg-gray-50 rounded-lg">Corte diario</span>
                            </button>
                        </li>
                        <li>
                            <button type="button"
                                class="flex items-center w-[52px] h-[52px] bg-gray-900 text-gray-300 hover:text-gray-900 rounded-2xl hover:bg-gray-50">
                                <div class="px-3 w-[52px]">
                                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 11H4m15.5 5a.5.5 0 0 0 .5-.5V8a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44l-1.436-2.12a1 1 0 0 0-.828-.44H8a1 1 0 0 0-1 1M4 9v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44L9.985 8.44A1 1 0 0 0 9.157 8H5a1 1 0 0 0-1 1Z" />
                                    </svg>
                                </div>
                                <span class="ms-2 px-3 py-2 text-gray-900 font-medium hover:bg-gray-50 rounded-lg">
                                    Solicitudes de factura
                                </span>
                            </button>
                        </li>
                        <li>
                            <button type="button"
                                class="flex items-center w-[52px] h-[52px] bg-gray-900 text-gray-300 hover:text-gray-900 rounded-2xl hover:bg-gray-50">
                                <div class="px-3 w-[52px]">
                                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 11H4m15.5 5a.5.5 0 0 0 .5-.5V8a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44l-1.436-2.12a1 1 0 0 0-.828-.44H8a1 1 0 0 0-1 1M4 9v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44L9.985 8.44A1 1 0 0 0 9.157 8H5a1 1 0 0 0-1 1Z" />
                                    </svg>
                                </div>
                                <span class="ms-2 px-3 py-2 text-gray-900 font-medium hover:bg-gray-50 rounded-lg">
                                    Listado de precios
                                </span>
                            </button>
                        </li>
                        <li>
                            <button type="button"
                                class="flex items-center w-[52px] h-[52px] bg-gray-900 text-gray-300 hover:text-gray-900 rounded-2xl hover:bg-gray-50">
                                <div class="px-3 w-[52px]">
                                    <svg class="w-[30px] h-[30px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 11H4m15.5 5a.5.5 0 0 0 .5-.5V8a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44l-1.436-2.12a1 1 0 0 0-.828-.44H8a1 1 0 0 0-1 1M4 9v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-3.75a1 1 0 0 1-.829-.44L9.985 8.44A1 1 0 0 0 9.157 8H5a1 1 0 0 0-1 1Z" />
                                    </svg>
                                </div>
                                <span class="ms-2 px-3 py-2 text-gray-900 font-medium hover:bg-gray-50 rounded-lg">
                                    Cancelar tickets
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Obteniendo los datos del ultimo mes
        const labels = @json($labels);
        const data = @json($data);

        // Obteniendo los datos de la tienda
        const labelsTienda = @json($labelsTiendas);
        const dataTienda = @json($dataTiendas);

        // Crear el gráfico con Chart.js
        const ctxTiendas = document.getElementById('ventasTiendas').getContext('2d');
        const ctx = document.getElementById('ventasChart').getContext('2d');

        const ventasChartTiendas = new Chart(ctxTiendas, {
            type: 'bar', // Tipo de gráfico, puede ser 'bar', 'line', etc.
            data: {
                labels: labelsTienda, // Las etiquetas del gráfico (e.g., fechas)
                datasets: [{
                        label: 'Ventas',
                        data: dataTienda.total_ventas, // Los datos de total_ventas
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Solicitudes de factura',
                        data: dataTienda.solicitudes_factura, // Los datos de solicitudes_factura
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Total Kilos',
                        data: dataTienda.total_kilos, // Los datos de total_kilos
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: ''
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Ventas ($)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        const ventasChart = new Chart(ctx, {
            type: 'line', // Tipo de gráfico, puede ser 'bar', 'line', etc.
            data: {
                labels: labels, // Las etiquetas del gráfico (e.g., fechas)
                datasets: [{
                        label: 'Ventas por día',
                        data: data.total_ventas, // Los datos de total_ventas
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Solicitudes de factura',
                        data: data.solicitudes_factura, // Los datos de solicitudes_factura
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Total Kilos',
                        data: data.total_kilos, // Los datos de total_kilos
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: ''
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Ventas ($)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
