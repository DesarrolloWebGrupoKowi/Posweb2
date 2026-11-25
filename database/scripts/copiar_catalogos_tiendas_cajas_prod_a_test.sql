/* =====================================================================
   Sincroniza catálogos de tiendas y cajas
       ORIGEN  (PROD): SERVER_22.VENTAWEB_NEW   (linked server)
       DESTINO (TEST): VENTAWEB_NEW             (BD local)

   Tablas (en este orden):
       CatTiendas  (llave IdTienda)
       CatCajas    (llave IdCaja)
       DatCajas    (llave IdDatCajas)  -> liga tienda + caja; la usa
                                          DatEncabezado.IdDatCaja

   QUÉ HACE (por tabla, tipo "upsert"):
   - Si el registro existe en TEST con la misma llave y cambió -> lo ACTUALIZA
     con lo de PROD.
   - Si no existe en TEST -> lo INSERTA conservando el mismo Id.
   - Lo que sólo exista en TEST NO se borra.
   - Triggers de estas tablas apagados mientras corre.
   - Todo en una transacción; @SoloValidar = 1 hace ROLLBACK.

   Correr ANTES de copiar_mes_prod_a_test.sql
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

PRINT CONCAT('Origen: ', @ServidorRemoto, '  ->  Destino: ', @@SERVERNAME, '.', DB_NAME());

------------------------------------------------------------------------
-- 1. STAGING: traer catálogos completos de PROD (fuera de la transacción)
------------------------------------------------------------------------
IF OBJECT_ID('tempdb..#Stg_CatTiendas') IS NOT NULL DROP TABLE #Stg_CatTiendas;
IF OBJECT_ID('tempdb..#Stg_CatCajas')   IS NOT NULL DROP TABLE #Stg_CatCajas;
IF OBJECT_ID('tempdb..#Stg_DatCajas')   IS NOT NULL DROP TABLE #Stg_DatCajas;
IF OBJECT_ID('tempdb..#Acciones')       IS NOT NULL DROP TABLE #Acciones;
IF OBJECT_ID('tempdb..#c')              IS NOT NULL DROP TABLE #c;

SELECT * INTO #Stg_CatTiendas FROM SERVER_22.VENTAWEB_NEW.dbo.CatTiendas;
SELECT * INTO #Stg_CatCajas   FROM SERVER_22.VENTAWEB_NEW.dbo.CatCajas;
SELECT * INTO #Stg_DatCajas   FROM SERVER_22.VENTAWEB_NEW.dbo.DatCajas;

SELECT 'Traído de PROD' AS Paso,
       (SELECT COUNT(*) FROM #Stg_CatTiendas) AS CatTiendas,
       (SELECT COUNT(*) FROM #Stg_CatCajas)   AS CatCajas,
       (SELECT COUNT(*) FROM #Stg_DatCajas)   AS DatCajas;

------------------------------------------------------------------------
-- Tablas y su llave
------------------------------------------------------------------------
DECLARE @Tablas TABLE (Orden int, Tabla sysname, Llave sysname);
INSERT @Tablas VALUES (1, 'CatTiendas', 'IdTienda'),
                      (2, 'CatCajas',   'IdCaja'),
                      (3, 'DatCajas',   'IdDatCajas');

DECLARE @Resumen TABLE (Tabla sysname, Insertadas int, Actualizadas int, SoloEnTest int);
CREATE TABLE #Acciones (Accion nvarchar(10));

DECLARE @sql nvarchar(max), @Tabla sysname, @Llave sysname, @Stg sysname,
        @Cols nvarchar(max), @ColsS nvarchar(max), @ColsT nvarchar(max),
        @Set nvarchar(max), @ColId sysname, @SoloTest int;

BEGIN TRY
    BEGIN TRAN;

    DISABLE TRIGGER ALL ON dbo.CatTiendas;
    DISABLE TRIGGER ALL ON dbo.CatCajas;
    DISABLE TRIGGER ALL ON dbo.DatCajas;

    DECLARE cur CURSOR LOCAL FAST_FORWARD FOR
        SELECT Tabla, Llave FROM @Tablas ORDER BY Orden;
    OPEN cur; FETCH NEXT FROM cur INTO @Tabla, @Llave;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        SET @Stg = N'#Stg_' + @Tabla;
        SELECT @Cols = NULL, @ColsS = NULL, @ColsT = NULL, @Set = NULL, @ColId = NULL;

        -- Columnas en común TEST/PROD (sin calculadas ni rowversion)
        SELECT c.column_id, c.name, c.is_identity
        INTO #c
        FROM sys.columns c
        WHERE c.object_id = OBJECT_ID(N'dbo.' + @Tabla)
          AND c.is_computed = 0
          AND TYPE_NAME(c.system_type_id) <> 'timestamp'
          AND EXISTS (SELECT 1 FROM tempdb.sys.columns s
                      WHERE s.object_id = OBJECT_ID(N'tempdb..' + @Stg) AND s.name = c.name);

        -- (FOR XML PATH en lugar de STRING_AGG, compatible con SQL Server 2012/2014)
        SET @Cols  = STUFF((SELECT N', ' + QUOTENAME(name)
                            FROM #c ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
        SET @ColsS = STUFF((SELECT N', s.' + QUOTENAME(name)
                            FROM #c ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
        SET @ColsT = STUFF((SELECT N', t.' + QUOTENAME(name)
                            FROM #c ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');

        -- SET del UPDATE: todas menos la llave y la identity
        SET @Set   = STUFF((SELECT N', t.' + QUOTENAME(name) + N' = s.' + QUOTENAME(name)
                            FROM #c WHERE name <> @Llave AND is_identity = 0 ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');

        SELECT @ColId = name FROM #c WHERE is_identity = 1;
        DROP TABLE #c;

        -- Registros que sólo existen en TEST (informativo, no se tocan)
        SET @sql = N'SELECT @x = COUNT(*) FROM dbo.' + QUOTENAME(@Tabla) + N' t WHERE NOT EXISTS '
                 + N'(SELECT 1 FROM ' + @Stg + N' s WHERE s.' + QUOTENAME(@Llave) + N' = t.' + QUOTENAME(@Llave) + N')';
        EXEC sp_executesql @sql, N'@x int OUTPUT', @SoloTest OUTPUT;

        TRUNCATE TABLE #Acciones;

        SET @sql =
              CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' ON; ' ELSE N'' END
            + N'MERGE dbo.' + QUOTENAME(@Tabla) + N' AS t '
            + N'USING ' + @Stg + N' AS s ON t.' + QUOTENAME(@Llave) + N' = s.' + QUOTENAME(@Llave) + N' '
            + CASE WHEN @Set IS NOT NULL
                   THEN N'WHEN MATCHED AND EXISTS (SELECT ' + @ColsS + N' EXCEPT SELECT ' + @ColsT + N') '
                      + N'THEN UPDATE SET ' + @Set + N' '
                   ELSE N'' END
            + N'WHEN NOT MATCHED BY TARGET THEN INSERT (' + @Cols + N') VALUES (' + @ColsS + N') '
            + N'OUTPUT $action INTO #Acciones (Accion); '
            + CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' OFF;' ELSE N'' END;

        EXEC sp_executesql @sql;

        INSERT @Resumen
        SELECT @Tabla,
               SUM(CASE WHEN Accion = 'INSERT' THEN 1 ELSE 0 END),
               SUM(CASE WHEN Accion = 'UPDATE' THEN 1 ELSE 0 END),
               @SoloTest
        FROM #Acciones;

        FETCH NEXT FROM cur INTO @Tabla, @Llave;
    END
    CLOSE cur; DEALLOCATE cur;

    ENABLE TRIGGER ALL ON dbo.CatTiendas;
    ENABLE TRIGGER ALL ON dbo.CatCajas;
    ENABLE TRIGGER ALL ON dbo.DatCajas;

    SELECT Tabla, ISNULL(Insertadas, 0) AS Insertadas, ISNULL(Actualizadas, 0) AS Actualizadas,
           SoloEnTest AS SoloEnTest_NoSeTocan
    FROM @Resumen;

    IF @SoloValidar = 1
    BEGIN
        ROLLBACK;
        PRINT 'SIMULACIÓN: se hizo ROLLBACK, no se guardó nada. Pon @SoloValidar = 0 para aplicar.';
    END
    ELSE
    BEGIN
        COMMIT;
        PRINT 'Listo: catálogos sincronizados en TEST.';
    END
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK;
    THROW;
END CATCH;
