<!-- Alerta: Registrado Correctamente -->
@if (Session::has('msjAdd'))
    <div
        class="toast-alert toast-success show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>¡Éxito!</strong>
            <span>{{ Session::get('msjAdd') }}</span>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Eliminado Correctamente -->
@if (Session::has('msjdelete'))
    <div
        class="toast-alert toast-danger show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-trash-fill"></i>
        </div>
        <div class="toast-content">
            <strong>Eliminado</strong>
            <span>{{ Session::get('msjdelete') }}</span>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Editado Correctamente -->
@if (Session::has('msjupdate'))
    <div
        class="toast-alert toast-warning show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-pencil-fill"></i>
        </div>
        <div class="toast-content">
            <strong>Actualizado</strong>
            <span>{{ Session::get('msjupdate') }}</span>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Error de Validación -->
@if (Session::has('msjErrorValida'))
    <div
        class="toast-alert toast-danger show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>Error</strong>
            <span>{{ Session::get('msjErrorValida') }}</span>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Eliminado Lógico -->
@if (Session::has('msjEliminadoLogico'))
    <div
        class="toast-alert toast-warning show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>Atención</strong>
            <span>{{ Session::get('msjEliminadoLogico') }}</span>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Errores de validación -->
@if (isset($errors) && $errors->any())
    <div
        class="toast-alert toast-danger show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>Errores encontrados</strong>
            <ul
                class="mb-0 ps-3"
                style="font-size: 0.8rem;"
            >
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: POS -->
@if (Session::has('Pos'))
    <div
        class="toast-alert toast-danger show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>{{ Session::get('Pos') }}</strong>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Pedido -->
@if (Session::has('AlertPedido'))
    <div
        class="toast-alert toast-warning show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>{{ Session::get('AlertPedido') }}</strong>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<!-- Alerta: Pedido Guardado -->
@if (Session::has('PedidoGuardado'))
    <div
        class="toast-alert toast-success show"
        role="alert"
    >
        <div class="toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="toast-content">
            <strong>{{ Session::get('PedidoGuardado') }}</strong>
        </div>
        <button
            type="button"
            class="toast-close"
            onclick="this.parentElement.remove()"
        >
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif

<style>
    /* ========== CONTENEDOR DE TOASTS ========== */
    .alerts-toast-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 380px;
        max-width: calc(100vw - 40px);
        pointer-events: none;
    }

    /* ========== TOAST ALERT ========== */
    .toast-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: white;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        pointer-events: auto;
        animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
        position: relative;
        overflow: hidden;
    }

    /* Barra de progreso */
    .toast-alert::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        animation: toastProgress 5s linear forwards;
    }

    /* Colores por tipo */
    .toast-success {
        border-left-color: #10b981;
    }

    .toast-success::before {
        background: #10b981;
    }

    .toast-danger {
        border-left-color: #ef4444;
    }

    .toast-danger::before {
        background: #ef4444;
    }

    .toast-warning {
        border-left-color: #f59e0b;
    }

    .toast-warning::before {
        background: #f59e0b;
    }

    /* Icono del toast */
    .toast-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .toast-success .toast-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .toast-danger .toast-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .toast-warning .toast-icon {
        background: #fef3c7;
        color: #f59e0b;
    }

    /* Contenido del toast */
    .toast-content {
        flex: 1;
        min-width: 0;
    }

    .toast-content strong {
        display: block;
        font-size: 0.85rem;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .toast-content span {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Botón cerrar */
    .toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .toast-close:hover {
        background: #f1f5f9;
        color: #475569;
    }

    /* ========== ANIMACIONES ========== */
    @keyframes toastSlideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes toastProgress {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }

    /* Auto-ocultar después de la animación */
    .toast-alert {
        animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1),
            toastFadeOut 0.3s ease-in 4.7s forwards;
    }

    @keyframes toastFadeOut {
        to {
            opacity: 0;
            transform: translateX(120%);
        }
    }
</style>

<script>
    // Auto-eliminar toasts después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-alert');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(120%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        });
    });
</script>
