/* =====================================================================
   Copia de PROD a TEST lo que FALTE de:
       1. CatTiendas  (llave IdTienda)
       2. CatCajas    (llave IdCaja)
       3. DatCajas    (llave IdDatCajas)
       ORIGEN  (PROD): SERVER_22.VENTAWEB_NEW
       DESTINO (TEST): VENTAWEB_NEW

   - Sólo INSERTA los registros cuya llave no existe en TEST, con el mismo Id.
   - NO modifica ni borra lo que ya existe en TEST.
   - Se insertan en ese orden para respetar las llaves foráneas.
   - Triggers de esas tablas apagados mientras corre.
   - @SoloValidar = 1 -> simula y hace ROLLBACK.
   ===================================================================== */

USE VENTAWEB_NEW;
GO

SET NOCOUNT ON;
SET XACT_ABORT ON;

DECLARE @SoloValidar bit = 1;   -- 1 = simula y hace ROLLBACK

------------------------------------------------------------------------
-- CANDADO: no correr en el propio SERVER_22 (PROD)
------------------------------------------------------------------------
DECLARE @ServidorRemoto sysname;
SELECT @ServidorRemoto = s FROM OPENQUERY(SERVER_22, 'SELECT CAST(@@SERVERNAME AS sysname) AS s');

IF @ServidorRemoto = @@SERVERNAME
    THROW 50000, 'Estás conectado a PRODUCCIÓN (SERVER_22). Conéctate al servidor de TEST.', 1;

------------------------------------------------------------------------
-- 1. Traer de PROD y quedarse sólo con lo que falta en TEST
------------------------------------------------------------------------
IF OBJECT_ID('tempdb..#Stg_CatTiendas') IS NOT NULL DROP TABLE #Stg_CatTiendas;
IF OBJECT_ID('tempdb..#Stg_CatCajas')   IS NOT NULL DROP TABLE #Stg_CatCajas;
IF OBJECT_ID('tempdb..#Stg_DatCajas')   IS NOT NULL DROP TABLE #Stg_DatCajas;

SELECT * INTO #Stg_CatTiendas FROM SERVER_22.VENTAWEB_NEW.dbo.CatTiendas;
SELECT * INTO #Stg_CatCajas   FROM SERVER_22.VENTAWEB_NEW.dbo.CatCajas;
SELECT * INTO #Stg_DatCajas   FROM SERVER_22.VENTAWEB_NEW.dbo.DatCajas;

DELETE s FROM #Stg_CatTiendas s WHERE EXISTS (SELECT 1 FROM dbo.CatTiendas t WHERE t.IdTienda   = s.IdTienda);
DELETE s FROM #Stg_CatCajas   s WHERE EXISTS (SELECT 1 FROM dbo.CatCajas   t WHERE t.IdCaja     = s.IdCaja);
DELETE s FROM #Stg_DatCajas   s WHERE EXISTS (SELECT 1 FROM dbo.DatCajas   t WHERE t.IdDatCajas = s.IdDatCajas);

SELECT 'Faltantes en TEST' AS Paso,
       (SELECT COUNT(*) FROM #Stg_CatTiendas) AS CatTiendas,
       (SELECT COUNT(*) FROM #Stg_CatCajas)   AS CatCajas,
       (SELECT COUNT(*) FROM #Stg_DatCajas)   AS DatCajas;

SELECT 'CatTiendas a insertar' AS Lista, IdTienda AS Id FROM #Stg_CatTiendas
UNION ALL
SELECT 'CatCajas a insertar', IdCaja FROM #Stg_CatCajas
UNION ALL
SELECT 'DatCajas a insertar', IdDatCajas FROM #Stg_DatCajas
ORDER BY 1, 2;

IF NOT EXISTS (SELECT 1 FROM #Stg_CatTiendas)
   AND NOT EXISTS (SELECT 1 FROM #Stg_CatCajas)
   AND NOT EXISTS (SELECT 1 FROM #Stg_DatCajas)
BEGIN
    PRINT 'TEST ya tiene todo. No hay nada que copiar.';
    RETURN;
END

------------------------------------------------------------------------
-- 2. Insertar en orden (padres primero), conservando los Id
------------------------------------------------------------------------
DECLARE @Tablas TABLE (Orden int, Tabla sysname);
INSERT @Tablas VALUES (1, 'CatTiendas'), (2, 'CatCajas'), (3, 'DatCajas');

DECLARE @Resumen TABLE (Tabla sysname, Insertadas int);

DECLARE @Tabla sysname, @Stg sysname, @Cols nvarchar(max), @ColId sysname,
        @sql nvarchar(max), @n int;

BEGIN TRY
    BEGIN TRAN;

    DISABLE TRIGGER ALL ON dbo.CatTiendas;
    DISABLE TRIGGER ALL ON dbo.CatCajas;
    DISABLE TRIGGER ALL ON dbo.DatCajas;

    DECLARE cur CURSOR LOCAL FAST_FORWARD FOR
        SELECT Tabla FROM @Tablas ORDER BY Orden;
    OPEN cur; FETCH NEXT FROM cur INTO @Tabla;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        SET @Stg = N'#Stg_' + @Tabla;
        SET @ColId = NULL;

        -- Columnas en común TEST/PROD (sin calculadas ni rowversion)
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

        SELECT @ColId = name FROM sys.columns
        WHERE object_id = OBJECT_ID(N'dbo.' + @Tabla) AND is_identity = 1;

        SET @sql = CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' ON; ' ELSE N'' END
                 + N'INSERT INTO dbo.' + QUOTENAME(@Tabla) + N' (' + @Cols + N') SELECT ' + @Cols + N' FROM ' + @Stg + N'; '
                 + N'SET @n = @@ROWCOUNT; '
                 + CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' OFF;' ELSE N'' END;

        EXEC sp_executesql @sql, N'@n int OUTPUT', @n OUTPUT;

        INSERT @Resumen VALUES (@Tabla, @n);

        FETCH NEXT FROM cur INTO @Tabla;
    END
    CLOSE cur; DEALLOCATE cur;

    ENABLE TRIGGER ALL ON dbo.CatTiendas;
    ENABLE TRIGGER ALL ON dbo.CatCajas;
    ENABLE TRIGGER ALL ON dbo.DatCajas;

    SELECT * FROM @Resumen;

    IF @SoloValidar = 1
    BEGIN
        ROLLBACK;
        PRINT 'SIMULACIÓN: se hizo ROLLBACK, no se guardó nada. Pon @SoloValidar = 0 para aplicar.';
    END
    ELSE
    BEGIN
        COMMIT;
        PRINT 'Listo: tiendas y cajas copiadas a TEST.';
    END
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK;
    THROW;
END CATCH;
