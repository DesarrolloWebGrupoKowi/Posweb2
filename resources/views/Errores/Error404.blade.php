<x-page-container title="404 - Página no encontrada">
    <x-card-gradient-header
        icon="exclamation-triangle"
        title="Página no encontrada"
        subtitle="Lo sentimos, no pudimos encontrar lo que buscas"
    >
        <!-- Contenido del 404 - Ocupa todo el espacio -->
        <div
            class="d-flex flex-column p-4"
            style="flex: 1; min-height: calc(100vh - 250px);"
        >
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center flex-grow-1 gap-5 py-4">
                <!-- Imagen -->
                <div
                    class="text-center"
                    style="max-width: 400px;"
                >
                    <img
                        src="/img/404.svg"
                        alt="404 - Página no encontrada"
                        class="img-fluid"
                        style="max-height: 280px;"
                    >
                </div>

                <!-- Texto -->
                <div class="text-md-start text-center">
                    <div class="mb-3">
                        <span
                            class="badge"
                            style="background: #fef2f2; color: #dc2626; font-weight: 600; font-size: 0.75rem; padding: 4px 12px; border-radius: 20px;"
                        >
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Error 404
                        </span>
                    </div>

                    <h1 style="font-weight: 700; color: #0f172a; font-size: 2rem; margin-bottom: 12px;">
                        Página no encontrada
                    </h1>

                    <p style="color: #64748b; font-size: 0.95rem; max-width: 400px; margin-bottom: 24px;">
                        Es posible que hayas escrito mal la dirección o que la página se haya movido a otra ubicación.
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start flex-wrap gap-2">
                        @guest
                            <a
                                href="/"
                                class="btn d-flex align-items-center gap-2"
                                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; transition: all 0.2s;"
                            >
                                <i class="bi bi-box-arrow-in-right"></i> Ir al inicio de sesión
                            </a>
                        @else
                            <a
                                href="/"
                                class="btn d-flex align-items-center gap-2"
                                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; transition: all 0.2s;"
                            >
                                <i class="bi bi-house-fill"></i> Ir al inicio
                            </a>
                        @endguest

                        <button
                            onclick="history.back()"
                            class="btn d-flex align-items-center gap-2"
                            style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 24px; font-weight: 500; transition: all 0.2s;"
                        >
                            <i class="bi bi-arrow-left"></i> Regresar
                        </button>
                    </div>

                    <div
                        class="border-top mt-4 pt-3"
                        style="border-color: #f1f5f9;"
                    >
                        <p style="color: #94a3b8; font-size: 0.75rem; margin-bottom: 0;">
                            ¿Necesitas ayuda?
                            <a
                                href="/contacto"
                                style="color: #3b82f6; text-decoration: none; font-weight: 500;"
                            >Contáctanos</a>
                            o
                            <a
                                href="/"
                                style="color: #3b82f6; text-decoration: none; font-weight: 500;"
                            >vuelve al inicio</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
