# Documentación para usuarios

Los archivos de esta carpeta se muestran dentro de Posweb en **/Ayuda**.

## Agregar un manual

Crea `manuales/<nombre>.md`. El nombre del archivo es la URL: `/Ayuda/<nombre>`.

```markdown
---
titulo: Dashboard por Tienda
descripcion: Texto corto que aparece en la tarjeta del índice
categoria: Reportes
icono: shop
rutas: DashTienda, DashTienda.grafica
roles: 1, 4
orden: 10
actualizado: 28/10/2026
---

## ¿Para qué sirve?
...
```

| Campo | Uso |
|---|---|
| `titulo` | Título del manual |
| `descripcion` | Resumen en la tarjeta del índice |
| `categoria` | Agrupa los manuales en el índice (Catálogos, Reportes, POS...) |
| `icono` | Nombre de un [Bootstrap Icon](https://icons.getbootstrap.com) sin `bi-` |
| `rutas` | Nombres de ruta (`->name()`) donde aparece el botón **Ayuda**, separados por coma |
| `roles` | `IdTipoUsuario` que pueden verlo. Vacío = todos |
| `orden` | Orden dentro de la categoría (menor primero) |
| `actualizado` | Fecha que se muestra al final del manual |

### Pasos, botones e imágenes

```markdown
## Parte 1. Crear la devolución

[Ir a Órdenes Oracle](ruta:OrdenesOracle){.boton}

### Busca la orden {.paso}

Texto del paso...

![Buscar la orden por número](devoluciones/01-buscar-orden.png)
```

| Sintaxis | Resultado |
|---|---|
| `### Título {.paso}` | Paso grande con número automático. La numeración reinicia en cada `##` |
| `[Texto](ruta:NombreRuta){.boton}` | Botón que lleva a la pantalla. Usa el nombre de la ruta (`->name()`), no la URL. Si va justo debajo de un `##`, se convierte en la etiqueta **Pantalla: …** de esa sección (escribe `Ir a Órdenes Oracle` y la etiqueta dirá *Órdenes Oracle*) |
| `[Texto](manual:slug){.boton}` | Botón a otro manual del centro de ayuda (ej. `manual:devoluciones`) |
| `[Texto](https://...){.boton}` | Enlace a otro sitio (ej. portal de facturación); se abre en otra pestaña |
| `![Descripción](carpeta/imagen.png)` | Imagen de `public/img/docs/carpeta/imagen.png` con pie de foto y zoom al dar clic |

Si la imagen todavía no existe se muestra un recuadro **"Captura pendiente"** con la ruta donde debe ir. Así puedes escribir el manual primero y tomar las capturas después.

Recomendaciones para capturas: una carpeta por manual, nombres numerados (`01-...png`), PNG o JPG de máximo ~1400 px de ancho, y recortar a la parte relevante de la pantalla.

El HTML dentro del Markdown se elimina por seguridad; usa solo sintaxis Markdown.

## Publicar una versión

> Con Claude Code puedes decir **"voy a subir cambios"** (o `/preparar-version`) y se encarga de todo este proceso siguiendo las reglas de `.claude/skills/preparar-version/SKILL.md`: propone el número, redacta la nota, actualiza manuales, prepara scripts de BD y te pide confirmación antes del commit y el tag.

Manualmente:

1. Sube `version` en `config/app.php` (por ejemplo `2.2.0`).
2. Crea `versiones/2.2.0.md` con lo que cambió, escrito para el usuario:

```markdown
---
version: 2.2.0
titulo: Resumen de una línea
fecha: 2026-11-15
---

### Nuevo
- ...

### Mejoras
- ...

### Correcciones
- ...
```

3. Haz commit y crea el tag: `git tag v2.2.0`.

Al cambiar la versión, cada usuario ve una vez el aviso **"Posweb se actualizó"**.

Numeración: `MAYOR.MENOR.CORRECCIÓN`. Sube *corrección* para arreglos, *menor* para funciones nuevas y *mayor* para cambios grandes.
