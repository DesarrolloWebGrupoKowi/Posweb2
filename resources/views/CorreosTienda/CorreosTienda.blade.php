@extends('plantillaBase.masterblade')
@section('title', 'Correos Por Tienda')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Correos Por Tienda</h2>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form id="formCorreoTienda" action="/CorreosTienda" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($idTienda))
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-auto card-tittle">
                <h3>Correos de la Tienda</h3>
            </div>
        </div>
        @if ($correos->count() == 0)
            <form class="card shadow p-3 rounded-3" action="/GuardarCorreosTienda/{{ $idTienda }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-6">
                        <input type="text" class="form-control" name="gerenteCorreo" id="gerenteCorreo"
                            placeholder="Correo del Gerente">
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" name="encargadoCorreo" id="encargadoCorreo"
                            placeholder="Correo del Encargado">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <input type="text" class="form-control" name="supervisorCorreo" id="supervisorCorreo"
                            placeholder="Correo del Supervisor">
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" name="administrativaCorreo" id="administrativaCorreo"
                            placeholder="Correo Administrativa">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <input type="text" class="form-control" name="almacenistaCorreo" id="almacenistaCorreo"
                            placeholder="Correo del Almacenista">
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="facturistaCorreo" id="facturistaCorreo"
                            placeholder="Correo de Facturista">
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="recepcionCorreo" id="recepcionCorreo"
                            placeholder="Correo Recepcion">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-auto">
                        <button class="btn btn-warning">
                            <i class="fa fa-plus"></i> Guardar Correos
                        </button>
                    </div>
                </div>
            </form>
        @else
            @foreach ($correos as $correo)
                <form class="card shadow p-3 rounded-3" action="/EditarCorreosTienda/{{ $idTienda }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" name="gerenteCorreo" id="gerenteCorreo"
                                placeholder="Correo del Gerente" value="{{ $correo->GerenteCorreo }}">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="encargadoCorreo" id="encargadoCorreo"
                                placeholder="Correo del Encargado" value="{{ $correo->EncargadoCorreo }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" name="supervisorCorreo" id="supervisorCorreo"
                                placeholder="Correo del Supervisor" value="{{ $correo->SupervisorCorreo }}">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="administrativaCorreo" id="administrativaCorreo"
                                placeholder="Correo Administrativa" value="{{ $correo->AdministrativaCorreo }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <input type="text" class="form-control" name="almacenistaCorreo" id="almacenistaCorreo"
                                placeholder="Correo del Almacenista" value="{{ $correo->AlmacenistaCorreo }}">
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="facturistaCorreo" id="facturistaCorreo"
                                placeholder="Correo de Facturista" value="{{ $correo->FacturistaCorreo }}">
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="recepcionCorreo" id="recepcionCorreo"
                                placeholder="Correo Recepcion" value="{{ $correo->RecepcionCorreo }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="col-auto">
                            <button class="btn btn-warning">
                                <i class="fa fa-edit"></i> Editar Correos
                            </button>
                        </div>
                    </div>
                </form>
            @endforeach
        @endif
    </div>
    @endif

    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCorreoTienda').submit();
        });
    </script>
@endsection
