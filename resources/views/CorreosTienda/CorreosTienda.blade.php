@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Correos Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Correos Por Tienda'])
            <form class="d-flex align-items-center justify-content-end" id="formCorreoTienda" action="/CorreosTienda"
                method="GET">
                <div class="form-group" style="max-width: 400px">
                    <label class="fw-bold text-secondary pb-1">Seleccione una tienda</label>
                    <select class="form-select" name="idTienda" id="idTienda">
                        <option value="">Seleccione una tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>



        @if (!empty($idTienda))
            <div class="card p-4" style="border-radius: 20px">
                @if ($correos->count() == 0)
                    <form action="/GuardarCorreosTienda/{{ $idTienda }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="fw-bold text-secondary pb-1">Correo del gerente</label>
                                <input type="text" class="form-control" name="gerenteCorreo" id="gerenteCorreo"
                                    placeholder="Correo del Gerente">
                            </div>
                            <div class="col-6">
                                <label class="fw-bold text-secondary pb-1">Correo del engardado</label>
                                <input type="text" class="form-control" name="encargadoCorreo" id="encargadoCorreo"
                                    placeholder="Correo del Encargado">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="fw-bold text-secondary pb-1">Correo del supervisor</label>
                                <input type="text" class="form-control" name="supervisorCorreo" id="supervisorCorreo"
                                    placeholder="Correo del Supervisor">
                            </div>
                            <div class="col-6">
                                <label class="fw-bold text-secondary pb-1">Correo administrativo</label>
                                <input type="text" class="form-control" name="administrativaCorreo"
                                    id="administrativaCorreo" placeholder="Correo Administrativa">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="fw-bold text-secondary pb-1">Correo almacenista</label>
                                <input type="text" class="form-control" name="almacenistaCorreo" id="almacenistaCorreo"
                                    placeholder="Correo del Almacenista">
                            </div>
                            <div class="col-4">
                                <label class="fw-bold text-secondary pb-1">Correo facturista</label>
                                <input type="text" class="form-control" name="facturistaCorreo" id="facturistaCorreo"
                                    placeholder="Correo de Facturista">
                            </div>
                            <div class="col-4">
                                <label class="fw-bold text-secondary pb-1">Correo recepción</label>
                                <input type="text" class="form-control" name="recepcionCorreo" id="recepcionCorreo"
                                    placeholder="Correo Recepción">
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
                        <form action="/EditarCorreosTienda/{{ $idTienda }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="fw-bold text-secondary pb-1">Correo del gerente</label>
                                    <input type="text" class="form-control" name="gerenteCorreo" id="gerenteCorreo"
                                        placeholder="Correo del Gerente" value="{{ $correo->GerenteCorreo }}">
                                </div>
                                <div class="col-6">
                                    <label class="fw-bold text-secondary pb-1">Correo del engardado</label>
                                    <input type="text" class="form-control" name="encargadoCorreo" id="encargadoCorreo"
                                        placeholder="Correo del Encargado" value="{{ $correo->EncargadoCorreo }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="fw-bold text-secondary pb-1">Correo del supervisor</label>
                                    <input type="text" class="form-control" name="supervisorCorreo" id="supervisorCorreo"
                                        placeholder="Correo del Supervisor" value="{{ $correo->SupervisorCorreo }}">
                                </div>
                                <div class="col-6">
                                    <label class="fw-bold text-secondary pb-1">Correo administrativo</label>
                                    <input type="text" class="form-control" name="administrativaCorreo"
                                        id="administrativaCorreo" placeholder="Correo Administrativo"
                                        value="{{ $correo->AdministrativaCorreo }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="fw-bold text-secondary pb-1">Correo almacenista</label>
                                    <input type="text" class="form-control" name="almacenistaCorreo"
                                        id="almacenistaCorreo" placeholder="Correo del Almacenista"
                                        value="{{ $correo->AlmacenistaCorreo }}">
                                </div>
                                <div class="col-4">
                                    <label class="fw-bold text-secondary pb-1">Correo facturista</label>
                                    <input type="text" class="form-control" name="facturistaCorreo"
                                        id="facturistaCorreo" placeholder="Correo de Facturista"
                                        value="{{ $correo->FacturistaCorreo }}">
                                </div>
                                <div class="col-4">
                                    <label class="fw-bold text-secondary pb-1">Correo recepción</label>
                                    <input type="text" class="form-control" name="recepcionCorreo"
                                        id="recepcionCorreo" placeholder="Correo Recepcion"
                                        value="{{ $correo->RecepcionCorreo }}">
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
    </div>

    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCorreoTienda').submit();
        });
    </script>
@endsection
