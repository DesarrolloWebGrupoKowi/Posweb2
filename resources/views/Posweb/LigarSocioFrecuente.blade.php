@extends('plantillaBase.masterblade')
@section('title', 'Ligar Socio/Frecuente')
@section('contenido')
    <div class="container mb-2">
        <div class="d-flex justify-content-center">
            <div class="col-auto card shadow p-1">
                <h3>Ligar Socio/Frecuente</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            @include('Alertas.Alertas')
        </div>
    </div>
    <div class="container mb-3">
        <form action="/LigarSocioFrecuente" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto card shadow p-3">
                    <label for="">
                        <h5>Escanear tarjeta</h5>
                    </label>
                    <input type="text" class="form-control" name="numNomina" id="numNomina" placeholder="Escanear tarjeta"
                        value="{{ $numNomina }}" required>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($numNomina))
        @if (empty($socioFrecuente))
            <div class="container d-flex justify-content-center">
                <h5 style="color: red"><i class="fa fa-exclamation-circle"></i> El Socio/Frecuente ya esta ligado o no
                    existe!
                </h5>
            </div>
        @else
            <form id="formGuardarSocioFrecuente" action="/GuardarSocioFrecuente/{{ $socioFrecuente->Num_nomina }}" method="POST">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="card shadow p-3">
                                <div class="col-auto">
                                    <label for="">
                                        <h6>Folio:</h6>
                                        <label for="">
                                            <h5 class="mt-1">
                                                {{ $socioFrecuente->Num_nomina }}
                                            </h5>
                                        </label>
                                    </label>
                                </div>
                                <div class="col-5">
                                    <label for="">
                                        <h6>Tipo Cliente:</h6>
                                    </label>
                                    <select class="form-select" name="tipoCliente" id="tipoCliente" required>
                                        @foreach ($tiposCliente as $tipoCliente)
                                            <option {!! empty($socioFrecuente->tipo) ? 'selected' : '' !!} value="{{ $tipoCliente->IdTipoCliente }}">{{ $tipoCliente->NomTipoCliente }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label for="">
                                        <h6>Fecha Alta:</h6>
                                        <label for="">
                                            <h5 class="mt-1">
                                                {{ strftime('%d %B %Y', strtotime($socioFrecuente->Fecha)) }}
                                            </h5>
                                        </label>
                                    </label>
                                </div>
                                <div class="col-auto">
                                    <label for="">
                                        <h6>Fecha Nacimiento:</h6>
                                        <label for="">
                                            <h5 class="mt-1">
                                                <input type="date" class="form-control" name="fechaNacimiento"
                                                    id="fechaNacimiento"
                                                    value="{{ date('Y-m-d', strtotime($socioFrecuente->FechaNac)) }}"
                                                    required>
                                            </h5>
                                        </label>
                                    </label>
                                </div>
                                <div class="col-10">
                                    <label for="">
                                        <h6>Nombre:</h6>
                                    </label>
                                    <input type="text" class="form-control" name="nombre" id="nombre"
                                        value="{{ $socioFrecuente->Nombre }} {{ $socioFrecuente->Apellidos }}" required>
                                </div>
                                <div class="col-5">
                                    <label for="">
                                        <h6>Sexo:</h6>
                                    </label>
                                    <select class="form-select" name="sexo" id="sexo">
                                        <option {!! $socioFrecuente->Sexo == 'F' ? 'selected' : '' !!} value="F">Femenino</option>
                                        <option {!! $socioFrecuente->Sexo == 'M' ? 'selected' : '' !!} value="M">Masculino</option>
                                    </select>
                                </div>
                                <div class="col-10">
                                    <label for="">
                                        <h6>Dirección:</h6>
                                    </label>
                                    <input type="text" class="form-control" name="direccion" id="direccion"
                                        value="{{ $socioFrecuente->Direccion }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card shadow p-3">
                                <div class="col-10">
                                    <label for="">
                                        <h6>Colonia:</h6>
                                    </label>
                                    <input type="text" class="form-control" name="colonia" id="colonia"
                                        value="{{ $socioFrecuente->Colonia }}" required>
                                </div>
                                <div class="col-10">
                                    <label for="">
                                        <h6>Ciudad:</h6>
                                    </label>
                                    <input type="text" class="form-control" name="ciudad" id="ciudad"
                                        value="{{ $socioFrecuente->Localidad }}" required>
                                </div>
                                <div class="col-5">
                                    <label for="">
                                        <h6>Telefono:</h6>
                                    </label>
                                    <input type="number" class="form-control" name="telefono" id="telefono" maxlength="10"
                                        value="{{ $socioFrecuente->Telefono }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="">
                                        <h6>Correo:</h6>
                                    </label>
                                    <input type="email" class="form-control" name="correo" id="correo"
                                        value="{{ $socioFrecuente->Correo }}" required>
                                </div>
                                <div class="col-5">
                                    <label for="">
                                        <h6>Ocupación:</h6>
                                    </label>
                                    <input type="text" class="form-control" name="ocupacion" id="ocupacion"
                                        value="{{ $socioFrecuente->Ocupacion }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="d-flex justify-content-center">
                            <div class="col-auto">
                                <button type="button" class="btn btn-warning shadow" data-bs-toggle="modal" data-bs-target="#ModalConfirmarFrecuenteSocio">
                                    <i class="fa fa-save"></i> Ligar Socio/Frecuente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('Posweb.ConfirmarFrecuenteSocio')
            </form>
            <script>
                document.getElementById('formGuardarSocioFrecuente').addEventListener('submit', function (){
                    document.getElementById('btnLigarSocioFrecuente').hidden = true;
                    document.getElementById('btnLigarndoSocioFrecuente').hidden = false;
                });
            </script>
        @endif
    @endif
@endsection
