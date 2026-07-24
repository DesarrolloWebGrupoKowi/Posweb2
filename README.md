# POSWEB 2

Sistema web para la administración del Punto de Venta (POS) de **Kowi**, desarrollado con Laravel.

## Características

- Gestión de ventas
- Administración de pedidos
- Proceso de devoluciones
- Catálogos
- Reportes
- Administración de usuarios y roles

## Tecnologías

- Laravel
- PHP 8+
- SQL Server
- JavaScript
- Bootstrap
- Vite

## Instalación

Clonar el proyecto

```bash
git clone <repositorio>
```

Instalar dependencias

```bash
composer install
npm install
```

Copiar el archivo de configuración

```bash
cp .env.example .env
```

Generar la llave de la aplicación

```bash
php artisan key:generate
```

Iniciar el servidor

```bash
php artisan serve
```

---

## Documentación

La documentación adicional del proyecto se encuentra en la carpeta **docs**.

- [API de Devoluciones Oracle](docs/api-devoluciones-oracle.md)

---

## Estructura del proyecto

```text
app/
bootstrap/
config/
database/
docs/
public/
resources/
routes/
storage/
tests/
```

---

## Autor

**Daniel Hernandez**
