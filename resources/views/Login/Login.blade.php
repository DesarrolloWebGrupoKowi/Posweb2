<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Login | Kowi</title>

    <link
        rel="shortcut icon"
        href="{{ asset('img/logokowi-v2.png') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    >
    <link
        href="{{ asset('material-icon/material-icon.css') }}"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        /* ============================================================ */
        /* FONDO CON OVERLAY MODERNO */
        /* ============================================================ */
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f1f5f9;
            background-image: url('../img/kowi.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            padding: 20px;
        }

        /* Overlay oscuro con degradado */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg,
                    rgba(15, 23, 42, 0.7) 0%,
                    rgba(30, 58, 95, 0.5) 50%,
                    rgba(15, 23, 42, 0.7) 100%);
            z-index: 0;
        }

        /* ============================================================ */
        /* TARJETA DE LOGIN */
        /* ============================================================ */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        /* Logo */
        .login-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .login-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.5rem;
            margin-bottom: 4px;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 24px;
        }

        /* ============================================================ */
        /* CAMPOS DEL FORMULARIO - CORREGIDO */
        /* ============================================================ */
        .form-label-custom {
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 4px;
            display: block;
        }

        .input-group-custom {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            background: white;
            display: flex;
            align-items: stretch;
        }

        .input-group-custom:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .input-group-custom .input-group-text {
            background: white;
            border: none;
            padding: 0 0 0 14px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 44px;
        }

        .input-group-custom .input-group-text i {
            font-size: 1.2rem;
            color: #94a3b8;
        }

        .input-group-custom .form-control {
            border: none;
            padding: 12px 14px 12px 8px;
            font-size: 0.9rem;
            background: white;
            color: #0f172a;
            flex: 1;
            height: auto;
            min-height: 48px;
        }

        .input-group-custom .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        .input-group-custom .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-group-custom.is-invalid {
            border-color: #dc2626;
        }

        .input-group-custom.is-invalid:focus-within {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }

        .error-text {
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ============================================================ */
        /* BOTÓN DE LOGIN */
        /* ============================================================ */
        .btn-login {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px -8px rgba(15, 23, 42, 0.4);
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* ============================================================ */
        /* ENLACES ADICIONALES */
        /* ============================================================ */
        .login-footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }

        .login-footer a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #3b82f6;
        }

        .login-footer .separator {
            color: #e2e8f0;
            margin: 0 8px;
        }

        /* ============================================================ */
        /* RESPONSIVE */
        /* ============================================================ */
        @media (max-width: 576px) {
            .login-card {
                padding: 28px 20px;
            }

            .login-logo {
                width: 56px;
                height: 56px;
            }

            .login-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">

            <!-- ============================================================ -->
            <!-- HEADER -->
            <!-- ============================================================ -->
            <div class="text-center">
                <img
                    src="{{ asset('img/logokowi-v2.png') }}"
                    alt="Kowi"
                    class="login-logo"
                >
                <h1 class="login-title">Bienvenido</h1>
                <p class="login-subtitle">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] Y')) }}
                </p>
            </div>

            <!-- ============================================================ -->
            <!-- FORMULARIO -->
            <!-- ============================================================ -->
            <form
                action="{{ url('/authenticate') }}"
                method="POST"
            >
                @csrf

                <!-- Usuario -->
                <label class="form-label-custom">
                    <i class="bi bi-person me-1"></i>Nombre de usuario
                </label>
                <div class="input-group-custom @error('NomUsuario') is-invalid @enderror">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control"
                        name="NomUsuario"
                        value="{{ old('NomUsuario') }}"
                        placeholder="Ingresa tu usuario"
                        autofocus
                    >
                </div>
                @error('NomUsuario')
                    <div class="error-text">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                @enderror

                <!-- Contraseña -->
                <label class="form-label-custom mt-3">
                    <i class="bi bi-lock me-1"></i>Contraseña
                </label>
                <div class="input-group-custom @error('Password') is-invalid @enderror">
                    <span class="input-group-text">
                        <i class="bi bi-fingerprint"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control"
                        name="Password"
                        placeholder="Ingresa tu contraseña"
                    >
                </div>
                @error('Password')
                    <div class="error-text">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                @enderror

                <!-- Error general de login (si no hay errores específicos) -->
                @if ($errors->has('login'))
                    <div class="error-text mt-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('login') }}
                    </div>
                @endif

                <!-- Botón -->
                <div class="mt-4">
                    <button
                        id="loginBtn"
                        type="submit"
                        class="btn-login"
                    >
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>

                    <button
                        id="iniciandoSesionBtn"
                        type="button"
                        class="btn-login"
                        hidden
                        disabled
                    >
                        <span
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Iniciando sesión...
                    </button>
                </div>
            </form>

            <!-- ============================================================ -->
            <!-- FOOTER -->
            <!-- ============================================================ -->
            <div class="login-footer">
                <a
                    href="#"
                    onclick="event.preventDefault(); alert('Función no disponible');"
                >
                    <i class="bi bi-question-circle me-1"></i>¿Olvidaste tu contraseña?
                </a>
                <span class="separator">|</span>
                <a
                    href="#"
                    onclick="event.preventDefault(); alert('Función no disponible');"
                >
                    <i class="bi bi-headset me-1"></i>Soporte
                </a>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginBtn').hidden = true;
            document.getElementById('iniciandoSesionBtn').hidden = false;
        });
    </script>
</body>

</html>
