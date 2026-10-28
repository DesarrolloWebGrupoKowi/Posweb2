<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;

/**
 * Lee los manuales y notas de versión escritos en Markdown dentro de resources/docs.
 *
 * Cada archivo puede iniciar con un encabezado entre "---" con datos clave: valor,
 * las listas (rutas, roles) se separan por comas.
 */
class AyudaService
{
    protected static ?Collection $manuales = null;

    // Manuales que puede ver el usuario actual, ordenados
    public function manuales(): Collection
    {
        self::$manuales ??= collect(glob(resource_path('docs/manuales/*.md')))
            ->map(fn($archivo) => $this->leer($archivo))
            ->sortBy([['orden', 'asc'], ['titulo', 'asc']])
            ->values();

        $tipoUsuario = (int) Auth::user()?->IdTipoUsuario;

        return self::$manuales
            ->filter(fn($manual) => empty($manual['roles']) || in_array($tipoUsuario, $manual['roles']))
            ->values();
    }

    public function manual(string $slug): ?array
    {
        return $this->manuales()->firstWhere('slug', $slug);
    }

    // Manual ligado al nombre de ruta de la pantalla actual (para el botón de ayuda)
    public function manualPorRuta(?string $ruta): ?array
    {
        if (!$ruta) {
            return null;
        }

        return $this->manuales()->first(fn($manual) => in_array($ruta, $manual['rutas']));
    }

    // Notas de versión, de la más reciente a la más antigua
    public function versiones(): Collection
    {
        return collect(glob(resource_path('docs/versiones/*.md')))
            ->map(fn($archivo) => $this->leer($archivo))
            ->sort(fn($a, $b) => version_compare($b['version'], $a['version']))
            ->values();
    }

    /**
     * Convierte el Markdown a HTML con las convenciones de los manuales:
     *  - `### Título {.paso}`             paso grande numerado
     *  - `[Texto](ruta:NombreRuta){.boton}` botón a una pantalla por nombre de ruta
     *  - `![Descripción](carpeta/img.png)`  imagen en public/img/docs, con zoom y pie de foto
     */
    public function html(array $documento): string
    {
        $html = Str::markdown($documento['markdown'], [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ], [new AttributesExtension()]);

        // {…} no debe poder agregar eventos JS ni estilos
        $html = preg_replace_callback(
            '/<[a-z][^>]*>/i',
            fn($m) => preg_replace('/\s(on[a-z]+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $m[0]),
            $html
        );

        // Numera los pasos; cada sección (##) empieza de nuevo en 1
        $paso = 0;
        $html = preg_replace_callback(
            '/<h2[^>]*>|<h3 class="paso">/',
            function ($m) use (&$paso) {
                if (str_starts_with($m[0], '<h2')) {
                    $paso = 0;
                    return $m[0];
                }
                return $m[0] . '<span class="paso-numero">' . ++$paso . '</span>';
            },
            $html
        );

        $html = preg_replace_callback(
            '/href="ruta:([\w.\-]+)"/',
            fn($m) => 'href="' . (Route::has($m[1]) ? e(route($m[1])) : '#') . '"',
            $html
        );

        // manual:slug -> otro manual del centro de ayuda
        $html = preg_replace_callback(
            '/href="manual:([\w\-]+)"/',
            fn($m) => 'href="' . e(route('ayuda.show', $m[1])) . '"',
            $html
        );

        // Secciones (##): el primer botón {.boton} de la sección indica en qué pantalla se trabaja
        // y se muestra junto al título como etiqueta con enlace
        $html = preg_replace_callback(
            '/<h2([^>]*)>(.*?)<\/h2>(.*?)(?=<h2|$)/s',
            function ($m) {
                $pantalla = '';
                $cuerpo = $m[3];

                if (preg_match('/<p><a class="boton" href="([^"]*)">(.*?)<\/a><\/p>\s*/s', $cuerpo, $boton)) {
                    $nombre = preg_replace('/^ir a\s+(la\s+pantalla\s+(de\s+)?)?/iu', '', strip_tags($boton[2]));
                    $pantalla = '<a class="seccion-pantalla" href="' . $boton[1] . '" title="Ir a la pantalla">'
                        . '<i class="bi bi-window-sidebar"></i><small>Pantalla</small> ' . $nombre
                        . ' <i class="bi bi-box-arrow-up-right"></i></a>';
                    $cuerpo = str_replace($boton[0], '', $cuerpo);
                }

                $atributos = preg_replace('/\sclass="[^"]*"/', '', $m[1]);

                return '<h2 class="seccion"' . $atributos . '><span class="seccion-titulo">' . $m[2] . '</span>'
                    . $pantalla . '</h2>' . $cuerpo;
            },
            $html
        );

        // Los enlaces a otros sitios (ej. portal de facturación) se abren en otra pestaña
        $hostPropio = parse_url(config('app.url'), PHP_URL_HOST);
        $html = preg_replace_callback(
            '/<a ([^>]*)href="(https?:\/\/[^"]+)"/',
            fn($m) => in_array(parse_url(html_entity_decode($m[2]), PHP_URL_HOST), [$hostPropio, request()->getHost()])
                ? $m[0]
                : '<a ' . $m[1] . 'target="_blank" rel="noopener" href="' . $m[2] . '"',
            $html
        );

        return preg_replace_callback(
            '/<p><img src="([^"]*)" alt="([^"]*)"[^>]*\/?>\s*<\/p>/',
            fn($m) => $this->figura(html_entity_decode($m[1]), $m[2]),
            $html
        );
    }

    protected function figura(string $src, string $alt): string
    {
        $esExterna = Str::startsWith($src, ['http://', 'https://']);
        $ruta = $esExterna || Str::startsWith($src, '/') ? $src : '/img/docs/' . $src;

        if (!$esExterna && !file_exists(public_path(ltrim(urldecode($ruta), '/')))) {
            return '<figure class="doc-figura doc-figura-pendiente">'
                . '<div><i class="bi bi-image"></i> Captura pendiente<small>public' . e($ruta) . '</small></div>'
                . '<figcaption>' . $alt . '</figcaption></figure>';
        }

        $url = $esExterna ? $src : asset(ltrim($ruta, '/'));

        return '<figure class="doc-figura">'
            . '<a href="' . e($url) . '" class="doc-zoom" title="Ver en grande">'
            . '<img src="' . e($url) . '" alt="' . $alt . '" loading="lazy">'
            . '<span class="doc-zoom-pista"><i class="bi bi-arrows-angle-expand"></i> Ampliar</span></a>'
            . '<figcaption>' . $alt . '</figcaption></figure>';
    }

    protected function leer(string $archivo): array
    {
        $contenido = file_get_contents($archivo);
        $meta = [];

        if (preg_match('/^---\R(.*?)\R---\R?(.*)$/s', $contenido, $partes)) {
            foreach (preg_split('/\R/', $partes[1]) as $linea) {
                if (str_contains($linea, ':')) {
                    [$clave, $valor] = array_map('trim', explode(':', $linea, 2));
                    $meta[$clave] = $valor;
                }
            }
            $contenido = $partes[2];
        }

        $lista = fn($valor) => array_values(array_filter(array_map('trim', explode(',', $valor ?? ''))));
        $slug = pathinfo($archivo, PATHINFO_FILENAME);

        return [
            'slug' => $slug,
            'titulo' => $meta['titulo'] ?? Str::headline($slug),
            'descripcion' => $meta['descripcion'] ?? '',
            'categoria' => $meta['categoria'] ?? 'General',
            'icono' => $meta['icono'] ?? 'journal-text',
            'rutas' => $lista($meta['rutas'] ?? null),
            'roles' => array_map('intval', $lista($meta['roles'] ?? null)),
            'orden' => (int) ($meta['orden'] ?? 100),
            // indice: no -> no aparece en el índice, pero sigue disponible en el botón Ayuda de su pantalla
            'indice' => Str::lower($meta['indice'] ?? 'si') !== 'no',
            'version' => $meta['version'] ?? $slug,
            'fecha' => $meta['fecha'] ?? null,
            'actualizado' => $meta['actualizado'] ?? null,
            'markdown' => $contenido,
            'texto' => Str::lower(strip_tags(Str::markdown(preg_replace('/\{[.#][^}]*\}/', '', $contenido)))),
        ];
    }
}
