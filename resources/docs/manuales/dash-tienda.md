---
titulo: Dashboard por Tienda
descripcion: Consulta ventas, corte del día y envía pedidos a Oracle por tienda
categoria: Reportes
icono: shop
orden: 10
indice: no
actualizado: 28/10/2026
---

## ¿Para qué sirve?

Muestra el resumen de ventas de **una tienda en una fecha**: indicadores del día, el corte de la tienda con el estatus de cada pedido en Oracle, las ventas por hora y la distribución de pagos.

## Consultar una tienda

1. En **Tienda**, elige la tienda.
2. En **Fecha**, elige el día que quieres revisar.
3. Da clic en **Buscar**.

> Si eliges solo la **fecha** sin tienda, el sistema te lleva al resumen de **todas las tiendas** de ese día.

Para empezar de nuevo, usa **Limpiar**.

### Buscar un pedido

Escribe el número en **Pedido** (por ejemplo `POS_123456`) y da clic en **Buscar**. Si no elegiste tienda ni fecha, el sistema las toma del pedido.

### Ver el detalle

Si activas **Detalle** antes de buscar, el sistema te lleva al corte detallado de la tienda.

## Indicadores

| Indicador | Qué muestra |
|---|---|
| **Solic. Facturas** | Solicitudes de factura del día y cuántas siguen pendientes |
| **Tickets** | Número de tickets y el promedio por ticket |
| **Kilos** | Kilos vendidos y el promedio por ticket |
| **Ventas Hoy** | Total vendido y la comparación contra el día anterior |

## Tabla "Corte tienda"

Cada renglón es un pedido del corte:

- **Cliente**: cliente de Oracle al que se cargó la venta.
- **Pedido**: número del pedido en Oracle. *SIN PEDIDO* indica que la venta no tiene pedido generado.
- **Estatus**:
  - **PROCESADO**: el pedido ya está en Oracle.
  - **SIN PROCESAR**: aún no se envía.
  - **ERROR**: Oracle lo rechazó. Debajo del renglón aparece el mensaje con el motivo.
- **Oracle**: estatus que regresa Oracle en ese momento (se consulta al abrir la pantalla). Cuando la factura de contado ya está timbrada aparecen los botones **PDF** y **XML**.
- **Cantidad** y **Ventas**: kilos e importe del pedido.

Las ventas con solicitud de factura aparecen como **Facturación en Línea**.

Usa **Expandir** para ver la tabla en pantalla completa.

## Enviar un pedido a Oracle

*Solo para usuarios con permiso.*

1. Busca el renglón con estatus **SIN PROCESAR** o **ERROR**.
2. Da clic en **ENVIAR** en la columna Acciones.
3. Espera a que el estatus cambie. Si vuelve a marcar **ERROR**, revisa el mensaje debajo del renglón.

## Procesar el corte

*Solo para usuarios con permiso.*

Los botones **Procesar contado** y **Procesar facturas** aparecen cuando:

- La fecha consultada **no es hoy**, y
- El corte tiene información, y
- El proceso automático de esa tienda está detenido.

Si ves el aviso naranja **Proceso detenido**, el envío automático de esa tienda está pausado. El aviso indica la fecha y quién lo detuvo.

## Descargar el corte

Da clic en **Descargar corte** (botón verde del encabezado) para abrir el PDF del corte de la tienda y fecha consultadas.

## Preguntas frecuentes

**No me aparece la tienda en la lista.**
Solo ves las tiendas que tienes asignadas. Pide al administrador que te las asigne.

**No veo el botón ENVIAR ni la columna Acciones.**
Tu tipo de usuario no tiene permiso para enviar pedidos.

**La columna Oracle se queda en "-".**
Oracle no respondió. Da clic en **Actualizar** para volver a consultar.
