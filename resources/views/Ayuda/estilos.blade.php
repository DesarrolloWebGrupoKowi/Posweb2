<style>
    /* ============================================================
       CENTRO DE AYUDA
       ============================================================ */
    .ayuda-categoria-titulo {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 12px;
    }

    .ayuda-card {
        padding: 14px;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        background: var(--card-bg);
        transition: all 0.2s ease;
    }

    .ayuda-card:hover {
        border-color: var(--btn-blue-text);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.12);
    }

    .ayuda-card-icono {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--btn-blue-text);
        font-size: 1.1rem;
    }

    .ayuda-card-titulo {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .ayuda-card-descripcion {
        color: var(--text-secondary);
    }

    /* Aviso de la última versión */
    .ayuda-version {
        position: relative;
        overflow: hidden;
        padding: 16px 20px;
        border-radius: 12px;
        color: var(--btn-blue-text);
        background: linear-gradient(135deg, #dbeafe 0%, var(--btn-blue-bg) 100%);
        box-shadow: 0 6px 18px rgba(59, 130, 246, 0.12);
        transition: all 0.2s ease;
    }

    .ayuda-version::after {
        content: '';
        position: absolute;
        right: -40px;
        top: -60px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.07);
        pointer-events: none;
    }

    .ayuda-version:hover {
        color: var(--btn-blue-text);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(59, 130, 246, 0.18);
    }

    .ayuda-version-icono {
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: rgba(59, 130, 246, 0.12);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.06);
    }

    .ayuda-version-badge {
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #fff;
        background: var(--btn-blue-text);
    }

    .ayuda-version-numero {
        font-weight: 700;
        font-size: 0.85rem;
    }

    .ayuda-version small {
        color: var(--text-secondary);
    }

    .ayuda-version-titulo {
        margin-top: 2px;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .ayuda-version-ver {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(59, 130, 246, 0.12);
        white-space: nowrap;
    }

    @media (max-width: 575.98px) {
        .ayuda-version-ver {
            display: none;
        }
    }


    /* ============================================================
       CONTENIDO MARKDOWN
       ============================================================ */
    .doc-content {
        color: var(--text-primary);
        line-height: 1.7;
        max-width: 860px;
    }

    .doc-content h1,
    .doc-content h2,
    .doc-content h3 {
        color: var(--text-heading);
        font-weight: 600;
        text-transform: none;
        margin-top: 1.6rem;
        margin-bottom: 0.6rem;
    }

    .doc-content h1 {
        font-size: 1.5rem;
    }

    .doc-content h2 {
        font-size: 1.25rem;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--border-light);
    }

    .doc-content h3 {
        font-size: 1.05rem;
    }

    .doc-content > :first-child {
        margin-top: 0;
    }

    .doc-content img {
        max-width: 100%;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        margin: 8px 0;
    }

    .doc-content code {
        background: var(--bg-subtle);
        color: var(--text-primary);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .doc-content blockquote {
        border-left: 4px solid var(--btn-blue-text);
        background: var(--btn-blue-bg);
        padding: 10px 14px;
        border-radius: 0 8px 8px 0;
        margin: 1rem 0;
    }

    .doc-content blockquote p:last-child {
        margin-bottom: 0;
    }

    .doc-content table {
        width: 100%;
        margin: 1rem 0;
        font-size: 0.9rem;
    }

    .doc-content th,
    .doc-content td {
        border-bottom: 1px solid var(--table-border);
        padding: 8px;
    }

    /* Lectura centrada cuando no hay columna lateral */
    .ayuda-lectura {
        max-width: 1100px;
        margin: 0 auto;
    }

    .ayuda-lectura .doc-content {
        max-width: none;
    }

    /* Secciones (##) como banda, con la pantalla donde se trabaja */
    .doc-content h2.seccion,
    .pasos-encabezado-seccion {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 14px;
    }

    .doc-content h2.seccion,
    .pasos-encabezado {
        color: #fff;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
    }

    .doc-content h2.seccion {
        margin-top: 2.2rem;
        padding: 12px 16px;
        border-bottom: none;
        font-size: 1.15rem;
    }

    .doc-content > h2.seccion:first-child {
        margin-top: 0;
    }

    .seccion-titulo {
        position: relative;
        padding-left: 14px;
        letter-spacing: 0.2px;
    }

    .seccion-titulo::before {
        content: '';
        position: absolute;
        left: 0;
        top: 15%;
        bottom: 15%;
        width: 4px;
        border-radius: 4px;
        background: #f97316;
    }

    .seccion-pantalla {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: all 0.2s ease;
    }

    .seccion-pantalla small {
        font-size: 0.65rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }

    .seccion-pantalla i {
        color: #fbbf24;
    }

    .seccion-pantalla:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.24);
    }

    .seccion-pantalla .bi-box-arrow-up-right {
        color: inherit;
        font-size: 0.7rem;
        opacity: 0.7;
    }

    /* Modo paso a paso: sección + pantalla arriba y título del paso abajo, en un solo bloque */
    .pasos-encabezado {
        margin-bottom: 10px;
        overflow: hidden;
    }

    .pasos-encabezado-seccion {
        padding: 5px 6px 5px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.18);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .pasos-encabezado-seccion .seccion-titulo {
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-size: 0.72rem;
    }

    .pasos-encabezado-seccion .seccion-pantalla {
        padding: 2px 10px;
        font-size: 0.78rem;
    }

    .doc-content .pasos-encabezado h3.paso {
        margin: 0;
        padding: 8px 14px;
        color: #fff;
        background: transparent;
        border-radius: 0;
    }

    .doc-content .pasos-encabezado .paso-numero {
        background: #f97316;
    }

    /* Pasos grandes: ### Título {.paso} (el número lo pone AyudaService) */
    .doc-content h3.paso {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 1.2rem;
        margin-top: 2rem;
        padding: 12px 16px;
        background: var(--bg-subtle);
        border-radius: 10px;
    }

    .doc-content .paso-numero {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    }

    /* Botón a una pantalla: [Texto](ruta:Nombre){.boton} */
    .doc-content a.boton {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        margin: 4px 0;
        border-radius: 8px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        transition: all 0.2s ease;
    }

    .doc-content a.boton::after {
        content: '\2192';
    }

    .doc-content a.boton:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
    }

    /* Imágenes con pie de foto y zoom */
    .doc-figura {
        margin: 12px 0 20px;
    }

    .doc-figura img {
        margin: 0;
        cursor: zoom-in;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
    }

    .doc-figura figcaption {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-top: 6px;
    }

    .doc-figura-pendiente > div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 140px;
        border: 2px dashed var(--border-light);
        border-radius: 8px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .doc-figura-pendiente i {
        font-size: 1.6rem;
    }

    .doc-figura-pendiente small {
        font-weight: 400;
        font-family: monospace;
    }

    /* Miniatura: indica que se puede ampliar */
    .doc-zoom {
        position: relative;
        display: inline-block;
        max-width: 100%;
    }

    .doc-zoom-pista {
        position: absolute;
        right: 8px;
        bottom: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #fff;
        background: rgba(15, 23, 42, 0.75);
        opacity: 0;
        transform: translateY(4px);
        transition: all 0.2s ease;
        pointer-events: none;
    }

    .doc-zoom:hover .doc-zoom-pista {
        opacity: 1;
        transform: translateY(0);
    }

    .doc-zoom:hover img {
        box-shadow: 0 0 0 3px #f97316, 0 8px 20px rgba(15, 23, 42, 0.15);
    }

    /* Imagen ampliada: fondo oscuro desenfocado y la captura en un marco, para no confundirla con la pantalla */
    .doc-zoom-overlay {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px;
        background: rgba(2, 6, 23, 0.9);
        backdrop-filter: blur(6px);
        cursor: zoom-out;
    }

    .doc-zoom-overlay.abierto {
        display: flex;
        animation: zoomFondo 0.2s ease;
    }

    .doc-zoom-marco {
        display: flex;
        flex-direction: column;
        max-width: min(1400px, 100%);
        max-height: calc(100% - 40px);
        margin: 0;
        /* Transparente: el color lo llevan la barra y el área de la imagen, así no se asoma blanco en las esquinas */
        background: transparent;
        border-radius: 12px;
        overflow: hidden;
        cursor: default;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7);
        animation: zoomMarco 0.25s ease;
    }

    .doc-zoom-barra {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 8px 8px 14px;
        color: #fff;
        font-size: 0.85rem;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    }

    .doc-zoom-barra .bi-zoom-in {
        color: #f97316;
        font-size: 1rem;
    }

    .doc-zoom-etiqueta {
        flex-shrink: 0;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 8px;
        border-radius: 10px;
        background: #f97316;
    }

    .doc-zoom-titulo {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .doc-zoom-cerrar {
        flex-shrink: 0;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 8px;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
    }

    .doc-zoom-cerrar:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .doc-zoom-imagen {
        min-height: 0;
        padding: 10px;
        background: repeating-conic-gradient(#f1f5f9 0% 25%, #ffffff 0% 50%) 50% / 16px 16px;
        overflow: auto;
    }

    .doc-zoom-imagen img {
        display: block;
        max-width: 100%;
        max-height: calc(100vh - 150px);
        margin: 0 auto;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }

    .doc-zoom-ayuda {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.78rem;
    }

    @keyframes zoomFondo {
        from {
            opacity: 0;
        }
    }

    @keyframes zoomMarco {
        from {
            opacity: 0;
            transform: scale(0.92);
        }
    }

    /* ============================================================
       MODO DE LECTURA: COMPLETA / PASO A PASO
       ============================================================ */
    .ayuda-modo-titulo {
        color: var(--text-heading);
        font-weight: 600;
        margin-bottom: 16px;
    }

    .ayuda-modo-opcion {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 28px 20px;
        text-align: center;
        background: var(--card-bg);
        border: 2px solid var(--border-light);
        border-radius: 12px;
        color: var(--text-primary);
        transition: all 0.2s ease;
    }

    .ayuda-modo-opcion:hover {
        border-color: #f97316;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.12);
    }

    .ayuda-modo-opcion i {
        font-size: 2rem;
        color: #f97316;
    }

    .ayuda-modo-opcion strong {
        font-size: 1.05rem;
    }

    .ayuda-modo-opcion small {
        color: var(--text-secondary);
    }

    .ayuda-cambiar-modo {
        display: inline-flex;
        padding: 4px;
        gap: 4px;
        background: var(--bg-subtle);
        border-radius: 10px;
    }

    .ayuda-cambiar-modo button {
        border: none;
        background: transparent;
        color: var(--text-secondary);
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .ayuda-cambiar-modo button.activo {
        background: var(--card-bg);
        color: var(--text-primary);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
    }

    .ayuda-barra-modo {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px 16px;
        margin-bottom: 12px;
    }

    .pasos-estado {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 260px;
    }

    /* Stepper */
    .pasos-contador {
        flex-shrink: 0;
        font-size: 0.85rem;
        font-weight: 600;
        color: #f97316;
    }

    .pasos-lista {
        flex: 1;
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pasos-lista li {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .pasos-lista li:not(:last-child)::after {
        content: '';
        flex: 1;
        height: 3px;
        margin: 0 2px;
        border-radius: 3px;
        background: var(--border-light);
    }

    .pasos-lista li:last-child {
        flex: 0;
    }

    .pasos-lista li.hecho::after {
        background: var(--gradient-end);
    }

    .pasos-lista button {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--border-light);
        background: var(--card-bg);
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0;
        transition: all 0.2s ease;
    }

    /* Hechos en el azul oscuro del sistema; el actual en naranja */
    .pasos-lista li.hecho button {
        border-color: var(--gradient-end);
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        color: #fff;
    }

    .pasos-lista li.actual button {
        border-color: #f97316;
        background: #f97316;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.25);
    }

    /* Contenido del paso: compacto */
    .pasos-contenido {
        max-width: none;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .pasos-contenido .pasos-encabezado h3.paso {
        font-size: 1.05rem;
        gap: 10px;
    }

    .pasos-contenido .paso-numero {
        width: 30px;
        height: 30px;
        font-size: 0.95rem;
    }

    .pasos-contenido p,
    .pasos-contenido ol,
    .pasos-contenido ul {
        margin-bottom: 0.6rem;
    }

    .pasos-texto > :last-child,
    .pasos-contenido blockquote p:last-child {
        margin-bottom: 0;
    }

    .pasos-contenido blockquote {
        padding: 8px 12px;
        margin: 0.6rem 0;
    }

    .pasos-contenido table {
        margin: 0.4rem 0;
        font-size: 0.8rem;
    }

    .pasos-contenido th,
    .pasos-contenido td {
        padding: 5px 6px;
        vertical-align: top;
    }

    .pasos-imagenes .doc-figura {
        margin: 0 0 8px;
        text-align: center;
    }

    .pasos-imagenes.varias {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        align-items: start;
    }

    .pasos-imagenes .doc-figura img {
        width: auto;
        max-width: 100%;
        max-height: var(--alto-captura, 45vh);
        object-fit: contain;
    }

    .pasos-imagenes .doc-figura-pendiente > div {
        min-height: 0;
        height: min(var(--alto-captura, 45vh), 220px);
    }

    @media (min-width: 992px) {
        .pasos-cuerpo.con-imagen {
            display: grid;
            grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
            gap: 20px;
            align-items: start;
        }

        .pasos-cuerpo.con-imagen.texto-amplio {
            grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
        }

        /* Si el texto no cabe, se desplaza dentro de su columna y no la página */
        .pasos-cuerpo.con-imagen .pasos-texto {
            max-height: var(--alto-texto, none);
            overflow-y: auto;
            padding-right: 4px;
        }
    }

    .pasos-nav {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--border-light);
    }

    .pasos-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 5px 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        transition: all 0.2s ease;
    }

    .pasos-btn-anterior {
        background: var(--bg-subtle);
        color: var(--text-secondary);
    }

    .pasos-btn-anterior:disabled {
        opacity: 0.4;
    }

    .pasos-btn-siguiente {
        margin-left: auto;
        text-align: right;
        color: #fff;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    }

    .pasos-btn-siguiente:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
    }

    .pasos-btn-texto {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .pasos-btn-texto small {
        font-weight: 400;
        font-size: 0.7rem;
        opacity: 0.75;
    }

    .pasos-fin {
        text-align: center;
        padding: 24px 16px;
    }

    .pasos-fin > i {
        font-size: 2.5rem;
        color: #f97316;
    }

    .pasos-fin h4 {
        margin-top: 8px;
        color: var(--text-heading);
        text-transform: none;
    }

    .pasos-fin p {
        color: var(--text-secondary);
    }

    @media (max-width: 575.98px) {
        .pasos-lista button {
            width: 24px;
            height: 24px;
            font-size: 0.68rem;
        }

        .pasos-btn-anterior span,
        .pasos-contador {
            display: none;
        }

        .pasos-estado {
            min-width: 0;
            flex-basis: 100%;
        }
    }

    .version-item {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-light);
    }

    .version-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .version-titulo {
        color: var(--text-heading);
        font-weight: 600;
        font-size: 1.05rem;
    }
</style>

<div
    id="docZoom"
    class="doc-zoom-overlay"
>
    <figure class="doc-zoom-marco">
        <div class="doc-zoom-barra">
            <i class="bi bi-zoom-in"></i>
            <span class="doc-zoom-etiqueta">Captura ampliada</span>
            <span
                id="docZoomTitulo"
                class="doc-zoom-titulo"
            ></span>
            <button
                type="button"
                class="doc-zoom-cerrar"
                title="Cerrar (Esc)"
            ><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="doc-zoom-imagen">
            <img alt="">
        </div>
    </figure>
    <div class="doc-zoom-ayuda">Clic fuera de la imagen o Esc para cerrar</div>
</div>

<script>
    (function() {
        var zoom = document.getElementById('docZoom');

        function cerrar() {
            zoom.classList.remove('abierto');
        }

        document.addEventListener('click', function(e) {
            var enlace = e.target.closest('.doc-zoom');

            if (enlace) {
                e.preventDefault();
                var img = enlace.querySelector('img');
                zoom.querySelector('.doc-zoom-imagen img').src = enlace.getAttribute('href');
                zoom.querySelector('.doc-zoom-imagen img').alt = img ? img.alt : '';
                document.getElementById('docZoomTitulo').textContent = img ? img.alt : '';
                zoom.classList.add('abierto');
            } else if (e.target.closest('.doc-zoom-cerrar') || (e.target.closest('#docZoom') && !e.target.closest('.doc-zoom-marco'))) {
                cerrar();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') cerrar();
        });
    })();
</script>
