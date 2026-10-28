---
name: preparar-version
description: Prepara una liberación de Posweb antes de subir cambios a producción (versión, notas para el usuario, scripts de BD, checklist de despliegue y tag). Usar cuando el usuario diga "voy a subir cambios", "prepara la versión", "prepárame todo para subir", "nueva versión" o invoque /preparar-version.
---

# Preparar versión de Posweb

Objetivo: dejar todo listo para subir cambios a producción siguiendo siempre las mismas reglas. **Nada se sube, se commitea ni se etiqueta sin que el usuario lo confirme.**

## Reglas fijas

- **Nunca ejecutar cambios en base de datos.** Si hacen falta, se redacta el script y el usuario lo corre.
- **Nunca hacer `git push`** salvo que el usuario lo pida explícitamente.
- Versionado `MAYOR.MENOR.CORRECCIÓN`:
  - **CORRECCIÓN** (2.1.0 → 2.1.1): solo arreglos de errores.
  - **MENOR** (2.1.0 → 2.2.0): funciones, pantallas, filtros o botones nuevos.
  - **MAYOR** (2.x → 3.0.0): rediseños o cambios grandes de flujo. Solo si el usuario lo confirma.
- Si los cambios son **solo internos** (refactor, código que el usuario no nota), recomendar **no subir versión**: cada versión muestra el aviso "Posweb se actualizó" a todos los usuarios.

## Procedimiento

### 1. Estado del repositorio

1. `git status` y `git branch --show-current`.
2. Si hay cambios sin commitear, listarlos agrupados y preguntar si entran en esta versión (se commitean antes) o se quedan fuera.
3. Confirmar con el usuario **a qué servidor/rama se sube** (ramas existentes: `gh-server`, `gh-production`, `gh-navojoa`, `gh-obregon`, `main`). El tag se crea en esa rama.

### 2. Qué cambió desde la última versión

1. Último tag: `git describe --tags --abbrev=0`. Si no hay tags, usar como base el commit donde se definió la versión actual en `config/app.php` (`git log -S"'version' =>" -- config/app.php`) y avisar que será el primer tag.
2. Revisar `git log <base>..HEAD --oneline` y `git diff <base>..HEAD --stat`, y leer los diffs relevantes (no basta el mensaje del commit).
3. Clasificar cada cambio en: **Nuevo**, **Mejoras**, **Correcciones** o **Interno** (no se menciona al usuario).
4. Proponer el número de versión con una línea de justificación y esperar confirmación.

### 3. Nota de versión

Crear `resources/docs/versiones/X.Y.Z.md`:

```markdown
---
version: X.Y.Z
titulo: Resumen de una línea (máx. ~8 palabras)
fecha: AAAA-MM-DD
---

### Nuevo
- ...

### Mejoras
- ...

### Correcciones
- ...
```

Reglas de redacción:

- Escrita **para el usuario de tienda**, no para programadores: sin nombres de archivos, clases, rutas técnicas ni tablas.
- Nombrar las pantallas como aparecen en el menú (ej. *Reporte de Ventas por Ticket*) y los botones en **negritas** tal como se ven.
- Cada punto dice **qué puede hacer ahora** el usuario o **qué se arregló**, en una línea.
- Máximo ~6 puntos por sección; omitir secciones vacías.
- Fecha de hoy en formato `AAAA-MM-DD`.

Después actualizar `'version' => 'X.Y.Z'` en `config/app.php`.

### 4. Manuales del centro de ayuda

1. Revisar si alguna pantalla modificada tiene manual: buscar su nombre de ruta en `rutas:` de `resources/docs/manuales/*.md`.
2. Si el cambio afecta pasos, botones o textos que el manual describe, **actualizar el manual** y su campo `actualizado:` (formato `DD/MM/AAAA`).
3. Si se agregaron botones o pantallas nuevas que cambian un proceso documentado, avisar qué capturas habría que volver a tomar (`public/img/docs/...`).

### 5. Base de datos

1. Buscar en el diff tablas o columnas nuevas o modificadas (modelos, `DB::table`, `select`, `insert`, `update`, `where` con campos que no existían, `database/migrations`).
2. Si hay cambios de BD, redactar el script en `database/scripts/vX.Y.Z.sql` con comentarios de qué hace cada sentencia, y listarlo en el resumen como **paso que corre el usuario antes de subir el código**.
3. Si no hay cambios de BD, decirlo explícitamente.

### 6. Revisión rápida

- `php -l` en los archivos PHP modificados.
- `php artisan view:cache` para confirmar que las vistas compilan y luego `php artisan view:clear`.
- Revisar que no quede código temporal (buscar `TEMPORAL`, `dd(`, `dump(`, `console.log(` agregados en el diff). Si hay, avisar antes de seguir.

### 7. Resumen y confirmación

Mostrar al usuario, en este orden:

1. Versión propuesta y por qué.
2. La nota de versión tal como la verá el usuario.
3. Manuales actualizados y capturas pendientes.
4. Scripts de BD que debe correr (o "sin cambios de BD").
5. Checklist de despliegue en el servidor:
   - Correr los scripts de BD (si hay).
   - Subir el código.
   - `php artisan optimize:clear`
   - `php artisan config:cache` y `php artisan route:cache` (este proyecto usa caché de config y rutas).
   - Si hay archivos nuevos en `public/` (imágenes, js, css), confirmar que se subieron.
6. Los comandos que se van a ejecutar al confirmar:
   - `git add` de los archivos de la versión
   - `git commit -m "Versión X.Y.Z"`
   - `git tag vX.Y.Z`

Esperar el **sí** del usuario. Al confirmar: commit y tag. El `git push origin <rama>` y `git push origin vX.Y.Z` solo si el usuario lo pide.
