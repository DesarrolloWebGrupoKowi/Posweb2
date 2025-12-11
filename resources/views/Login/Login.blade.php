<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kowi</title>

    <link rel="shortcut icon" href="{{ asset('img/logokowi-v2.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">

    {{-- ✅ CSS personalizado mínimo --}}
    <style>
        body {
            background-size: cover;
            background-position: top center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f1f5f9;
            background: linear-gradient(to right,
                    #fed7aa 0%,
                    #94a3b8 50%,
                    hsla(211, 58%, 79%, 1) 100%);
            background-image: url('../img/kowi.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(10px);
        }

        .login-card {
            max-width: 420px;
            margin: 5vh auto;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            background-color: #fff;
        }

        .error-text {
            font-size: 0.9rem;
            color: #dc3545;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card login-card p-4">
            <div class="text-center mb-3">
                <img src="{{ asset('img/logokowi-v2.png') }}" width="70" alt="Logo">
                <h5 class="mt-3 mb-1">Inicie Sesión</h5>
                <small class="text-muted">
                    {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] Y')) }}
                </small>
            </div>

            <form action="{{ url('/authenticate') }}" method="POST">
                @csrf

                {{-- Usuario --}}
                <label class="fw-semibold mb-1 text-secondary">Nombre de usuario</label>
                <div class="input-group mb-1">
                    <span class="input-group-text">
                        <i class="material-icons" style="color: gray;">person</i>
                    </span>
                    <input type="text" class="form-control @error('NomUsuario') is-invalid @enderror"
                        name="NomUsuario" value="{{ old('NomUsuario') }}" placeholder="Nombre de Usuario" autofocus>
                </div>
                @error('NomUsuario')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                {{-- Contraseña --}}
                <label class="fw-semibold mb-1 mt-3 text-secondary">Contraseña</label>
                <div class="input-group mb-1">
                    <span class="input-group-text">
                        <i class="material-icons" style="color: gray;">fingerprint</i>
                    </span>
                    <input type="password" class="form-control @error('Password') is-invalid @enderror" name="Password"
                        placeholder="Contraseña">
                </div>
                @error('Password')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                {{-- Error general de login --}}
                {{-- @if ($errors->has('NomUsuario') && !$errors->has('Password'))
                    <div class="error-text text-center mt-2">
                        {{ $errors->first('NomUsuario') }}
                    </div>
                @endif --}}

                {{-- Botones --}}
                <div class="d-flex justify-content-center mt-4">
                    <button id="loginBtn" type="submit" class="btn btn-dark w-100">
                        <i class="material-icons" style="vertical-align: middle; font-size:18px;">login</i>
                        Iniciar Sesión
                    </button>

                    <button type="button" id="iniciandoSesionBtn" class="btn btn-dark w-100" hidden>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Iniciando sesión...
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts mínimos --}}
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginBtn').hidden = true;
            document.getElementById('iniciandoSesionBtn').hidden = false;
        });
    </script>
</body>

</html>
