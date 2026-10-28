---
titulo: Hacer una devolución
descripcion: Crea la devolución desde la orden de Oracle y procésala hasta la factura
categoria: Procesos
icono: arrow-return-left
rutas: OrdenesOracle, Devoluciones
orden: 10
actualizado: 28/10/2026
---

## Resumen

Una devolución se hace en **dos pantallas**:

1. **Órdenes Oracle**: se **crea** la devolución a partir de la orden de venta.
2. **Devoluciones**: se **procesa** en Oracle en 4 pasos: enviar, crear recepción, confirmar y facturar.

> Crear la devolución **no mueve inventario ni importes todavía**. Solo deja registrado el encabezado y los productos a devolver para procesarlos después en la pantalla **Devoluciones**.

## Parte 1. Crear la devolución

[Ir a Órdenes Oracle](ruta:OrdenesOracle){.boton}

### Busca la orden {.paso}

1. Entra a **Devoluciones → Órdenes Oracle**.
2. En **Número de orden** escribe la orden de venta, por ejemplo `POS_123232`.
3. Da clic en **Buscar orden**.

![Buscar la orden por número](devoluciones/01-buscar-orden.png)

Se muestran los datos generales de la orden (cliente, total, almacén) y la tabla de productos con las columnas **Disp. Devolución** (lo que todavía se puede devolver) y **Devuelto** (lo que ya se devolvió antes).

### Abre el formulario de devolución {.paso}

Da clic en **Crear Devolución**. Se abre una ventana con los productos que todavía tienen cantidad disponible para devolver.

![Botón Crear Devolución en el detalle de la orden](devoluciones/02-boton-crear-devolucion.png)

### Captura las cantidades a devolver {.paso}

Llena la columna **Cant. a Devolver** de una de estas **cuatro formas**:

- **Por ticket:** escribe el ticket arriba (ej. `POS739999`) y da clic en **Buscar**. Se llenan las cantidades de ese ticket.
- **Manual:** escribe la cantidad en cada producto.
- **Max:** botón a la derecha de cada producto; pone todo lo disponible de ese producto.
- **Devolución Total:** llena todos los productos con su máximo.

![Ventana Crear Devolución con las opciones para capturar cantidades](devoluciones/03-capturar-cantidades.png)

> No se puede capturar más de lo que dice **Disp. Devolución**. Para borrar lo capturado usa **Limpiar** o **Resetear**.

### Revisa los totales y crea la devolución {.paso}

Abajo de la ventana se calculan solos el **Total Kilos** y el **Total Importe** de lo que vas a devolver.

En cuanto haya al menos un producto con cantidad, se habilita el botón **Crear Devolución**. Da clic para crearla.

![Totales de kilos e importe y botón Crear Devolución](devoluciones/04-totales.png)

### Copia el folio {.paso}

Aparece el mensaje **¡Devolución creada!** con el **Folio** de la devolución. Da clic en **Copiar**: lo vas a necesitar en la siguiente pantalla.

![Mensaje de devolución creada con el folio](devoluciones/05-folio.png)

## Parte 2. Procesar la devolución en Oracle

[Ir a Devoluciones](ruta:Devoluciones){.boton}

### Busca y abre la devolución {.paso}

1. Entra a la pantalla **Devoluciones**.
2. En **Folio** pega el folio que copiaste (o busca por **Orden Venta**) y da clic en **Buscar**.
3. Da clic en el renglón de la devolución para abrir su detalle.

![Buscar la devolución por folio](devoluciones/06-buscar-folio.png)

En el detalle solo aparece **el botón del siguiente paso** que toca hacer.

### Envía el pedido de devolución {.paso}

Da clic en **Enviar Pedido Devolución**. Se envía al momento y aparece un mensaje de éxito.

![Botón Enviar Pedido Devolución](devoluciones/07-enviar.png)

Si el envío falla, la devolución queda en **Error** con el mensaje del motivo, y el botón vuelve a aparecer para reintentar.

### Crea la recepción {.paso}

Da clic en **Crear Recepción Devolución**. Se crea al momento.

![Botón Crear Recepción Devolución](devoluciones/08-crear-recepcion.png)

### Confirma la recepción {.paso}

Da clic en **Confirmar Recepción**. **Aquí es donde la devolución se aplica en Oracle.**

![Botón Confirmar Recepción](devoluciones/09-confirmar-recepcion.png)

> ⏳ **Este paso tarda.** Oracle procesa la recepción por su cuenta. Hay que esperar a que las líneas cambien a **Awaiting Billing** (en espera de facturación). Mientras tanto no aparece el siguiente botón.
>
> Da clic en **Actualizar** (en el detalle de la devolución) cada cierto tiempo para consultar el estatus.

### Crea la factura de la devolución {.paso}

Cuando las líneas estén en **Awaiting Billing**, aparece el botón **Crear Factura Devolución**. Da clic para mandar a facturar.

![Botón Crear Factura Devolución](devoluciones/11-crear-factura.png)

> ⏳ **Este paso también tarda.** Espera a que las líneas queden en **Closed** (cerrada). Usa **Actualizar** para revisar.

Cuando las líneas están en **Closed**, la devolución quedó terminada. ✅

![Devolución terminada con las líneas en Closed](devoluciones/12-closed.png)

## Estatus en Oracle

| Estatus de las líneas | Qué significa | Qué sigue |
|---|---|---|
| **Awaiting Receiving** | El pedido de devolución ya está en Oracle, falta la recepción | Crear y confirmar la recepción |
| **Awaiting Billing** | La recepción ya se aplicó, falta facturar | Crear Factura Devolución |
| **Closed** | Devolución terminada | Nada |

## Preguntas frecuentes

**No aparece el botón Crear Devolución en Órdenes Oracle.**
Revisa que la orden tenga productos con **Disp. Devolución** mayor a cero. Si dice *Sin productos pendientes*, todo ya fue devuelto.

**El botón Crear Devolución de la ventana está gris.**
Ningún producto tiene cantidad capturada. Captura al menos una.

**Confirmé la recepción y no aparece el botón de factura.**
Es normal: Oracle todavía está procesando. Espera unos minutos y da clic en **Actualizar** hasta que las líneas estén en **Awaiting Billing**.

**Perdí el folio.**
En **Devoluciones** busca por **Orden Venta** y abre la devolución desde la lista.
