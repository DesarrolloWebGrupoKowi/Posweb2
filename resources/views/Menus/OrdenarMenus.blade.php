@extends('plantillaBase.masterblade')
@section('title', 'Ordenar Menús por Tipo de Usuario')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h3 class="card shadow p-1">
                Ordenar Menús
            </h3>
        </div>
    </div>
    <div class="container mb-3">
        <form id="formTipoUsuario" action="/OrdenarMenus" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select" name="idTipoUsuario" id="idTipoUsuario">
                        <option value="0">Seleccione</option>
                        @foreach ($tiposUsuario as $tipoUsuario)
                            <option {!! $idTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{ $tipoUsuario->IdTipoUsuario }}">
                                {{ $tipoUsuario->NomTipoUsuario }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="container mb-3">
        @if ($menus->count() == 0 && !empty($idTipoUsuario))
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="bg-danger text-white p-1 shadow"><i class="fa fa-exclamation-triangle"></i> No Hay Menús <i
                            class="fa fa-exclamation-triangle"></i></h4>
                </div>
            </div>
        @else
            @foreach ($menus as $menu)
                <form action="/EditarPosicionMenu">
                    <input type="hidden" name="idTipoUsuario" value="{{ $idTipoUsuario }}">
                    <h5>{{ $menu->NomTipoMenu }}</h5>
                    <table class="table table-responsive table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Menú</th>
                                <th>Posición</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menu->Ordenar as $nMenu)
                                <tr>
                                    <td>{{ $nMenu->PivotMenu->NomMenu }}</td>
                                    <td><input style="width: 15vh" class="form-control" type="text" name="posicion[{{ $nMenu->PivotMenu->IdMenu }}]"
                                            value="{{ $nMenu->Posicion }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-warning shadow">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            @endforeach
        @endif
    </div>
    <script>
        const idTipoUsuario = document.getElementById('idTipoUsuario');
        const formTipoUsuario = document.getElementById('formTipoUsuario');
        idTipoUsuario.addEventListener('change', (e) => {
            formTipoUsuario.submit();
        });
    </script>
@endsection
