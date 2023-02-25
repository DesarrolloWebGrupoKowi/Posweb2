@extends('plantillaBase.masterblade')
@section('title', 'Resumen de Ventas')
<script src="{{ asset('js/chart.js') }}"></script>
@section('contenido')
    <div class="container">
        <div class="d-flex justify-content-center">
            <h2 class="card shadow">Resumen de mis Ventas</h2>
        </div>
        <hr>
    </div>
    <div class="container mb-3">
        <form action="/ResumenVentas" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow mt-1">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                @if (!empty($idTienda))
                    <div class="col d-flex justify-content-end">
                        <h4 class="text-opacity-50">Resumen a partir de:
                            <strong>{{ strftime('%d %B %Y', strtotime($primerDiaMesActual)) }}</strong></h4>
                    </div>
                @endif
            </div>
        </form>
        <hr>
    </div>
    @if (!empty($idTienda))
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <h5><strong>Total de Ingresos ($)</strong></h5>
                    <canvas class="shadow p-1" id="totalDinero"></canvas>
                </div>
            </div>
        </div>
    @endif
    <script>
        var ctx = document.getElementById('totalDinero').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo $nomListasPrecio; ?>,
                datasets: [{
                    label: '$',
                    data: <?php echo $totalIngresos; ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 150, 23, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 150, 23, 1)'
                    ],
                    borderWidth: 3
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection
