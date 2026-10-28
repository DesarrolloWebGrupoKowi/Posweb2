@php $tienePasos = str_contains($html, 'class="paso"'); @endphp

<x-page-container
    :title="$manual['titulo'] . ' - Ayuda'"
    class="pb-3"
>
    <x-card-gradient-header
        :icon="$manual['icono']"
        :title="$manual['titulo']"
        :subtitle="$manual['descripcion']"
    >
        <x-slot:buttons>
            <a
                href="{{ route('ayuda') }}"
                class="btn-header-ghost text-decoration-none"
            >
                <i class="bi bi-arrow-left"></i> Centro de ayuda
            </a>
            @if ($manual['rutas'] && Route::has($manual['rutas'][0]))
                <a
                    href="{{ route($manual['rutas'][0]) }}"
                    class="btn-header-ghost text-decoration-none"
                >
                    <i class="bi bi-box-arrow-up-right"></i> Ir a la pantalla
                </a>
            @endif
        </x-slot:buttons>

        <div class="p-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="ayuda-lectura">
                    @if ($tienePasos)
                        {{-- Primero se pregunta cómo quiere ver el manual --}}
                        <div
                            id="ayudaModo"
                            class="ayuda-modo"
                        >
                            <h5 class="ayuda-modo-titulo">¿Cómo quieres ver este manual?</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button
                                        type="button"
                                        class="ayuda-modo-opcion"
                                        data-modo="pasos"
                                    >
                                        <i class="bi bi-signpost-split"></i>
                                        <strong>Paso a paso</strong>
                                        <small>Un paso a la vez, para seguirlo mientras lo haces.</small>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button
                                        type="button"
                                        class="ayuda-modo-opcion"
                                        data-modo="completa"
                                    >
                                        <i class="bi bi-file-earmark-text"></i>
                                        <strong>Documentación completa</strong>
                                        <small>Todo el manual en una página, para leer o buscar algo puntual.</small>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            id="ayudaBarraModo"
                            class="ayuda-barra-modo"
                            hidden
                        >
                            <div
                                id="ayudaCambiarModo"
                                class="ayuda-cambiar-modo"
                            >
                                <button
                                    type="button"
                                    data-modo="pasos"
                                ><i class="bi bi-signpost-split"></i> Paso a paso</button>
                                <button
                                    type="button"
                                    data-modo="completa"
                                ><i class="bi bi-file-earmark-text"></i> Completa</button>
                            </div>
                            <div
                                id="pasosEstado"
                                class="pasos-estado"
                            >
                                <ol
                                    id="pasosLista"
                                    class="pasos-lista"
                                ></ol>
                                <span
                                    id="pasosContador"
                                    class="pasos-contador"
                                ></span>
                            </div>
                        </div>
                    @endif

                    <article
                        id="docCompleta"
                        class="doc-content"
                        @if ($tienePasos) hidden @endif
                    >
                        {!! $html !!}
                    </article>

                    @if ($tienePasos)
                        <div
                            id="docPasos"
                            hidden
                        >
                            <article
                                id="pasosContenido"
                                class="doc-content pasos-contenido"
                            ></article>

                            <div
                                id="pasosFin"
                                class="pasos-fin"
                                hidden
                            >
                                <i class="bi bi-check-circle-fill"></i>
                                <h4>¡Terminaste!</h4>
                                <p>Completaste todos los pasos de este manual.</p>
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="btn-subtle"
                                        id="pasosReiniciar"
                                    ><i class="bi bi-arrow-counterclockwise"></i> Volver a empezar</button>
                                    <button
                                        type="button"
                                        class="btn-gradient"
                                        data-modo="completa"
                                    ><i class="bi bi-file-earmark-text"></i> Ver documentación completa</button>
                                </div>
                            </div>

                            <div
                                id="pasosNav"
                                class="pasos-nav"
                            >
                                <button
                                    type="button"
                                    id="pasoAnterior"
                                    class="pasos-btn pasos-btn-anterior"
                                ><i class="bi bi-arrow-left"></i> <span>Anterior</span></button>
                                <button
                                    type="button"
                                    id="pasoSiguiente"
                                    class="pasos-btn pasos-btn-siguiente"
                                >
                                    <span class="pasos-btn-texto">
                                        <small id="pasoSiguienteEtiqueta">Siguiente</small>
                                        <span id="pasoSiguienteTitulo"></span>
                                    </span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if ($manual['actualizado'])
                        <p
                            id="docActualizado"
                            class="text-muted small mb-0 mt-4"
                        >
                            <i class="bi bi-clock-history me-1"></i> Actualizado: {{ $manual['actualizado'] }}
                        </p>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    @include('Ayuda.estilos')

    @if ($tienePasos)
        <script>
            (function() {
                var completa = document.getElementById('docCompleta');
                var contenedorPasos = document.getElementById('docPasos');
                var contenido = document.getElementById('pasosContenido');
                var lista = document.getElementById('pasosLista');
                var fin = document.getElementById('pasosFin');
                var nav = document.getElementById('pasosNav');
                var btnAnterior = document.getElementById('pasoAnterior');
                var btnSiguiente = document.getElementById('pasoSiguiente');
                var pasos = [];
                var actual = 0;

                // Cada "### … {.paso}" es un paso; lo que sigue hasta el próximo paso o "##" es su contenido.
                // Lo que está entre un "##" y su primer paso (ej. el botón "Ir a…") se agrega al primer paso.
                (function armarPasos() {
                    var seccion = null;
                    var previos = [];
                    var paso = null;

                    Array.prototype.forEach.call(completa.children, function(nodo) {
                        if (nodo.tagName === 'H2') {
                            seccion = nodo;
                            previos = [];
                            paso = null;
                        } else if (nodo.matches('h3.paso')) {
                            var numero = nodo.querySelector('.paso-numero');
                            paso = {
                                seccion: seccion,
                                titulo: nodo.textContent.replace(numero ? numero.textContent : '', '').trim(),
                                nodos: previos.concat([nodo])
                            };
                            previos = [];
                            pasos.push(paso);
                        } else if (paso) {
                            paso.nodos.push(nodo);
                        } else if (seccion) {
                            previos.push(nodo);
                        }
                    });
                })();

                pasos.forEach(function(paso, i) {
                    var item = document.createElement('li');
                    item.innerHTML = '<button type="button" title="' + paso.titulo.replace(/"/g, '&quot;') + '">' + (i + 1) + '</button>';
                    item.querySelector('button').addEventListener('click', function() {
                        mostrarPaso(i);
                    });
                    lista.appendChild(item);
                });

                function mostrarPaso(i) {
                    actual = i;
                    var paso = pasos[i];
                    var siguiente = pasos[i + 1];

                    // Encabezado: sección y pantalla arriba, título del paso abajo, en un solo bloque.
                    // Debajo, texto a la izquierda y capturas a la derecha (en pantallas anchas)
                    var encabezado = document.createElement('div');
                    var texto = document.createElement('div');
                    var imagenes = document.createElement('div');
                    var botones = [];
                    encabezado.className = 'pasos-encabezado';
                    texto.className = 'pasos-texto';
                    imagenes.className = 'pasos-imagenes';

                    if (paso.seccion) {
                        var seccion = document.createElement('div');
                        seccion.className = 'pasos-encabezado-seccion';
                        seccion.innerHTML = paso.seccion.innerHTML;
                        encabezado.appendChild(seccion);
                    }

                    contenido.innerHTML = '';
                    contenido.appendChild(encabezado);
                    paso.nodos.forEach(function(nodo) {
                        var copia = nodo.cloneNode(true);
                        copia.querySelectorAll('img').forEach(function(img) {
                            img.loading = 'eager';
                        });
                        if (copia.matches('h3.paso')) encabezado.appendChild(copia);
                        else if (copia.matches('figure.doc-figura')) imagenes.appendChild(copia);
                        else if (copia.querySelector('a.boton')) botones.push(copia);
                        else texto.appendChild(copia);
                    });
                    botones.forEach(function(boton) {
                        texto.appendChild(boton);
                    });

                    var cuerpo = document.createElement('div');
                    cuerpo.className = 'pasos-cuerpo' + (imagenes.children.length ? ' con-imagen' : '');
                    cuerpo.appendChild(texto);
                    if (imagenes.children.length > 1) imagenes.classList.add('varias');
                    if (imagenes.children.length) cuerpo.appendChild(imagenes);
                    contenido.appendChild(cuerpo);

                    // En este modo la numeración es corrida (1 al total)
                    var numero = contenido.querySelector('.paso-numero');
                    if (numero) numero.textContent = i + 1;

                    contenido.hidden = false;
                    nav.hidden = false;
                    fin.hidden = true;
                    ajustarImagenes();

                    document.getElementById('pasosContador').textContent = 'Paso ' + (i + 1) + ' de ' + pasos.length;

                    Array.prototype.forEach.call(lista.children, function(item, j) {
                        item.className = j < i ? 'hecho' : (j === i ? 'actual' : '');
                    });

                    btnAnterior.disabled = i === 0;
                    document.getElementById('pasoSiguienteEtiqueta').textContent = siguiente ? 'Siguiente paso' : 'Último paso';
                    document.getElementById('pasoSiguienteTitulo').textContent = siguiente ? siguiente.titulo : 'Terminar';

                    guardarEnUrl('pasos', i + 1);
                }

                // Las capturas usan el alto que queda libre en la pantalla para que el paso no genere scroll
                function ajustarImagenes() {
                    var cuerpo = contenido.querySelector('.pasos-cuerpo');
                    var figuras = contenido.querySelectorAll('.pasos-imagenes .doc-figura');
                    if (!cuerpo || !figuras.length) return;

                    var arriba = cuerpo.getBoundingClientRect().top + window.scrollY;
                    var abajo = nav.offsetHeight + 56; // botones + padding inferior de card y página
                    var disponible = window.innerHeight - arriba - abajo;
                    // Varias capturas van lado a lado, así que cada una puede usar todo el alto
                    var alto = Math.max(140, disponible - (figuras.length > 1 ? 50 : 30));

                    contenido.style.setProperty('--alto-captura', alto + 'px');
                    contenido.style.setProperty('--alto-texto', Math.max(200, disponible) + 'px');

                    // Si el texto no cabe, se le da más ancho a su columna
                    var texto = cuerpo.querySelector('.pasos-texto');
                    cuerpo.classList.remove('texto-amplio');
                    if (texto.scrollHeight > texto.clientHeight + 1) cuerpo.classList.add('texto-amplio');
                }

                window.addEventListener('resize', function() {
                    if (!contenedorPasos.hidden) ajustarImagenes();
                });

                function mostrarFin() {
                    contenido.hidden = true;
                    nav.hidden = true;
                    fin.hidden = false;
                    document.getElementById('pasosContador').textContent = 'Completado';
                    Array.prototype.forEach.call(lista.children, function(item) {
                        item.className = 'hecho';
                    });
                }

                function cambiarModo(modo) {
                    document.getElementById('ayudaModo').hidden = true;
                    document.getElementById('ayudaBarraModo').hidden = false;
                    document.querySelectorAll('#ayudaCambiarModo button').forEach(function(btn) {
                        btn.classList.toggle('activo', btn.dataset.modo === modo);
                    });

                    completa.hidden = modo !== 'completa';
                    contenedorPasos.hidden = modo !== 'pasos';
                    document.getElementById('pasosEstado').hidden = modo !== 'pasos';
                    var actualizado = document.getElementById('docActualizado');
                    if (actualizado) actualizado.hidden = modo === 'pasos';

                    if (modo === 'pasos') {
                        mostrarPaso(actual);
                    } else {
                        guardarEnUrl('completa');
                    }
                }

                function guardarEnUrl(modo, paso) {
                    var url = new URL(window.location.href);
                    url.searchParams.set('modo', modo);
                    if (paso) url.searchParams.set('paso', paso);
                    else url.searchParams.delete('paso');
                    history.replaceState(null, '', url);
                }

                // Solo regresa arriba si el inicio del paso quedó fuera de la pantalla
                function subir() {
                    if (contenedorPasos.getBoundingClientRect().top < 0) {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                }

                btnAnterior.addEventListener('click', function() {
                    if (actual > 0) mostrarPaso(actual - 1);
                    subir();
                });

                btnSiguiente.addEventListener('click', function() {
                    if (actual < pasos.length - 1) mostrarPaso(actual + 1);
                    else mostrarFin();
                    subir();
                });

                document.getElementById('pasosReiniciar').addEventListener('click', function() {
                    mostrarPaso(0);
                });

                document.querySelectorAll('[data-modo]').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        cambiarModo(btn.dataset.modo);
                    });
                });

                document.addEventListener('keydown', function(e) {
                    if (contenedorPasos.hidden || nav.hidden || /INPUT|TEXTAREA/.test(e.target.tagName)) return;
                    if (e.key === 'ArrowRight') btnSiguiente.click();
                    if (e.key === 'ArrowLeft' && actual > 0) btnAnterior.click();
                });

                // Si la URL ya trae el modo (al recargar o compartir el enlace), no se vuelve a preguntar
                var params = new URLSearchParams(window.location.search);
                var paso = parseInt(params.get('paso'), 10);
                if (paso >= 1 && paso <= pasos.length) actual = paso - 1;
                if (params.get('modo') === 'pasos' || params.get('modo') === 'completa') {
                    cambiarModo(params.get('modo'));
                }
            })();
        </script>
    @endif
</x-page-container>
