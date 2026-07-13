# API de Devoluciones - Oracle Nota Crédito

> **Versión:** 1.0  
> **Última actualización:** Julio 2026  
> **Base URL:** `http://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr`

---

# Introducción

Esta API permite realizar el proceso completo de generación de una Nota de Crédito (Devolución) en Oracle.

El flujo consiste en:

1. Consultar una orden.
2. Crear una devolución.
3. Crear la recepción.
4. Confirmar la recepción.
5. Generar la factura (Nota de Crédito).

Cada paso depende del anterior, por lo que **no pueden ejecutarse fuera de orden**.

---

# Arquitectura

```text
                Aplicación
                     │
                     ▼
       API NotaCreditoAr (REST)
                     │
        ┌────────────┴────────────┐
        │                         │
        ▼                         ▼
    Oracle ERP              SQL Server
                                  │
                                  ├── XXKW_CREDIT_FOLIO
                                  ├── XXKW_CREDIT_HEADERS
                                  └── XXKW_CREDIT_LINES
```

---

# Flujo General

```mermaid
-->A[Consultar Orden]
-->B[Crear Devolución]
-->C[Crear Recepción]
-->D[Confirmar Recepción]
-->E[Generar Factura]
```

---

# Flujo de Estados Oracle

```mermaid
-->A[Nueva]
-->B[Awaiting Receipt]
-->C[Awaiting Billing]
-->D[Closed]
```

| Paso                | Estado esperado  |
| ------------------- | ---------------- |
| Crear devolución    | Awaiting Receipt |
| Confirmar recepción | Awaiting Billing |
| Generar factura     | Closed           |

---
# Endpoints

## 1. Consultar Orden

Consulta el detalle de una orden de venta.

**Endpoint**

```http
GET /GetDevolucionOracle?Orden={ORDEN}
```

**Ejemplo**

```http
GET /GetDevolucionOracle?Orden=POS_0000
```

**Nota:** Regresa la cantidad disponible para devolución (`available_quantity`).

---

## 1.1. Consultar Devolución

Consulta una devolución previamente creada.

**Endpoint**

```http
GET /GetSalesOracle?Orden={DEVOLUCION}
```

**Ejemplo**

```http
GET /GetSalesOracle?Orden=DEV_ANT_0000
```

**Diferencia con el endpoint anterior**

| Endpoint | Tipo | Cantidad disponible |
|----------|------|---------------------|
| GetDevolucionOracle | Pedido | ✔ Sí |
| GetSalesOracle | Devolución | ✘ No |

---

## 2. Crear Devolución

Genera una devolución en Oracle.

**Endpoint**

```http
GET /Devolucion?Devolucion={DEVOLUCION}
```

**Ejemplo**

```http
GET /Devolucion?Devolucion=DEV_ANT_15
```

**Validar**

- Oracle → **Awaiting Receipt**
- SQL Server

```sql
SELECT  STATUS, MENSAJE_ERROR 
FROM XXKW_CREDIT_HEADERS 
WHERE Source_Transaction_Identifier='DEV_ANT_23';
```

---

## 3. Crear Recepción

Registra la recepción de la devolución.

**Endpoint**

```http
GET /Recepcion?Devolucion={DEVOLUCION}
```

**Ejemplo**

```http
GET /Recepcion?Devolucion=DEV_ANT_15
```

**Validar**

```sql
SELECT ReceiptNumber, ReceiptHeaderId, STATUS_RECEIPT, MENSAJE_RECEIPT 
FROM XXKW_CREDIT_HEADERS 
WHERE Source_Transaction_Identifier='DEV_ANT_15'
```

---

## 4. Confirmar Recepción

Confirma la recepción y habilita la facturación.

**Endpoint**

```http
GET /ConfirmRecepcion?Devolucion={DEVOLUCION}
```

**Ejemplo**

```http
GET /ConfirmRecepcion?Devolucion=DEV_ANT_15
```

**Validar**

- Oracle → **Awaiting Billing**

---

## 5. Generar Factura

Genera la Nota de Crédito y finaliza el proceso.

**Endpoint**

```http
GET /SendArRecepcion?Devolucion={DEVOLUCION}
```

**Ejemplo**

```http
GET /SendArRecepcion?Devolucion=DEV_ANT_15
```

**Validar**

- Oracle → **Closed**

---

# Base de Datos

## Tablas involucradas

| Tabla               | Descripción                |
| ------------------- | -------------------------- |
| XXKW_CREDIT_FOLIO   | Administración de folios   |
| XXKW_CREDIT_HEADERS | Encabezado de devoluciones |
| XXKW_CREDIT_LINES   | Detalle de artículos       |

---

# Ejemplo de Flujo Completo

```bash
# Consultar pedido
GET /GetDevolucionOracle?Orden=POS_0000

# Crear devolución
GET /Devolucion?Devolucion=DEV_ANT_15

# Crear recepción
GET /Recepcion?Devolucion=DEV_ANT_15

# Confirmar recepción
GET /ConfirmRecepcion?Devolucion=DEV_ANT_15

# Generar factura
GET /SendArRecepcion?Devolucion=DEV_ANT_15
```

---

# Resumen del Flujo

| Paso | Endpoint            | Oracle           | SQL Server                              |
| ---- | ------------------- | ---------------- | --------------------------------------- |
| 1    | GetDevolucionOracle | Consulta Pedido  | —                                       |
| 2    | Devolucion          | Awaiting Receipt | XXKW_CREDIT_HEADERS                     |
| 3    | Recepcion           | Awaiting Receipt | XXKW_CREDIT_HEADERS / XXKW_CREDIT_LINES |
| 4    | ConfirmRecepcion    | Awaiting Billing | Actualiza encabezado                    |
| 5    | SendArRecepcion     | Closed           | Actualiza factura y UUID                |

---

# Notas

- Todos los endpoints utilizan el método **GET**.
- Todos los parámetros son enviados mediante **Query String**.
- Las devoluciones utilizan el formato:

```
DEV_ANT_{Número}
```

Ejemplo:

```
DEV_ANT_15
```

- No se recomienda ejecutar un paso sin verificar previamente que el estado esperado se haya actualizado correctamente en Oracle.
- En caso de error, revisar la respuesta del endpoint y el contenido de las tablas `XXKW_CREDIT_HEADERS` y `XXKW_CREDIT_LINES`, especialmente el campo `MENSAJE_ERROR`.

---

**Versión:** 1.0  
**Autor:** Equipo de Desarrollo  
**Última actualización:** Julio 2026
