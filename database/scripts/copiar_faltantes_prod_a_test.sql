/* =====================================================================
   Copia de PROD a TEST las tablas que pongas en la lista
       ORIGEN  (PROD): SERVER_22.VENTAWEB_NEW
       DESTINO (TEST): VENTAWEB_NEW

   Por cada tabla:
   - INSERTA los registros cuya llave no existe en TEST (con el mismo Id).
   - Si Actualizar = 1: además ACTUALIZA con lo de PROD los registros que
     tienen la misma llave pero datos distintos.
     Si Actualizar = 0: esos sólo se informan, no se tocan.
   - NUNCA borra lo que sólo existe en TEST.
   - La llave se detecta sola (primary key de una columna). Si la tabla no
     tiene PK o es compuesta, escribe la columna en "Llave".
   - Tablas en orden: padres primero.
   - @SoloValidar = 1 -> simula y hace ROLLBACK.
   ===================================================================== */

USE VENTAWEB_NEW;
GO

-- Limpia temporales que hayan quedado de corridas anteriores en esta ventana
-- (si no, SQL Server compila contra la estructura vieja y marca error)
IF OBJECT_ID('tempdb..#c')         IS NOT NULL DROP TABLE #c;
IF OBJECT_ID('tempdb..#ColsTabla') IS NOT NULL DROP TABLE #ColsTabla;
GO

SET NOCOUNT ON;
SET XACT_ABORT ON;

DECLARE @SoloValidar bit = 1;   -- 1 = simula y hace ROLLBACK

------------------------------------------------------------------------
-- TABLAS A COPIAR (en orden: padres primero)
--   Llave      NULL = detectar sola
--   Actualizar 1 = igualar con PROD los que ya existen / 0 = sólo insertar faltantes
------------------------------------------------------------------------
DECLARE @Tablas TABLE (Orden int IDENTITY(1,1), Tabla sysname, Llave sysname NULL, Actualizar bit);
INSERT @Tablas (Tabla, Llave, Actualizar) VALUES
    -- Menús -----------------------------------------------------------
    ('CatTipoMenu',        NULL, 1),   -- grupos de menú
    ('CatMenus',           NULL, 1),   -- pantallas
    ('CatTipoUsuarios',    NULL, 0),   -- tipos de usuario (sólo faltantes)
    ('DatMenuTipoUsuario', NULL, 1);   -- qué menús ve cada tipo de usuario

    -- Catálogos que piden las ventas (ya corridos; descomenta si hace falta)
    -- ('CatArticulos',     NULL, 0),
    -- ('CatTipoDescuento', NULL, 0),
    -- ('CatPlazas',        NULL, 0),
    -- ('DatEncDescuentos', NULL, 0),
    -- ('DatDetDescuentos', NULL, 0);

------------------------------------------------------------------------
-- CANDADO: no correr en el propio SERVER_22 (PROD)
------------------------------------------------------------------------
DECLARE @ServidorRemoto sysname;
SELECT @ServidorRemoto = s FROM OPENQUERY(SERVER_22, 'SELECT CAST(@@SERVERNAME AS sysname) AS s');

IF @ServidorRemoto = @@SERVERNAME
    THROW 50000, 'Estás conectado a PRODUCCIÓN (SERVER_22). Conéctate al servidor de TEST.', 1;

------------------------------------------------------------------------
-- Variables
------------------------------------------------------------------------
DECLARE @Orden int, @Tabla sysname, @Llave sysname, @Stg sysname, @Actualizar bit,
        @Cols nvarchar(max), @ColsS nvarchar(max), @ColsT nvarchar(max), @Set nvarchar(max),
        @Join nvarchar(max), @Match nvarchar(max), @Distinto nvarchar(max),
        @ColId sysname, @sql nvarchar(max), @n int, @u int, @Faltan int, @Distintos int,
        @msg nvarchar(2000);

DECLARE @Resumen TABLE (Orden int, Tabla sysname, Llave sysname, Stg sysname, Actualizar bit,
                        FaltanEnTest int, MismaLlaveDatosDistintos int,
                        Insertadas int, Actualizadas int);

------------------------------------------------------------------------
-- 1. Detectar llave de cada tabla
------------------------------------------------------------------------
UPDATE t SET Llave = (SELECT MAX(c.name)
                      FROM sys.indexes i
                      JOIN sys.index_columns ic ON ic.object_id = i.object_id AND ic.index_id = i.index_id
                      JOIN sys.columns c        ON c.object_id = ic.object_id AND c.column_id = ic.column_id
                      WHERE i.object_id = OBJECT_ID(N'dbo.' + t.Tabla) AND i.is_primary_key = 1
                      HAVING COUNT(*) = 1)
FROM @Tablas t
WHERE t.Llave IS NULL;

IF EXISTS (SELECT 1 FROM @Tablas WHERE Llave IS NULL)
BEGIN
    SELECT Tabla AS SinLlaveDetectada FROM @Tablas WHERE Llave IS NULL;
    THROW 50005, 'No se pudo detectar la llave de alguna tabla (ver lista). Escríbela en la columna Llave.', 1;
END

------------------------------------------------------------------------
-- 2. STAGING (fuera de la transacción): traer de PROD
--    Tablas ##temporales con el número de sesión para que sigan vivas
--    entre los EXEC dinámicos.
------------------------------------------------------------------------
DECLARE cur CURSOR LOCAL FAST_FORWARD FOR SELECT Orden, Tabla, Llave, Actualizar FROM @Tablas ORDER BY Orden;
OPEN cur; FETCH NEXT FROM cur INTO @Orden, @Tabla, @Llave, @Actualizar;
WHILE @@FETCH_STATUS = 0
BEGIN
    SET @Stg = N'##Stg_' + @Tabla + N'_' + CAST(@@SPID AS nvarchar(10));

    SET @sql = N'IF OBJECT_ID(''tempdb..' + @Stg + N''') IS NOT NULL DROP TABLE ' + QUOTENAME(@Stg) + N'; '
             + N'SELECT * INTO ' + QUOTENAME(@Stg) + N' FROM SERVER_22.VENTAWEB_NEW.dbo.' + QUOTENAME(@Tabla) + N';';
    EXEC sp_executesql @sql;

    INSERT @Resumen VALUES (@Orden, @Tabla, @Llave, @Stg, @Actualizar, 0, 0, 0, 0);

    FETCH NEXT FROM cur INTO @Orden, @Tabla, @Llave, @Actualizar;
END
CLOSE cur; DEALLOCATE cur;

------------------------------------------------------------------------
-- 3. Aplicar en TEST (padres primero), conservando los Id
------------------------------------------------------------------------
BEGIN TRY
    BEGIN TRAN;

    DECLARE cur CURSOR LOCAL FAST_FORWARD FOR SELECT Orden, Tabla, Llave, Stg, Actualizar FROM @Resumen ORDER BY Orden;
    OPEN cur; FETCH NEXT FROM cur INTO @Orden, @Tabla, @Llave, @Stg, @Actualizar;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        SELECT @ColId = NULL, @n = 0, @u = 0;

        -- Columnas en común TEST/PROD (sin calculadas ni rowversion)
        IF OBJECT_ID('tempdb..#ColsTabla') IS NOT NULL DROP TABLE #ColsTabla;
        SELECT c.column_id, c.name, c.is_identity,
               CASE WHEN TYPE_NAME(c.system_type_id) IN ('text', 'ntext', 'image') THEN 0 ELSE 1 END AS Comparable
        INTO #ColsTabla
        FROM sys.columns c
        WHERE c.object_id = OBJECT_ID(N'dbo.' + @Tabla)
          AND c.is_computed = 0
          AND TYPE_NAME(c.system_type_id) <> 'timestamp'
          AND EXISTS (SELECT 1 FROM tempdb.sys.columns s
                      WHERE s.object_id = OBJECT_ID(N'tempdb..' + @Stg) AND s.name = c.name);

        SET @Cols  = STUFF((SELECT N', ' + QUOTENAME(name) FROM #ColsTabla ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
        SET @ColsS = STUFF((SELECT N', s.' + QUOTENAME(name) FROM #ColsTabla WHERE Comparable = 1 ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
        SET @ColsT = STUFF((SELECT N', t.' + QUOTENAME(name) FROM #ColsTabla WHERE Comparable = 1 ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
        SET @Set   = STUFF((SELECT N', t.' + QUOTENAME(name) + N' = s.' + QUOTENAME(name)
                            FROM #ColsTabla WHERE name <> @Llave AND is_identity = 0 ORDER BY column_id
                            FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');

        SELECT @ColId = name FROM sys.columns
        WHERE object_id = OBJECT_ID(N'dbo.' + @Tabla) AND is_identity = 1;

        SET @Join     = N' t ON t.' + QUOTENAME(@Llave) + N' = s.' + QUOTENAME(@Llave);
        SET @Match    = N' t WHERE t.' + QUOTENAME(@Llave) + N' = s.' + QUOTENAME(@Llave);
        SET @Distinto = N' EXISTS (SELECT ' + @ColsS + N' EXCEPT SELECT ' + @ColsT + N')';

        -- Conteos
        SET @sql = N'SELECT @f = COUNT(*) FROM ' + QUOTENAME(@Stg) + N' s WHERE NOT EXISTS (SELECT 1 FROM dbo.'
                 + QUOTENAME(@Tabla) + @Match + N'); '
                 + N'SELECT @d = COUNT(*) FROM ' + QUOTENAME(@Stg) + N' s JOIN dbo.' + QUOTENAME(@Tabla) + @Join
                 + N' WHERE' + @Distinto + N';';
        EXEC sp_executesql @sql, N'@f int OUTPUT, @d int OUTPUT', @Faltan OUTPUT, @Distintos OUTPUT;

        -- Apagar triggers
        SET @sql = N'ALTER TABLE dbo.' + QUOTENAME(@Tabla) + N' DISABLE TRIGGER ALL;';
        EXEC sp_executesql @sql;

        -- UPDATE de los que cambiaron (si Actualizar = 1)
        IF @Actualizar = 1 AND @Set IS NOT NULL
        BEGIN
            SET @sql = N'UPDATE t SET ' + @Set + N' FROM dbo.' + QUOTENAME(@Tabla) + N' t JOIN ' + QUOTENAME(@Stg)
                     + N' s ON t.' + QUOTENAME(@Llave) + N' = s.' + QUOTENAME(@Llave)
                     + N' WHERE' + @Distinto + N'; SET @u = @@ROWCOUNT;';
            EXEC sp_executesql @sql, N'@u int OUTPUT', @u OUTPUT;
        END

        -- INSERT de los faltantes
        SET @sql = CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' ON; ' ELSE N'' END
                 + N'INSERT INTO dbo.' + QUOTENAME(@Tabla) + N' (' + @Cols + N') SELECT ' + @Cols + N' FROM ' + QUOTENAME(@Stg)
                 + N' s WHERE NOT EXISTS (SELECT 1 FROM dbo.' + QUOTENAME(@Tabla) + @Match + N'); '
                 + N'SET @n = @@ROWCOUNT; '
                 + CASE WHEN @ColId IS NOT NULL THEN N'SET IDENTITY_INSERT dbo.' + QUOTENAME(@Tabla) + N' OFF;' ELSE N'' END;
        EXEC sp_executesql @sql, N'@n int OUTPUT', @n OUTPUT;

        -- Prender triggers
        SET @sql = N'ALTER TABLE dbo.' + QUOTENAME(@Tabla) + N' ENABLE TRIGGER ALL;';
        EXEC sp_executesql @sql;

        UPDATE @Resumen
        SET FaltanEnTest = @Faltan, MismaLlaveDatosDistintos = @Distintos, Insertadas = @n, Actualizadas = @u
        WHERE Orden = @Orden;

        FETCH NEXT FROM cur INTO @Orden, @Tabla, @Llave, @Stg, @Actualizar;
    END
    CLOSE cur; DEALLOCATE cur;

    SELECT Tabla, Llave, Actualizar, FaltanEnTest, MismaLlaveDatosDistintos, Insertadas, Actualizadas
    FROM @Resumen ORDER BY Orden;

    IF @SoloValidar = 1
    BEGIN
        ROLLBACK;
        PRINT 'SIMULACIÓN: se hizo ROLLBACK, no se guardó nada. Pon @SoloValidar = 0 para aplicar.';
    END
    ELSE
    BEGIN
        COMMIT;
        PRINT 'Listo: tablas copiadas a TEST.';
    END
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK;
    SET @msg = CONCAT(ERROR_MESSAGE(), N' (tabla: ', @Tabla, N')');
    IF CURSOR_STATUS('local', 'cur') >= 0  CLOSE cur;
    IF CURSOR_STATUS('local', 'cur') >= -1 DEALLOCATE cur;
END CATCH;

------------------------------------------------------------------------
-- 4. Limpiar tablas ##temporales
------------------------------------------------------------------------
DECLARE cur CURSOR LOCAL FAST_FORWARD FOR SELECT Stg FROM @Resumen;
OPEN cur; FETCH NEXT FROM cur INTO @Stg;
WHILE @@FETCH_STATUS = 0
BEGIN
    SET @sql = N'IF OBJECT_ID(''tempdb..' + @Stg + N''') IS NOT NULL DROP TABLE ' + QUOTENAME(@Stg) + N';';
    EXEC sp_executesql @sql;
    FETCH NEXT FROM cur INTO @Stg;
END
CLOSE cur; DEALLOCATE cur;

IF @msg IS NOT NULL
    THROW 50009, @msg, 1;
