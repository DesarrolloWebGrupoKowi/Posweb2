---
titulo: Refacturar un ticket vencido
descripcion: Habilita un ticket fuera de tiempo para que el cliente pueda volver a facturarlo
categoria: Procesos
icono: receipt-cutoff
rutas: TicketFacturacion, DashVentaPorTicket
orden: 20
actualizado: 28/10/2026
---

## Resumen

Sirve cuando un cliente necesita facturar (o volver a facturar) un ticket que **ya pasó su tiempo para facturarse**. El proceso pasa por varias pantallas:

1. **Reporte de Ventas por Ticket**: revisar si el ticket ya tiene factura.
2. **Solicitudes de Factura**: cancelar la factura anterior (solo si tiene).
3. **Órdenes Oracle / Devoluciones**: hacer la devolución del ticket.
4. **Habilitar Tickets para Facturación**: habilitar el ticket.
5. **Portal de facturación**: el cliente solicita la factura nueva.
6. **Reporte de Ventas por Ticket**: confirmar que llegó la solicitud.
7. **Dashboard por Tienda**: crear el pedido y facturar en Oracle.

> ⚠️ **La habilitación del ticket solo dura el día de hoy.** Haz las partes 4 y 5 el **mismo día**. Si se vence, hay que volver a habilitarlo.

## Parte 1. Revisa el ticket

[Ir a Reporte de Ventas por Ticket](ruta:DashVentaPorTicket){.boton}

### Busca el ticket {.paso}

1. Elige la **Tienda** y la **Fecha** del ticket.
2. Escribe el **N° Ticket** (o el **ID Encabezado** si lo tienes) y da clic en **Buscar**.

![Búsqueda del ticket en el reporte](refacturar/01-buscar-ticket.png)

### Revisa si tiene factura y anota sus datos {.paso}

En el detalle del ticket revisa si aparece el bloque **📋 Factura:** con el número de solicitud y el cliente.

- **Sí tiene factura** → continúa con la **Parte 2**.
- **Es de contado** (no tiene factura) → salta a la **Parte 3**.

Anota también:

- El **Encabezado** del ticket: lo usarás para habilitarlo en la Parte 4.
- El **Pedido** (`POS_...`): lo usarás para la devolución en la Parte 3.

![Ticket con solicitud de factura](refacturar/02-ticket-con-factura.png)

## Parte 2. Cancela la factura anterior

[Ir a Solicitudes de Factura](ruta:SolicitudesFactura){.boton}

> Solo si el ticket **tiene factura**. Si es de contado, salta a la Parte 3.

### Abre la solicitud de factura {.paso}

En el reporte, junto a **📋 Factura**, da clic en **Ver solicitud**: se abre Solicitudes de Factura ya filtrada por ese folio. También puedes buscarla tú en **Filtros avanzados → Folio**.

Abre la solicitud.

![Lista de solicitudes de factura](refacturar/03-solicitudes.png)

### Cancela la solicitud {.paso}

Da clic en **Cancelar Solicitud**, confirma en la ventana y verifica el mensaje *Solicitud de factura cancelada correctamente*.

![Ventana para cancelar la solicitud](refacturar/04-cancelar-solicitud.png)

## Parte 3. Haz la devolución del ticket

[Ir a Órdenes Oracle](ruta:OrdenesOracle){.boton}

### Haz la devolución completa {.paso}

Haz la devolución del ticket siguiendo el manual **Hacer una devolución**. Usa el **Pedido** que anotaste y, en la ventana de devolución, la opción **Por ticket** con el número del ticket.

Termina todo el proceso de la devolución (hasta que las líneas queden en **Closed**) antes de seguir.

[Ver manual: Hacer una devolución](manual:devoluciones){.boton}

## Parte 4. Habilita el ticket

[Ir a Habilitar Tickets para Facturación](ruta:TicketFacturacion){.boton}

### Habilita el ticket {.paso}

1. En **Id Encabezado / Folio** escribe el **Encabezado** que anotaste y da clic en **Buscar**.
2. Da clic en **Habilitar Ticket**.
3. En la ventana escribe el motivo (ej. *Refacturación por cambio de datos fiscales*) y confirma.

El ticket aparece en la tabla de abajo con estatus **Activo hoy**. Si al buscarlo dice **Ticket ya facturado**, la factura anterior no se canceló: regresa a la Parte 2.

![Ticket habilitado con estatus Activo hoy](refacturar/06-habilitado.png)

> ⏳ Al día siguiente el estatus cambia a **Expirado**. Si el cliente no alcanzó a facturar, búscalo de nuevo y da clic en **Rehabilitar Ticket**.

## Parte 5. Solicita la factura nueva

[Ir a Portal de facturación](https://facturacion.kowi.com.mx/){.boton}

### Solicita la factura en el portal {.paso}

El cliente (o tú, con sus datos fiscales) entra al **portal de facturación**, captura los datos del ticket y solicita la factura.

Debe hacerse **el mismo día** en que se habilitó el ticket.

![Portal de facturación](refacturar/07-portal.png)

## Parte 6. Confirma que llegó la solicitud

[Ir a Reporte de Ventas por Ticket](ruta:DashVentaPorTicket){.boton}

### Verifica la nueva factura en el ticket {.paso}

Busca el ticket otra vez (igual que en la Parte 1) y revisa que aparezca el bloque **📋 Factura:** con la **nueva** solicitud y el cliente correcto.

![Ticket con la nueva solicitud de factura](refacturar/08-nueva-factura.png)

## Parte 7. Crea el pedido y factura en Oracle

[Ir a Dashboard por Tienda](ruta:DashTienda){.boton}

### Busca la tienda y fecha del ticket {.paso}

Elige la **Tienda** y la **Fecha de venta del ticket** y da clic en **Buscar**. La solicitud aparece en la tabla **Corte Tienda** como **Facturación en Línea**.

![Solicitud en el corte de la tienda](refacturar/09-corte-tienda.png)

### Envía el pedido {.paso}

En el renglón de la solicitud da clic en **ENVIAR**. Esto crea el pedido en Oracle. Espera a que el estatus cambie a **PROCESADO**.

![Botón ENVIAR en la solicitud](refacturar/10-enviar-pedido.png)

### Despacha y genera la factura {.paso}

La columna **Oracle** muestra el estatus del pedido y, en **Acciones**, el botón que toca:

| Estatus en Oracle | Botón | Qué hace |
|---|---|---|
| **Awaiting Shipping** | **DESPACHO** | Despacha el inventario |
| **Awaiting Billing** | **GENERAR FACTURA** | Manda facturar |
| **Closed** | — | Factura terminada |

> ⏳ Entre cada botón, Oracle tarda en cambiar el estatus. Da clic en **Actualizar** para volver a consultar.

![Botón GENERAR FACTURA con estatus Awaiting Billing](refacturar/11-generar-factura.png)

### Verifica el UUID {.paso}

Cuando el pedido esté en **Closed**, revisa la columna **Oracle**:

- **Closed & UUID** ✅: la factura quedó lista.
- Si aparece el botón **ENVIAR UUID**, da clic para sincronizar el UUID de la factura con Oracle.

![Pedido Closed con UUID](refacturar/12-closed-uuid.png)

## Preguntas frecuentes

**En Habilitar Tickets dice "Ticket ya facturado".**
Primero hay que cancelar la factura anterior (Parte 2).

**En Habilitar Tickets dice "Ticket cancelado".**
Un ticket cancelado no se puede habilitar para facturación.

**El cliente no alcanzó a facturar el mismo día.**
El ticket queda **Expirado**. Búscalo en Habilitar Tickets y da clic en **Rehabilitar Ticket**.

**No aparece la solicitud en el Dashboard por Tienda.**
Revisa que estés buscando la **fecha de venta del ticket** (no la de hoy) y que la solicitud ya se vea en el Reporte de Ventas por Ticket (Parte 6).
