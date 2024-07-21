@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Correos Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Correos Por Tienda'])
                <form class="d-flex align-items-center justify-content-end" id="formCorreoTienda" action="/CorreosTienda"
                    method="GET">
                    <div class="form-group" style="max-width: 400px">
                        <label class="text-secondary" style="font-weight: 500">Seleccione una tienda</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda">
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
        </div>



        @if (empty($idTienda))
            <h2 class="text-center">
                Seleccione una tienda
            </h2>
        @endif

        @if (!empty($idTienda))
            <div class="card border-0 p-4" style="border-radius: 10px">
                @if ($correos->count() == 0)
                    <form action="/GuardarCorreosTienda/{{ $idTienda }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="text-secondary" style="font-weight: 500" style="font-weight: 500">Correo del
                                    gerente</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="gerenteCorreo" id="gerenteCorreo" placeholder="Correo del Gerente">
                            </div>
                            <div class="col-6">
                                <label class="text-secondary" style="font-weight: 500">Correo del engardado</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="encargadoCorreo" id="encargadoCorreo" placeholder="Correo del Encargado">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="text-secondary" style="font-weight: 500">Correo del supervisor</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="supervisorCorreo" id="supervisorCorreo" placeholder="Correo del Supervisor">
                            </div>
                            <div class="col-6">
                                <label class="text-secondary" style="font-weight: 500">Correo administrativo</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="administrativaCorreo" id="administrativaCorreo"
                                    placeholder="Correo Administrativa">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="text-secondary" style="font-weight: 500">Correo almacenista</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="almacenistaCorreo" id="almacenistaCorreo" placeholder="Correo del Almacenista">
                            </div>
                            <div class="col-4">
                                <label class="text-secondary" style="font-weight: 500">Correo facturista</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="facturistaCorreo" id="facturistaCorreo" placeholder="Correo de Facturista">
                            </div>
                            <div class="col-4">
                                <label class="text-secondary" style="font-weight: 500">Correo recepción</label>
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="recepcionCorreo" id="recepcionCorreo" placeholder="Correo Recepción">
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
                                    <label class="text-secondary" style="font-weight: 500">Correo del gerente</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="gerenteCorreo" id="gerenteCorreo" placeholder="Correo del Gerente"
                                        value="{{ $correo->GerenteCorreo }}">
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary" style="font-weight: 500">Correo del engardado</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="encargadoCorreo" id="encargadoCorreo" placeholder="Correo del Encargado"
                                        value="{{ $correo->EncargadoCorreo }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="text-secondary" style="font-weight: 500">Correo del supervisor</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="supervisorCorreo" id="supervisorCorreo" placeholder="Correo del Supervisor"
                                        value="{{ $correo->SupervisorCorreo }}">
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary" style="font-weight: 500">Correo administrativo</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="administrativaCorreo" id="administrativaCorreo"
                                        placeholder="Correo Administrativo" value="{{ $correo->AdministrativaCorreo }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="text-secondary" style="font-weight: 500">Correo almacenista</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="almacenistaCorreo" id="almacenistaCorreo"
                                        placeholder="Correo del Almacenista" value="{{ $correo->AlmacenistaCorreo }}">
                                </div>
                                <div class="col-4">
                                    <label class="text-secondary" style="font-weight: 500">Correo facturista</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="facturistaCorreo" id="facturistaCorreo" placeholder="Correo de Facturista"
                                        value="{{ $correo->FacturistaCorreo }}">
                                </div>
                                <div class="col-4">
                                    <label class="text-secondary" style="font-weight: 500">Correo recepción</label>
                                    <input type="text" class="form-control rounded" style="line-height: 18px"
                                        name="recepcionCorreo" id="recepcionCorreo" placeholder="Correo Recepcion"
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
