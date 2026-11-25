/* =====================================================================
   Iguala en TEST las DatCajas que YA EXISTEN con los valores de PROD
       ORIGEN  (PROD): SERVER_22.VENTAWEB_NEW.dbo.DatCajas
       DESTINO (TEST): VENTAWEB_NEW.dbo.DatCajas

   Para qué:
   - Actualiza los consecutivos (IdEncabezado, IdTicket, FolioMerma, ...)
     para que los tickets nuevos en TEST no choquen con los copiados de PROD.
   - Corrige los IdDatCajas que en TEST son otra tienda/caja
     (ej. Id 9: TEST = tienda 19 caja 2, PROD = tienda 17 caja 1).

   - Sólo ACTUALIZA registros con el mismo IdDatCajas en ambas BD y que
     tengan alguna diferencia. No inserta ni borra.
     (Las faltantes se insertan con copiar_datcajas_prod_a_test.sql)
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
-- 1. Traer DatCajas de PROD
------------------------------------------------------------------------
IF OBJECT_ID('tempdb..#Stg_DatCajas') IS NOT NULL DROP TABLE #Stg_DatCajas;

SELECT * INTO #Stg_DatCajas FROM SERVER_22.VENTAWEB_NEW.dbo.DatCajas;

------------------------------------------------------------------------
-- 2. Cajas que cambian de tienda/caja + ventas de TEST que apuntan a ellas
------------------------------------------------------------------------
SELECT t.IdDatCajas,
       t.IdTienda AS Tienda_TEST, t.IdCaja AS Caja_TEST,
       s.IdTienda AS Tienda_PROD, s.IdCaja AS Caja_PROD,
       (SELECT COUNT(*) FROM dbo.DatEncabezado e WHERE e.IdDatCaja = t.IdDatCajas) AS VentasEnTestConEsteId
FROM dbo.DatCajas t
JOIN #Stg_DatCajas s ON s.IdDatCajas = t.IdDatCajas
WHERE t.IdTienda <> s.IdTienda OR t.IdCaja <> s.IdCaja;

-- La tienda/caja nueva debe existir en TEST (por las FK)
IF EXISTS (SELECT 1 FROM #Stg_DatCajas s
           JOIN dbo.DatCajas t ON t.IdDatCajas = s.IdDatCajas
           WHERE NOT EXISTS (SELECT 1 FROM dbo.CatTiendas ct WHERE ct.IdTienda = s.IdTienda)
              OR NOT EXISTS (SELECT 1 FROM dbo.CatCajas   cc WHERE cc.IdCaja   = s.IdCaja))
    THROW 50003, 'Falta alguna tienda o caja en TEST. Corre primero copiar_datcajas_prod_a_test.sql con @SoloValidar = 0.', 1;

------------------------------------------------------------------------
-- 3. Armar el UPDATE con las columnas en común (sin llave ni identity)
------------------------------------------------------------------------
IF OBJECT_ID('tempdb..#c') IS NOT NULL DROP TABLE #c;

SELECT c.column_id, c.name
INTO #c
FROM sys.columns c
WHERE c.object_id = OBJECT_ID(N'dbo.DatCajas')
  AND c.is_computed = 0
  AND c.is_identity = 0
  AND c.name <> 'IdDatCajas'
  AND TYPE_NAME(c.system_type_id) <> 'timestamp'
  AND EXISTS (SELECT 1 FROM tempdb.sys.columns s
              WHERE s.object_id = OBJECT_ID(N'tempdb..#Stg_DatCajas') AND s.name = c.name);

DECLARE @Set nvarchar(max), @ColsS nvarchar(max), @ColsT nvarchar(max), @sql nvarchar(max), @n int;

SET @Set   = STUFF((SELECT N', t.' + QUOTENAME(name) + N' = s.' + QUOTENAME(name)
                    FROM #c ORDER BY column_id FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
SET @ColsS = STUFF((SELECT N', s.' + QUOTENAME(name)
                    FROM #c ORDER BY column_id FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');
SET @ColsT = STUFF((SELECT N', t.' + QUOTENAME(name)
                    FROM #c ORDER BY column_id FOR XML PATH(''), TYPE).value('.', 'nvarchar(max)'), 1, 2, N'');

-- Qué cambia (antes)
SET @sql = N'SELECT ''ANTES (TEST)'' AS Version, t.* FROM dbo.DatCajas t JOIN #Stg_DatCajas s ON s.IdDatCajas = t.IdDatCajas '
         + N'WHERE EXISTS (SELECT ' + @ColsS + N' EXCEPT SELECT ' + @ColsT + N') ORDER BY t.IdDatCajas;';
EXEC sp_executesql @sql;

SET @sql = N'UPDATE t SET ' + @Set + N' '
         + N'FROM dbo.DatCajas t JOIN #Stg_DatCajas s ON s.IdDatCajas = t.IdDatCajas '
         + N'WHERE EXISTS (SELECT ' + @ColsS + N' EXCEPT SELECT ' + @ColsT + N'); '
         + N'SET @n = @@ROWCOUNT;';

------------------------------------------------------------------------
-- 4. Aplicar
------------------------------------------------------------------------
BEGIN TRY
    BEGIN TRAN;

    DISABLE TRIGGER ALL ON dbo.DatCajas;

    EXEC sp_executesql @sql, N'@n int OUTPUT', @n OUTPUT;

    ENABLE TRIGGER ALL ON dbo.DatCajas;

    SELECT @n AS DatCajas_Actualizadas;
    SELECT 'DESPUÉS (TEST)' AS Version, * FROM dbo.DatCajas ORDER BY IdDatCajas;

    IF @SoloValidar = 1
    BEGIN
        ROLLBACK;
        PRINT 'SIMULACIÓN: se hizo ROLLBACK, no se guardó nada. Pon @SoloValidar = 0 para aplicar.';
    END
    ELSE
    BEGIN
        COMMIT;
        PRINT 'Listo: DatCajas de TEST igualadas con PROD.';
    END
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK;
    THROW;
END CATCH;
