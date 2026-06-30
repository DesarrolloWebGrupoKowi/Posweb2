<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Glasea - Calculadora de Costos</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >
    <link
        rel="shortcut icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍩</text></svg>"
    >
    <style>
        :root {
            --crema: #FAF3E8;
            --crema-oscuro: #F0E4D0;
            --vainilla: #F5E6D3;
            --beige: #E8D5B7;
            --dorado: #C9A96E;
            --dorado-oscuro: #B8956A;
            --cafe-medio: #8B6F47;
            --cafe-oscuro: #5C3D2E;
            --cafe-profundo: #3C2415;
            --blanco: #FFFDF9;
            --sombra-suave: 0 2px 15px rgba(92, 61, 46, 0.08);
            --sombra-media: 0 4px 20px rgba(92, 61, 46, 0.12);
            --borde-suave: 1px solid rgba(139, 111, 71, 0.15);
            --gradiente-logo: linear-gradient(135deg, #C9A96E 0%, #8B6F47 50%, #5C3D2E 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--crema);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            color: var(--cafe-oscuro);
        }

        /* Layout Principal */
        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar - Recetas Guardadas */
        .sidebar {
            width: 350px;
            background: var(--blanco);
            border-right: var(--borde-suave);
            display: flex;
            flex-direction: column;
            box-shadow: var(--sombra-suave);
            z-index: 10;
        }

        .sidebar-header {
            background: linear-gradient(135deg, #e6dcd1 0%, #C4B5A0 100%);
            color: var(--blanco);
            border-bottom: 1px solid rgba(255, 253, 249, 0.1);
            position: relative;
            overflow: hidden;
            /* padding: 20px; */
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(201, 169, 110, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .logo-logotipo {
            height: 148px;
            object-fit: contain;
        }

        .recetas-list {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: linear-gradient(180deg, var(--blanco) 0%, var(--crema) 100%);
        }

        .receta-card {
            background: var(--blanco);
            border: var(--borde-suave);
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: var(--sombra-suave);
        }

        .receta-card:hover {
            border-color: var(--dorado);
            box-shadow: var(--sombra-media);
            transform: translateX(5px);
        }

        .receta-card.active {
            border-color: var(--dorado);
            background: linear-gradient(135deg, #FFFDF9 0%, #FAF3E8 100%);
            border-left: 4px solid var(--dorado);
            box-shadow: 0 4px 15px rgba(201, 169, 110, 0.2);
        }

        .receta-nombre {
            font-weight: 600;
            color: var(--cafe-oscuro);
            margin-bottom: 5px;
            font-size: 0.95rem;
            padding-right: 25px;
        }

        .receta-info {
            font-size: 0.8rem;
            color: var(--cafe-medio);
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .receta-costo {
            font-weight: 600;
            color: var(--dorado-oscuro);
            font-size: 0.9rem;
        }

        .btn-delete-receta {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: var(--beige);
            cursor: pointer;
            padding: 5px;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-delete-receta:hover {
            color: #D4A574;
            background: #FFF5F0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--crema);
            overflow: hidden;
        }

        /* Header del Postre */
        .postre-header {
            background: var(--blanco);
            padding: 25px 35px 20px;
            border-bottom: var(--borde-suave);
            box-shadow: var(--sombra-suave);
        }

        .postre-title-input {
            border: none;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--cafe-profundo);
            width: 100%;
            outline: none;
            background: transparent;
            padding: 8px 0;
            border-bottom: 2px dashed var(--beige);
            transition: border-color 0.3s;
        }

        .postre-title-input:focus {
            border-bottom-color: var(--dorado);
        }

        .postre-title-input::placeholder {
            color: var(--beige);
        }

        .postre-meta {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-label {
            font-size: 0.82rem;
            color: var(--cafe-medio);
            font-weight: 500;
        }

        .meta-input {
            width: 75px;
            border: 2px solid var(--beige);
            border-radius: 10px;
            padding: 7px 10px;
            text-align: center;
            font-weight: 600;
            color: var(--cafe-oscuro);
            font-size: 0.9rem;
            background: var(--blanco);
            transition: all 0.3s;
            outline: none;
        }

        .meta-input:focus {
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1);
        }

        .btn-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .btn-action {
            padding: 8px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-save {
            background: var(--gradiente-logo);
            color: var(--blanco);
            box-shadow: 0 3px 12px rgba(139, 111, 71, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(139, 111, 71, 0.4);
        }

        .btn-new {
            background: var(--blanco);
            color: var(--cafe-oscuro);
            border: 2px solid var(--dorado);
        }

        .btn-new:hover {
            background: var(--crema);
        }

        .btn-clean {
            background: var(--blanco);
            color: var(--cafe-medio);
            border: 2px solid var(--beige);
        }

        .btn-clean:hover {
            background: #FFF5F0;
            border-color: #D4A574;
            color: #D4A574;
        }

        /* Formulario ARRIBA */
        .input-area {
            background: var(--blanco);
            padding: 16px 35px;
            border-bottom: var(--borde-suave);
            box-shadow: 0 2px 10px rgba(92, 61, 46, 0.04);
        }

        .input-row {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .input-group-custom {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 110px;
        }

        .input-group-custom label {
            font-size: 0.72rem;
            color: var(--cafe-medio);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-custom input,
        .input-group-custom select {
            border: 2px solid var(--beige);
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 0.85rem;
            transition: all 0.3s;
            outline: none;
            background: var(--blanco);
            color: var(--cafe-oscuro);
        }

        .input-group-custom input:focus,
        .input-group-custom select:focus {
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1);
        }

        .btn-send {
            background: var(--gradiente-logo);
            border: none;
            border-radius: 12px;
            padding: 10px 22px;
            color: var(--blanco);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            box-shadow: 0 3px 12px rgba(139, 111, 71, 0.3);
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(139, 111, 71, 0.4);
        }

        .btn-clear {
            background: var(--blanco);
            border: 2px solid var(--beige);
            border-radius: 12px;
            padding: 10px 18px;
            color: var(--cafe-medio);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-clear:hover {
            border-color: #D4A574;
            color: #D4A574;
        }

        /* Área de contenido: chat + totales */
        .content-wrapper {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        /* Área de ingredientes (tipo chat) */
        .chat-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px 25px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background:
                radial-gradient(circle at 20% 50%, rgba(201, 169, 110, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 111, 71, 0.03) 0%, transparent 50%),
                var(--crema);
        }

        .ingrediente-bubble {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bubble-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--gradiente-logo);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blanco);
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(139, 111, 71, 0.2);
        }

        .bubble-content {
            background: var(--blanco);
            border-radius: 16px 16px 16px 4px;
            padding: 14px;
            box-shadow: var(--sombra-suave);
            flex: 1;
            max-width: 480px;
            border: var(--borde-suave);
        }

        .bubble-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .bubble-producto {
            font-weight: 600;
            color: var(--cafe-oscuro);
            font-size: 0.95rem;
        }

        .bubble-unidad {
            font-size: 0.7rem;
            color: var(--cafe-medio);
            background: var(--crema);
            padding: 3px 8px;
            border-radius: 15px;
            font-weight: 500;
        }

        .bubble-detalles {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--cafe-medio);
        }

        .bubble-detalle-item {
            display: flex;
            justify-content: space-between;
        }

        .bubble-costo {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--beige);
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: var(--dorado-oscuro);
            font-size: 0.9rem;
        }

        .bubble-actions {
            display: flex;
            flex-direction: column;
        }

        .btn-bubble-action {
            background: var(--blanco);
            border: var(--borde-suave);
            border-radius: 10px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--beige);
            transition: all 0.3s;
        }

        .btn-bubble-action:hover {
            background: #FFF5F0;
            color: #D4A574;
            border-color: #D4A574;
            transform: scale(1.1);
        }

        /* Panel de Totales - LADO DERECHO */
        .totals-panel {
            width: 280px;
            background: var(--blanco);
            border-left: var(--borde-suave);
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            overflow-y: auto;
            box-shadow: -2px 0 10px rgba(92, 61, 46, 0.04);
        }

        .totals-title {
            font-size: 0.75rem;
            color: var(--cafe-medio);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 4px;
        }

        .total-block {
            background: var(--crema);
            border-radius: 16px;
            padding: 18px 16px;
            text-align: center;
            border: var(--borde-suave);
        }

        .total-block.principal {
            background: linear-gradient(135deg, #C4A882 0%, #A08460 100%);
            color: var(--blanco);
            border: none;
            box-shadow: 0 6px 20px rgba(139, 111, 71, 0.3);
        }

        .total-block-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            opacity: 0.85;
        }

        .total-block.principal .total-block-label {
            color: #FFD700;
            font-weight: 600;
        }

        .total-block-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--cafe-oscuro);
        }

        .total-block.principal .total-block-value {
            color: var(--blanco);
            font-size: 2.2rem;
        }

        .total-block-sub {
            font-size: 0.78rem;
            color: var(--cafe-medio);
            margin-top: 4px;
        }

        .total-block.principal .total-block-sub {
            color: rgba(255, 255, 255, 0.8);
        }

        .divider-totals {
            border: none;
            border-top: 1px solid var(--beige);
            margin: 4px 0;
        }

        .costo-porcion {
            text-align: center;
            padding: 10px;
            background: var(--crema-oscuro);
            border-radius: 12px;
            border: var(--borde-suave);
        }

        .costo-porcion-label {
            font-size: 0.72rem;
            color: var(--cafe-medio);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .costo-porcion-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dorado-oscuro);
        }

        /* Scrollbar personalizado */
        .chat-area::-webkit-scrollbar,
        .recetas-list::-webkit-scrollbar,
        .totals-panel::-webkit-scrollbar {
            width: 6px;
        }

        .chat-area::-webkit-scrollbar-track,
        .recetas-list::-webkit-scrollbar-track,
        .totals-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-area::-webkit-scrollbar-thumb,
        .recetas-list::-webkit-scrollbar-thumb,
        .totals-panel::-webkit-scrollbar-thumb {
            background: var(--beige);
            border-radius: 10px;
        }

        .chat-area::-webkit-scrollbar-thumb:hover,
        .recetas-list::-webkit-scrollbar-thumb:hover,
        .totals-panel::-webkit-scrollbar-thumb:hover {
            background: var(--dorado);
        }

        .empty-isotipo {
            width: 256px;
            height: 128px;
            object-fit: contain;
            /* margin-bottom: 20px; */
            opacity: 0.7;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 4px 12px rgba(139, 111, 71, 0.15));
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 20px;
            color: var(--cafe-medio);
            flex: 1;
            min-height: 100%;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--cafe-oscuro);
        }

        .empty-state small {
            color: var(--cafe-claro, #A08460);
            font-style: italic;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .totals-panel {
                width: 240px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: 30vh;
            }

            .app-container {
                flex-direction: column;
            }

            .content-wrapper {
                flex-direction: column;
            }

            .totals-panel {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 12px;
                padding: 15px;
                border-left: none;
                border-top: var(--borde-suave);
            }

            .total-block {
                flex: 1;
                min-width: 140px;
            }

            .input-row {
                flex-direction: column;
            }
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--cafe-medio);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .empty-state small {
            color: var(--beige);
            font-style: italic;
            font-size: 0.78rem;
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--gradiente-logo);
            color: var(--blanco);
            padding: 14px 22px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(92, 61, 46, 0.3);
            z-index: 1000;
            animation: slideInRight 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <!-- Sidebar - Recetas Guardadas -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <img
                        src="/img/calculo/logotipo.png"
                        class="logo-logotipo"
                        alt="Glasea"
                    >
                </div>
            </div>
            <div
                class="recetas-list"
                id="recetasList"
            >
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <p>Sin recetas guardadas</p>
                    <small>Crea tu primera receta</small>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header con nombre del postre -->
            <header class="postre-header">
                <input
                    type="text"
                    id="nombrePostre"
                    class="postre-title-input"
                    placeholder="Nombra tu creación..."
                >
                <div class="postre-meta">
                    <div class="meta-item">
                        <span class="meta-label">🍽️ Porciones:</span>
                        <input
                            type="number"
                            id="porciones"
                            class="meta-input"
                            value="1"
                            min="1"
                        >
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">📊 Margen:</span>
                        <input
                            type="number"
                            id="margenGanancia"
                            class="meta-input"
                            value="40"
                            min="0"
                            max="500"
                        >
                        <span class="meta-label">%</span>
                    </div>
                </div>
                <div class="btn-actions">
                    <button
                        class="btn-action btn-save"
                        onclick="guardarReceta()"
                    >
                        <i class="fas fa-save"></i> Guardar Receta
                    </button>
                    <button
                        class="btn-action btn-new"
                        onclick="nuevaReceta()"
                    >
                        <i class="fas fa-plus"></i> Nueva
                    </button>
                    <button
                        class="btn-action btn-clean"
                        onclick="limpiarTodo()"
                    >
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </header>

            <!-- FORMULARIO ARRIBA -->
            <div class="input-area">
                <div class="input-row">
                    <div
                        class="input-group-custom"
                        style="flex: 2;"
                    >
                        <label>🧁 Producto</label>
                        <input
                            type="text"
                            id="nombreProducto"
                            placeholder="Harina, huevos, chocolate..."
                        >
                    </div>
                    <div class="input-group-custom">
                        <label>📦 Cant. Comprada</label>
                        <input
                            type="number"
                            id="cantidadComprada"
                            placeholder="1000"
                            step="0.01"
                        >
                    </div>
                    <div class="input-group-custom">
                        <label>📏 Unidad</label>
                        <select id="unidadComprada">
                            <option value="gr">Gramos (gr)</option>
                            <option value="ml">Mililitros (ml)</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="lt">Litros (lt)</option>
                            <option value="pz">Piezas (pz)</option>
                        </select>
                    </div>
                    <div class="input-group-custom">
                        <label>💰 Costo Total</label>
                        <input
                            type="number"
                            id="costoTotal"
                            placeholder="45.50"
                            step="0.01"
                        >
                    </div>
                    <div class="input-group-custom">
                        <label>⚖️ Cant. Usada</label>
                        <input
                            type="number"
                            id="cantidadUsada"
                            placeholder="200"
                            step="0.01"
                        >
                    </div>
                    <button
                        class="btn-send"
                        onclick="agregarIngrediente()"
                    >
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                    <button
                        class="btn-clear"
                        onclick="limpiarFormulario()"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- CONTENIDO: Chat + Totales -->
            <div class="content-wrapper">
                <!-- Área de ingredientes (chat) -->
                <div
                    class="chat-area"
                    id="chatIngredientes"
                ></div>

                <!-- PANEL DE TOTALES - LADO DERECHO -->
                <aside class="totals-panel">
                    <div>
                        <div class="totals-title">Resumen</div>

                        <hr class="divider-totals">

                        <div class="total-block">
                            <div class="total-block-label">Costo Total</div>
                            <div
                                class="total-block-value"
                                id="costoTotalIngredientes"
                                style="font-size: 1.5rem;"
                            >$0.00</div>
                        </div>

                        <div
                            class="total-block"
                            style="margin-top: 12px;"
                        >
                            <div class="total-block-label">Ganancia Estimada</div>
                            <div
                                class="total-block-value"
                                id="gananciaEstimada"
                                style="font-size: 1.5rem;"
                            >$0.00</div>
                        </div>

                        <hr class="divider-totals">

                        <div class="costo-porcion">
                            <div class="costo-porcion-label">💎 Costo por Porción</div>
                            <div
                                class="costo-porcion-value"
                                id="costoPorPorcion"
                            >$0.00</div>
                        </div>

                        <hr class="divider-totals">

                        <div class="total-block principal">
                            <div class="total-block-label">⭐ Precio Sugerido</div>
                            <div
                                class="total-block-value"
                                id="precioVentaSugerido"
                            >$0.00</div>
                            <div class="total-block-sub">con margen incluido</div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <script>
        // Estado de la aplicación
        let ingredientes = [];
        let recetas = JSON.parse(localStorage.getItem('glasea_recetas')) || [];
        let recetaActualId = null;
        let hayCambiosPendientes = false;
        let estadoOriginal = null;

        // Inicializar
        document.addEventListener('DOMContentLoaded', () => {
            // Recuperar estado completo guardado
            const estadoGuardado = JSON.parse(localStorage.getItem('glasea_estado_actual'));

            if (estadoGuardado) {
                ingredientes = estadoGuardado.ingredientes || [];
                recetaActualId = estadoGuardado.recetaActualId || null;

                if (estadoGuardado.nombrePostre) {
                    document.getElementById('nombrePostre').value = estadoGuardado.nombrePostre;
                }
                if (estadoGuardado.porciones) {
                    document.getElementById('porciones').value = estadoGuardado.porciones;
                }
                if (estadoGuardado.margen) {
                    document.getElementById('margenGanancia').value = estadoGuardado.margen;
                }
            } else {
                // Si no hay estado guardado, cargar solo ingredientes (compatibilidad)
                ingredientes = JSON.parse(localStorage.getItem('glasea_ingredientes')) || [];
            }

            mostrarIngredientes();
            mostrarRecetas();
            calcularTotales();
            guardarEstadoOriginal();
        });

        // Event listeners
        ['porciones', 'margenGanancia'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                calcularTotales();
                marcarCambiosPendientes();
                guardarEstadoCompleto();
            });
        });

        document.getElementById('nombrePostre').addEventListener('input', function() {
            // NO perder el vínculo con la receta actual al editar el nombre
            actualizarRecetaActiva();
            marcarCambiosPendientes();
            guardarEstadoCompleto();
        });

        // Detectar cambios en el formulario
        ['nombreProducto', 'cantidadComprada', 'costoTotal', 'cantidadUsada'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', marcarCambiosPendientes);
            }
        });

        document.getElementById('unidadComprada').addEventListener('change', marcarCambiosPendientes);

        function marcarCambiosPendientes() {
            hayCambiosPendientes = true;
            actualizarIndicadorGuardado();
        }

        function guardarEstadoOriginal() {
            estadoOriginal = {
                nombrePostre: document.getElementById('nombrePostre').value,
                porciones: document.getElementById('porciones').value,
                margen: document.getElementById('margenGanancia').value,
                ingredientes: JSON.parse(JSON.stringify(ingredientes)),
                recetaActualId: recetaActualId
            };
            hayCambiosPendientes = false;
            actualizarIndicadorGuardado();
        }

        function hayCambiosReales() {
            if (!estadoOriginal) return ingredientes.length > 0;

            const nombreActual = document.getElementById('nombrePostre').value;
            const porcionesActual = document.getElementById('porciones').value;
            const margenActual = document.getElementById('margenGanancia').value;

            // Comparar ingredientes
            const ingredientesIguales = JSON.stringify(ingredientes) === JSON.stringify(estadoOriginal.ingredientes);
            const metadataIgual = nombreActual === estadoOriginal.nombrePostre &&
                porcionesActual === estadoOriginal.porciones &&
                margenActual === estadoOriginal.margen;

            return !(ingredientesIguales && metadataIgual);
        }

        function actualizarIndicadorGuardado() {
            const btnSave = document.querySelector('.btn-save');
            if (!btnSave) return;

            const icon = btnSave.querySelector('i');
            if (hayCambiosPendientes && hayCambiosReales()) {
                btnSave.style.background = 'linear-gradient(135deg, #e8a850 0%, #c4883c 100%)';
                btnSave.style.animation = 'pulse-save 2s infinite';
                if (icon) icon.className = 'fas fa-save';
            } else {
                btnSave.style.background = 'var(--gradiente-logo)';
                btnSave.style.animation = 'none';
                if (icon) icon.className = 'fas fa-save';
            }
        }

        // Agregar animación al CSS
        const styleSheet = document.createElement('style');
        styleSheet.textContent = `
            @keyframes pulse-save {
                0%, 100% { box-shadow: 0 3px 12px rgba(200, 150, 80, 0.3); }
                50% { box-shadow: 0 3px 25px rgba(200, 150, 80, 0.6); }
            }

            .btn-save.modified {
                background: linear-gradient(135deg, #e8a850 0%, #c4883c 100%) !important;
                animation: pulse-save 2s infinite;
            }
        `;
        document.head.appendChild(styleSheet);

        function agregarIngrediente() {
            const nombre = document.getElementById('nombreProducto').value.trim();
            const cantidadComprada = parseFloat(document.getElementById('cantidadComprada').value);
            const unidadComprada = document.getElementById('unidadComprada').value;
            const costoTotal = parseFloat(document.getElementById('costoTotal').value);
            const cantidadUsada = parseFloat(document.getElementById('cantidadUsada').value);

            if (!nombre) {
                alert('🍩 Ingresa el nombre del producto');
                return;
            }
            if (!cantidadComprada || cantidadComprada <= 0) {
                alert('🍩 Ingresa una cantidad válida');
                return;
            }
            if (!costoTotal || costoTotal <= 0) {
                alert('🍩 Ingresa el costo total');
                return;
            }
            if (!cantidadUsada || cantidadUsada <= 0) {
                alert('🍩 Ingresa la cantidad usada');
                return;
            }
            if (cantidadUsada > cantidadComprada) {
                alert('🍩 La cantidad usada no puede ser mayor');
                return;
            }

            const costoPorUnidad = costoTotal / cantidadComprada;
            const costoUsado = costoPorUnidad * cantidadUsada;

            const ingrediente = {
                id: Date.now(),
                nombre,
                cantidadComprada,
                unidadComprada,
                costoTotal,
                cantidadUsada,
                costoPorUnidad,
                costoUsado
            };

            ingredientes.push(ingrediente);
            guardarIngredientes();
            mostrarIngredientes();
            calcularTotales();
            limpiarFormulario();
            marcarCambiosPendientes();

            document.getElementById('chatIngredientes').scrollTop =
                document.getElementById('chatIngredientes').scrollHeight;
        }

        function mostrarIngredientes() {
            const chatArea = document.getElementById('chatIngredientes');
            chatArea.innerHTML = '';

            if (ingredientes.length === 0) {
                chatArea.innerHTML = `
                    <div class="empty-state">
                        <img
                            src="/img/calculo/logo.png"
                            alt="Glasea"
                            class="empty-isotipo"
                        >
                        <p>Aún no hay ingredientes</p>
                        <small>Agrega los ingredientes de tu receta</small>
                    </div>
                `;
                return;
            }

            ingredientes.forEach(ingrediente => {
                const bubble = document.createElement('div');
                bubble.className = 'ingrediente-bubble';
                bubble.innerHTML = `
                    <div class="bubble-avatar">
                        ${ingrediente.nombre.charAt(0).toUpperCase()}
                    </div>
                    <div class="bubble-content">
                        <div class="bubble-header">
                            <span class="bubble-producto">${ingrediente.nombre}</span>
                            <span class="bubble-unidad">${ingrediente.unidadComprada}</span>
                        </div>
                        <div class="bubble-detalles">
                            <div class="bubble-detalle-item">
                                <span>📦 Comprado:</span>
                                <span>${ingrediente.cantidadComprada} ${ingrediente.unidadComprada}</span>
                            </div>
                            <div class="bubble-detalle-item">
                                <span>💰 Costo:</span>
                                <span>$${ingrediente.costoTotal.toFixed(2)}</span>
                            </div>
                            <div class="bubble-detalle-item">
                                <span>⚖️ Usado:</span>
                                <span>${ingrediente.cantidadUsada} ${ingrediente.unidadComprada}</span>
                            </div>
                            <div class="bubble-detalle-item">
                                <span>📊 Costo/unidad:</span>
                                <span>$${ingrediente.costoPorUnidad.toFixed(4)}</span>
                            </div>
                        </div>
                        <div class="bubble-costo">
                            <span>Costo usado:</span>
                            <span>$${ingrediente.costoUsado.toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="bubble-actions">
                        <button class="btn-bubble-action" onclick="eliminarIngrediente(${ingrediente.id})" title="Eliminar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                chatArea.appendChild(bubble);
            });
        }

        function eliminarIngrediente(id) {
            if (confirm('¿Eliminar este ingrediente?')) {
                ingredientes = ingredientes.filter(i => i.id !== id);
                guardarIngredientes();
                mostrarIngredientes();
                calcularTotales();
                marcarCambiosPendientes();
            }
        }

        function calcularTotales() {
            const costoTotal = ingredientes.reduce((total, i) => total + i.costoUsado, 0);
            const margen = parseFloat(document.getElementById('margenGanancia').value) || 0;
            const precioVenta = costoTotal * (1 + margen / 100);
            const ganancia = precioVenta - costoTotal;
            const porciones = parseInt(document.getElementById('porciones').value) || 1;
            const costoPorPorcion = costoTotal / porciones;

            document.getElementById('costoTotalIngredientes').textContent = `$${costoTotal.toFixed(2)}`;
            document.getElementById('precioVentaSugerido').textContent = `$${precioVenta.toFixed(2)}`;
            document.getElementById('gananciaEstimada').textContent = `$${ganancia.toFixed(2)}`;
            document.getElementById('costoPorPorcion').textContent = `$${costoPorPorcion.toFixed(2)}`;
        }

        function limpiarFormulario() {
            document.getElementById('nombreProducto').value = '';
            document.getElementById('cantidadComprada').value = '';
            document.getElementById('costoTotal').value = '';
            document.getElementById('cantidadUsada').value = '';
            document.getElementById('nombreProducto').focus();
        }

        function guardarEstadoCompleto() {
            const estadoCompleto = {
                ingredientes: ingredientes,
                nombrePostre: document.getElementById('nombrePostre').value,
                porciones: document.getElementById('porciones').value,
                margen: document.getElementById('margenGanancia').value,
                recetaActualId: recetaActualId
            };
            localStorage.setItem('glasea_estado_actual', JSON.stringify(estadoCompleto));
        }

        function guardarIngredientes() {
            localStorage.setItem('glasea_ingredientes', JSON.stringify(ingredientes));
            guardarEstadoCompleto(); // 👈 Agregar esta línea
        }

        function guardarReceta() {
            const nombrePostre = document.getElementById('nombrePostre').value.trim();

            if (!nombrePostre) {
                alert('🍩 ¡Ponle nombre a tu creación!');
                document.getElementById('nombrePostre').focus();
                return;
            }

            if (ingredientes.length === 0) {
                alert('🍩 Agrega al menos un ingrediente');
                return;
            }

            // Lógica mejorada para evitar duplicados
            if (recetaActualId) {
                // Si ya tengo un ID, actualizo ESA receta (aunque cambie el nombre)
                const recetaExistenteMismoNombre = recetas.find(r =>
                    r.nombre.toLowerCase() === nombrePostre.toLowerCase() &&
                    r.id !== recetaActualId
                );

                if (recetaExistenteMismoNombre) {
                    const actualizar = confirm(
                        `Ya existe OTRA receta llamada "${nombrePostre}".\n\n` +
                        `¿Deseas REEMPLAZARLA con esta?\n\n` +
                        `• Aceptar = Reemplazar la receta existente\n` +
                        `• Cancelar = Cambiar el nombre`
                    );

                    if (actualizar) {
                        // Eliminar la receta con nombre duplicado
                        recetas = recetas.filter(r => r.id !== recetaExistenteMismoNombre.id);
                    } else {
                        document.getElementById('nombrePostre').focus();
                        document.getElementById('nombrePostre').select();
                        return;
                    }
                }
            } else {
                // Si NO hay recetaActualId, buscar si existe una con el mismo nombre
                const recetaExistente = recetas.find(r =>
                    r.nombre.toLowerCase() === nombrePostre.toLowerCase()
                );

                if (recetaExistente) {
                    const actualizar = confirm(
                        `Ya existe una receta llamada "${nombrePostre}".\n\n` +
                        `¿Deseas ACTUALIZARLA con los ingredientes actuales?\n\n` +
                        `• Aceptar = Actualizar receta existente\n` +
                        `• Cancelar = Cambiar el nombre`
                    );

                    if (actualizar) {
                        recetaActualId = recetaExistente.id;
                    } else {
                        document.getElementById('nombrePostre').focus();
                        document.getElementById('nombrePostre').select();
                        return;
                    }
                }
            }

            const receta = {
                id: recetaActualId || Date.now(),
                nombre: nombrePostre,
                porciones: parseInt(document.getElementById('porciones').value) || 1,
                margen: parseFloat(document.getElementById('margenGanancia').value) || 40,
                ingredientes: [...ingredientes],
                costoTotal: ingredientes.reduce((total, i) => total + i.costoUsado, 0),
                fecha: new Date().toLocaleDateString('es-MX', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
            };

            if (recetaActualId) {
                // Actualizar receta existente (reemplazar, no duplicar)
                const index = recetas.findIndex(r => r.id === recetaActualId);
                if (index !== -1) {
                    recetas[index] = receta;
                }
            } else {
                // Nueva receta
                recetas.push(receta);
                recetaActualId = receta.id;
            }

            localStorage.setItem('glasea_recetas', JSON.stringify(recetas));
            mostrarRecetas();
            guardarEstadoOriginal();

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = '✅ Receta guardada correctamente';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        }

        function mostrarRecetas() {
            const recetasList = document.getElementById('recetasList');

            if (recetas.length === 0) {
                recetasList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-cookie"></i>
                        <p>Sin recetas guardadas</p>
                        <small>Crea tu primera receta</small>
                    </div>
                `;
                return;
            }

            recetasList.innerHTML = '';
            // Ordenar por fecha de modificación (más reciente primero)
            const recetasOrdenadas = [...recetas].sort((a, b) => {
                const idA = a.id;
                const idB = b.id;
                return idB - idA;
            });

            recetasOrdenadas.forEach(receta => {
                const precioVenta = receta.costoTotal * (1 + receta.margen / 100);
                const isActive = receta.id === recetaActualId;

                const card = document.createElement('div');
                card.className = `receta-card ${isActive ? 'active' : ''}`;
                card.innerHTML = `
                    <button class="btn-delete-receta" onclick="event.stopPropagation(); eliminarReceta(${receta.id})">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="receta-nombre">${receta.nombre}</div>
                    <div class="receta-info">
                        <span>📅 ${receta.fecha}</span>
                        <span>🍽️ ${receta.porciones} porciones | ${receta.margen}% margen</span>
                    </div>
                    <div class="receta-costo">
                        Costo: $${receta.costoTotal.toFixed(2)} → Venta: $${precioVenta.toFixed(2)}
                    </div>
                `;

                card.addEventListener('click', () => cargarReceta(receta.id));
                recetasList.appendChild(card);
            });
        }

        function cargarReceta(id) {
            // Verificar cambios pendientes antes de cargar otra receta
            if (hayCambiosPendientes && hayCambiosReales() && id !== recetaActualId) {
                const continuar = confirm(
                    '⚠️ Tienes cambios sin guardar\n\n' +
                    '¿Deseas DESCARTAR los cambios y cargar otra receta?\n\n' +
                    '• Aceptar = Descartar cambios y cargar\n' +
                    '• Cancelar = Seguir editando'
                );
                if (!continuar) return;
            }

            const receta = recetas.find(r => r.id === id);
            if (receta) {
                recetaActualId = receta.id;
                ingredientes = [...receta.ingredientes];
                document.getElementById('nombrePostre').value = receta.nombre;
                document.getElementById('porciones').value = receta.porciones;
                document.getElementById('margenGanancia').value = receta.margen;

                guardarIngredientes();
                mostrarIngredientes();
                mostrarRecetas();
                calcularTotales();
                guardarEstadoOriginal();
            }
        }

        function eliminarReceta(id) {
            if (confirm('¿Eliminar esta receta permanentemente?')) {
                recetas = recetas.filter(r => r.id !== id);
                if (recetaActualId === id) {
                    recetaActualId = null;
                }
                localStorage.setItem('glasea_recetas', JSON.stringify(recetas));
                mostrarRecetas();
            }
        }

        function nuevaReceta() {
            if (hayCambiosPendientes && hayCambiosReales()) {
                const continuar = confirm(
                    '⚠️ Tienes cambios sin guardar\n\n' +
                    '¿Deseas DESCARTAR los cambios y crear una nueva receta?\n\n' +
                    '• Aceptar = Descartar y crear nueva\n' +
                    '• Cancelar = Seguir editando'
                );
                if (!continuar) return;
            }

            recetaActualId = null;
            document.getElementById('nombrePostre').value = '';
            document.getElementById('porciones').value = '1';
            document.getElementById('margenGanancia').value = '40';
            ingredientes = [];
            guardarIngredientes();
            mostrarIngredientes();
            mostrarRecetas();
            calcularTotales();
            guardarEstadoOriginal();
            document.getElementById('nombrePostre').focus();
        }

        function limpiarTodo() {
            // Si no hay ingredientes ni nombre, no hacer nada
            if (ingredientes.length === 0 && !document.getElementById('nombrePostre').value) return;

            // Solo preguntar si hay cambios reales
            if (hayCambiosReales()) {
                if (!confirm('¿Limpiar todos los ingredientes y el nombre?')) {
                    return;
                }
            }

            ingredientes = [];
            recetaActualId = null;
            document.getElementById('nombrePostre').value = '';
            document.getElementById('porciones').value = '1';
            document.getElementById('margenGanancia').value = '40';
            guardarIngredientes();
            mostrarIngredientes();
            mostrarRecetas();
            calcularTotales();
            guardarEstadoOriginal();
            document.getElementById('nombrePostre').focus();
        }

        function actualizarRecetaActiva() {
            mostrarRecetas();
        }

        // Ctrl+S para guardar
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                guardarReceta();
            }
        });

        // Advertir antes de cerrar/recargar la página si hay cambios
        window.addEventListener('beforeunload', function(e) {
            if (hayCambiosPendientes && hayCambiosReales()) {
                e.preventDefault();
                e.returnValue = 'Tienes cambios sin guardar. ¿Estás seguro de salir?';
                return e.returnValue;
            }
        });
    </script>
</body>

</html>
