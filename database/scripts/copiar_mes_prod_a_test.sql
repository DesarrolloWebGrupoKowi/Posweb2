/* =====================================================================
   Copia un mes completo de UNA tienda
       ORIGEN  (PROD): SERVER_22.VENTAWEB_NEW   (linked server)
       DESTINO (TEST): VENTAWEB_NEW             (BD local)
   Tablas: DatEncabezado, DatDetalle, DatTipoPago, DatCortesTienda

   CÓMO USAR
   1. Ejecutar en el servidor de TEST (donde existe el linked server SERVER_22).
   2. Ajustar @IdTienda y @FechaIni.
   3. Correr primero con @SoloValidar = 1 (hace todo y luego ROLLBACK,
      sólo muestra conteos). Si todo cuadra, correr con @SoloValidar = 0.

   QUÉ HACE
   1. Trae de SERVER_22 a tablas temporales locales (#Stg_*) los datos
      de la tienda/mes. Esto va FUERA de la transacción, para no
      necesitar transacciones distribuidas (MSDTC).
   2. Dentro de una transacción local:
      - Deshabilita triggers de las 4 tablas.
      - Borra en TEST lo que ya exista de esa tienda/mes (y los mismos
        IdEncabezado que vienen de prod) -> se puede re-ejecutar.
      - Inserta conservando los Id identity (IDENTITY_INSERT).
      - Rehabilita triggers.
   ===================================================================== */

USE VENTAWEB_NEW;
GO

SET NOCOUNT ON;
SET XACT_ABORT ON;

------------------------------------------------------------------------
-- PARÁMETROS
------------------------------------------------------------------------
DECLARE @IdTienda    int      = 0;            -- <<< tienda
DECLARE @FechaIni    datetime = '20261001';   -- <<< primer día del mes (yyyymmdd)
DECLARE @FechaFin    datetime = DATEADD(MONTH, 1, @FechaIni); -- exclusivo
DECLARE @SoloValidar bit      = 1;            -- 1 = simula y hace ROLLBACK

------------------------------------------------------------------------
-- CANDADO: este script NO debe correr en el propio SERVER_22 (PROD),
-- porque ahí "VENTAWEB_NEW" local sería producción.
------------------------------------------------------------------------
DECLARE @ServidorRemoto sysname;
SELECT @ServidorRemoto = s FROM OPENQUERY(SERVER_22, 'SELECT CAST(@@SERVERNAME AS sysname) AS s');

IF @ServidorRemoto = @@SERVERNAME
    THROW 50000, 'Estás conectado a PRODUCCIÓN (SERVER_22). Conéctate al servidor de TEST.', 1;

PRINT CONCAT('Origen: ', @ServidorRemoto, '  ->  Destino: ', @@SERVERNAME, '.', DB_NAME());

------------------------------------------------------------------------
-- 1. STAGING: traer datos de PROD a temporales locales
------------------------------------------------------------------------
IF OBJECT_ID('tempdb..#Stg_DatEncabezado')   IS NOT NULL DROP TABLE #Stg_DatEncabezado;
IF OBJECT_ID('tempdb..#Stg_DatDetalle')      IS NOT NULL DROP TABLE #Stg_DatDetalle;
IF OBJECT_ID('tempdb..#Stg_DatTipoPago')     IS NOT NULL DROP TABLE #Stg_DatTipoPago;
IF OBJECT_ID('tempdb..#Stg_DatCortesTienda') IS NOT NULL DROP TABLE #Stg_DatCortesTienda;
IF OBJECT_ID('tempdb..#EncBorrar')           IS NOT NULL DROP TABLE #EncBorrar;

SELECT e.*
INTO #Stg_DatEncabezado
FROM SERVER_22.VENTAWEB_NEW.dbo.DatEncabezado e
WHERE e.IdTienda = @IdTienda
  AND e.FechaVenta >= @FechaIni AND e.FechaVenta < @FechaFin;

SELECT d.*
INTO #Stg_DatDetalle
FROM SERVER_22.VENTAWEB_NEW.dbo.DatDetalle d
WHERE d.IdEncabezado IN (SELECT e.IdEncabezado
                         FROM SERVER_22.VENTAWEB_NEW.dbo.DatEncabezado e
                         WHERE e.IdTienda = @IdTienda
                           AND e.FechaVenta >= @FechaIni AND e.FechaVenta < @FechaFin);

SELECT p.*
INTO #Stg_DatTipoPago
FROM SERVER_22.VENTAWEB_NEW.dbo.DatTipoPago p
WHERE p.IdEncabezado IN (SELECT e.IdEncabezado
                         FROM SERVER_22.VENTAWEB_NEW.dbo.DatEncabezado e
                         WHERE e.IdTienda = @IdTienda
                           AND e.FechaVenta >= @FechaIni AND e.FechaVenta < @FechaFin);

SELECT c.*
INTO #Stg_DatCortesTienda
FROM SERVER_22.VENTAWEB_NEW.dbo.DatCortesTienda c
WHERE c.IdEncabezado IN (SELECT e.IdEncabezado
                         FROM SERVER_22.VENTAWEB_NEW.dbo.DatEncabezado e
                         WHERE e.IdTienda = @IdTienda
                           AND e.FechaVenta >= @FechaIni AND e.FechaVenta < @FechaFin);

SELECT 'Traído de PROD' AS Paso,
       (SELECT COUNT(*) FROM #Stg_DatEncabezado)   AS DatEncabezado,
       (SELECT COUNT(*) FROM #Stg_DatDetalle)      AS DatDetalle,
       (SELECT COUNT(*) FROM #Stg_DatTipoPago)     AS DatTipoPago,
       (SELECT COUNT(*) FROM #Stg_DatCortesTienda) AS DatCortesTienda;

IF NOT EXISTS (SELECT 1 FROM #Stg_DatEncabezado)
    THROW 50002, 'No hay ventas en PROD para esa tienda/mes. No se hizo nada.', 1;

------------------------------------------------------------------------
-- Validar que las cajas (IdDatCaja) de esas ventas existan en TEST
-- y sean de la misma tienda
------------------------------------------------------------------------
IF EXISTS (SELECT 1 FROM #Stg_DatEncabezado e
           LEFT JOIN dbo.DatCajas c ON c.IdDatCajas = e.IdDatCaja
           WHERE c.IdDatCajas IS NULL OR c.IdTienda <> e.IdTienda)
BEGIN
    SELECT e.IdDatCaja,
           e.IdTienda          AS TiendaDeLaVenta,
           c.IdTienda          AS TiendaDeLaCajaEnTEST,
           p.IdTienda          AS TiendaDeLaCajaEnPROD,
           COUNT(*)            AS Tickets,
           CASE WHEN c.IdDatCajas IS NULL AND p.IdDatCajas IS NULL THEN 'No existe ni en TEST ni en PROD'
                WHEN c.IdDatCajas IS NULL                          THEN 'Falta en TEST (correr copiar_datcajas con @SoloValidar = 0)'
                ELSE 'En TEST es de otra tienda (correr igualar_datcajas con @SoloValidar = 0)'
           END AS Problema
    FROM #Stg_DatEncabezado e
    LEFT JOIN dbo.DatCajas c ON c.IdDatCajas = e.IdDatCaja
    LEFT JOIN SERVER_22.VENTAWEB_NEW.dbo.DatCajas p ON p.IdDatCajas = e.IdDatCaja
    WHERE c.IdDatCajas IS NULL OR c.IdTienda <> e.IdTienda
    GROUP BY e.IdDatCaja, e.IdTienda, c.IdTienda, p.IdTienda, c.IdDatCajas, p.IdDatCajas;

    THROW 50004, 'Hay ventas con cajas que no existen en TEST o son de otra tienda (ver la lista). No se hizo nada.', 1;
END

------------------------------------------------------------------------
-- Validar que los artículos (IdArticulo) de DatDetalle existan en TEST
------------------------------------------------------------------------
IF EXISTS (SELECT 1 FROM #Stg_DatDetalle d
           WHERE NOT EXISTS (SELECT 1 FROM dbo.CatArticulos a WHERE a.IdArticulo = d.IdArticulo))
BEGIN
    SELECT d.IdArticulo,
           COUNT(*) AS RenglonesDeDetalle,
           CASE WHEN EXISTS (SELECT 1 FROM SERVER_22.VENTAWEB_NEW.dbo.CatArticulos p WHERE p.IdArticulo = d.IdArticulo)
                THEN 'Existe en PROD (correr copiar_faltantes_prod_a_test con @SoloValidar = 0)'
                ELSE 'No existe ni en PROD'
           END AS Problema
    FROM #Stg_DatDetalle d
    WHERE NOT EXISTS (SELECT 1 FROM dbo.CatArticulos a WHERE a.IdArticulo = d.IdArticulo)
    GROUP BY d.IdArticulo
    ORDER BY d.IdArticulo;

    THROW 50006, 'Hay artículos en DatDetalle que no existen en TEST (ver la lista). No se hizo nada.', 1;
END

------------------------------------------------------------------------
-- Validar que los descuentos (IdEncDescuento) de DatDetalle existan en TEST
------------------------------------------------------------------------
IF EXISTS (SELECT 1 FROM #Stg_DatDetalle d
           WHERE d.IdEncDescuento IS NOT NULL
             AND NOT EXISTS (SELECT 1 FROM dbo.DatEncDescuentos x WHERE x.IdEncDescuento = d.IdEncDescuento))
BEGIN
    SELECT d.IdEncDescuento,
           COUNT(*) AS RenglonesDeDetalle,
           CASE WHEN EXISTS (SELECT 1 FROM SERVER_22.VENTAWEB_NEW.dbo.DatEncDescuentos p WHERE p.IdEncDescuento = d.IdEncDescuento)
                THEN 'Existe en PROD (correr copiar_faltantes_prod_a_test con @SoloValidar = 0)'
                ELSE 'No existe ni en PROD'
           END AS Problema
    FROM #Stg_DatDetalle d
    WHERE d.IdEncDescuento IS NOT NULL
      AND NOT EXISTS (SELECT 1 FROM dbo.DatEncDescuentos x WHERE x.IdEncDescuento = d.IdEncDescuento)
    GROUP BY d.IdEncDescuento
    ORDER BY d.IdEncDescuento;

    THROW 50007, 'Hay descuentos en DatDetalle que no existen en TEST (ver la lista). No se hizo nada.', 1;
END

------------------------------------------------------------------------
-- IdEncabezado a limpiar en TEST: los de la tienda/mes en test
-- + los que vienen de prod
------------------------------------------------------------------------
SELECT TOP 0 IdEncabezado INTO #EncBorrar FROM dbo.DatEncabezado;

INSERT #EncBorrar (IdEncabezado)
SELECT IdEncabezado FROM dbo.DatEncabezado
WHERE IdTienda = @IdTienda AND FechaVenta >= @FechaIni AND FechaVenta < @FechaFin
UNION
SELECT IdEncabezado FROM #Stg_DatEncabezado;

------------------------------------------------------------------------
-- Tablas a procesar (Orden = orden de INSERT; el DELETE va al revés)
------------------------------------------------------------------------
DECLARE @Tablas TABLE (Orden int, Tabla sysname);
INSERT @Tablas VALUES (1, 'DatEncabezado'), (2, 'DatDetalle'),
                      (3, 'DatTipoPago'),   (4, 'DatCortesTienda');

DECLARE @Resumen TABLE (Tabla sysname, BorradasTest int, InsertadasDesdeProd int);

DECLARE @sql nvarchar(max), @Tabla sysname, @Stg sysname, @Cols nvarchar(max),
        @ColId sysname, @Choques int, @n int, @msg nvarchar(400);

BEGIN TRY
    BEGIN TRAN;

    --------------------------------------------------------------------
    -- 2. Deshabilitar triggers en TEST
    --------------------------------------------------------------------
    DISABLE TRIGGER ALL ON dbo.DatEncabezado;
    DISABLE TRIGGER ALL ON dbo.DatDetalle;
    DISABLE TRIGGER ALL ON dbo.DatTipoPago;
    DISABLE TRIGGER ALL ON dbo.DatCortesTienda;

    --------------------------------------------------------------------
    -- 3. DELETE en TEST (hijos primero)
    --------------------------------------------------------------------
    DELETE FROM dbo.DatCortesTienda WHERE IdEncabezado IN (SELECT IdEncabezado FROM #EncBorrar);
    INSERT @Resumen VALUES ('DatCortesTienda', @@ROWCOUNT, 0);

    DELETE FROM dbo.DatTipoPago     WHERE IdEncabezado IN (SELECT IdEncabezado FROM #EncBorrar);
    INSERT @Resumen VALUES ('DatTipoPago', @@ROWCOUNT, 0);

    DELETE FROM dbo.DatDetalle      WHERE IdEncabezado IN (SELECT IdEncabezado FROM #EncBorrar);
    INSERT @Resumen VALUES ('DatDetalle', @@ROWCOUNT, 0);

    DELETE FROM dbo.DatEncabezado   WHERE IdEncabezado IN (SELECT IdEncabezado FROM #EncBorrar);
    INSERT @Resumen VALUES ('DatEncabezado', @@ROWCOUNT, 0);

    --------------------------------------------------------------------
    -- 4. INSERT desde staging (padres primero)
    --------------------------------------------------------------------
    DECLARE cur CURSOR LOCAL FAST_FORWARD FOR
        SELECT Tabla FROM @Tablas ORDER BY Orden;
    OPEN cur; FETCH NEXT FROM cur INTO @Tabla;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        SET @Stg = N'#Stg_' + @Tabla;

        -- Columnas en común entre TEST y lo traído de PROD
        -- (sin calculadas ni rowversion en destino)
        -- (FOR XML PATH en lugar de STRING_AGG, compatible con SQL Server 2012/2014)
        SET @Cols = STUFF((SELECT N', ' + QUOTENAME(c.name)
                           FROM sys.columns c
                           WHERE c.object_id = OBJECT_ID(N'dbo.' + @Tabla)
                             AND c.is_computed = 0
                             AND TYPE_NAME(c.system_type_id) <> 'timestamp'
                             AND EXISTS (SELECT 1 FROM tempdb.sys.columns s
                                         WHERE s.object_id = OBJECT_ID(N'tempdb..' + @Stg)
                                           AND s.name = c.name)
                           ORDER BY c.column_id
                           FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');

        -- Columna identity del destino (si tiene)
        SET @ColId = NULL;
        SELECT @ColId = c.name
        FROM sys.columns c
        WHERE c.object_id = OBJECT_ID(N'dbo.' + @Tabla) AND c.is_identity = 1;

        -- ¿Algún Id identity de PROD ya está ocupado en TEST por otro registro?
        IF @ColId IS NOT NULL
        BEGIN
            SET @sql = N'SELECT @Choques = COUNT(*) FROM ' + @Stg + N' s '
                     + N'WHERE EXISTS (SELECT 1 FROM dbo.' + QUOTENAME(@Tabla) + N' t '
                     + N'WHERE t.' + QUOTENAME(@ColId) + N' = s.' + QUOTENAME(@ColId) + N')';
            EXEC sp_executesql @sql, N'@Choques int OUTPUT', @Choques OUTPUT;

            IF @Choques > 0
            BEGIN
                SET @msg = CONCAT(N'Abortado: ', @Choques, N' registros de ', @Tabla, N' tienen un ',
                                  @ColId, N' que ya existe en TEST con otro registro. Revisar antes de continuar.');
                THROW 50001, @msg, 1;
            END
        END

        SET @sql = CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' ON; ' ELSE N'' END
                 + N'INSERT INTO dbo.' + QUOTENAME(@Tabla) + N' (' + @Cols + N') '
                 + N'SELECT ' + @Cols + N' FROM ' + @Stg + N'; '
                 + N'SET @n = @@ROWCOUNT; '
                 + CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' OFF;' ELSE N'' END;

        EXEC sp_executesql @sql, N'@n int OUTPUT', @n OUTPUT;

        UPDATE @Resumen SET InsertadasDesdeProd = @n WHERE Tabla = @Tabla;

        FETCH NEXT FROM cur INTO @Tabla;
    END
    CLOSE cur; DEALLOCATE cur;

    --------------------------------------------------------------------
    -- 5. Rehabilitar triggers
    --------------------------------------------------------------------
    ENABLE TRIGGER ALL ON dbo.DatEncabezado;
    ENABLE TRIGGER ALL ON dbo.DatDetalle;
    ENABLE TRIGGER ALL ON dbo.DatTipoPago;
    ENABLE TRIGGER ALL ON dbo.DatCortesTienda;

    SELECT * FROM @Resumen ORDER BY Tabla;

    IF @SoloValidar = 1
    BEGIN
        ROLLBACK;
        PRINT 'SIMULACIÓN: se hizo ROLLBACK, no se guardó nada. Pon @SoloValidar = 0 para aplicar.';
    END
    ELSE
    BEGIN
        COMMIT;
        PRINT 'Listo: datos copiados a TEST.';
    END
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK;   -- también revierte el DISABLE TRIGGER
    THROW;
END CATCH;
