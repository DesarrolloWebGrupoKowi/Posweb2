<?php

namespace App\Services;

use App\Models\Articulo;
use App\Models\CatPaquete;
use App\Models\DatAsignacionPreparadosLocal;
use App\Models\DatDetalle;
use App\Models\DatDetalleRosticero;
use App\Models\DatEncabezado;
use App\Models\DatMonederoAcumulado;
use App\Models\DatRosticero;
use App\Models\InventarioTienda;
use App\Models\MonederoElectronico;
use App\Models\MovimientoMonederoElectronico;
use App\Models\PreventaTmp;
use App\Models\TemporalPos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaService
{
    public function validandoTipoDePagoMonedero($idTipoPago, $pago)
    {
        if ($idTipoPago == 7) {
            $temporalPos = TemporalPos::first();
            $numNomina = $temporalPos->NumNomina;
            $descuentoMonedero = $temporalPos->MonederoDescuento;
            $pagoMonedero = $pago;

            $monederoE = MonederoElectronico::where('Status', 0)
                ->first();

            $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
                ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                ->sum('MonederoPorGastar') - $descuentoMonedero;

            $importeProcesado = DB::table('DatVentaTmp as a')
                ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
                ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->where('b.IdGrupo', $monederoE->IdGrupo)
                ->sum('a.ImporteArticulo');


            //Validaciones de Monedero Electrónico
            if ($pagoMonedero > $monederoEmpleado) {
                throw ValidationException::withMessages([
                    'Pos' => 'Saldo en Monedero Insuficiente, Saldo Actual: $' . number_format($monederoEmpleado, 2),
                ]);
                // return redirect()->route('Pos')->with('Pos', 'Saldo en Monedero Insuficiente, Saldo Actual: $' . number_format($monederoEmpleado, 2));
            }

            if ($importeProcesado == 0) {
                throw ValidationException::withMessages([
                    'Pos' => 'No Puede Pagar Con Monedero Electrónico Porque No Lleva Producto Procesado!',
                ]);
                // return redirect()->route('Pos')->with('Pos', 'No Puede Pagar Con Monedero Electrónico Porque No Lleva Producto Procesado!');
            }

            if ($pagoMonedero > $importeProcesado) {
                throw ValidationException::withMessages([
                    'Pos' => 'Solo Puede Pagar $' . number_format($importeProcesado, 2) . ' En Monedero Electrónico!',
                ]);
                // return redirect()->route('Pos')->with('Pos', 'Solo Puede Pagar $' . number_format($importeProcesado, 2) . ' En Monedero Electrónico!');
            }

            TemporalPos::where('TemporalPos', 1)
                ->update([
                    'MonederoDescuento' => $pagoMonedero,
                ]);
        }
    }

    public function guardarEncabezado($idTienda, $idDatCajas, $idTicket, $idUsuario, $subTotalVenta, $ivaVenta, $totalVenta, $numNomina)
    {
        DatEncabezado::create([
            'IdEncabezado' => -2,
            'IdTienda' => $idTienda,
            'IdDatCaja' => $idDatCajas,
            'IdTicket' => $idTicket,
            'FechaVenta' => date('d-m-Y H:i:s'), //now
            'IdUsuario' => $idUsuario,
            'SubTotal' => $subTotalVenta,
            'Iva' => $ivaVenta,
            'Promocion' => null,
            'ImporteVenta' => $totalVenta,
            'StatusVenta' => 0,
            'MotivoCancel' => null,
            'FechaCancelacion' => null,
            'FechaCreacion' => null,
            'SolicitudFE' => null,
            'IdMetodoPago' => null,
            'IdUsoCFDI' => null,
            'IdFormaPago' => null,
            'FolioCupon' => null,
            'NumNomina' => $numNomina,
            'Subir' => 1,
        ]);

        $idEncabezado = DatEncabezado::where('IdTienda', $idTienda)
            ->latest('IdDatEncabezado')
            ->value('IdEncabezado');

        return $idEncabezado;
    }

    public function guardarDetalle($idTienda, $idEncabezado)
    {
        $preventa = PreventaTmp::where('IdTienda', $idTienda)
            ->get();

        foreach ($preventa as $index => $detalle) {
            $idRostisado = DatDetalleRosticero::where('IdDatDetalleRosticero', $detalle->CodigoEtiqueta)
                ->where('Status', 0)
                ->where('Vendida', 1)
                ->value('IdRosticero');

            DatDetalle::insert([
                'IdEncabezado' => $idEncabezado,
                'IdArticulo' => $detalle->IdArticulo,
                'CantArticulo' => $detalle->CantArticulo,
                'PrecioLista' => $detalle->PrecioLista,
                'PrecioArticulo' => $detalle->PrecioVenta,
                'IdListaPrecio' => $detalle->IdListaPrecio,
                'CapturaManual' => null,
                'ImporteArticulo' => $detalle->ImporteArticulo,
                'IvaArticulo' => $detalle->IvaArticulo,
                'SubTotalArticulo' => $detalle->SubTotalArticulo,
                'IdPaquete' => $detalle->IdPaquete,
                'IdPedido' => $detalle->IdPedido,
                'IdDatPrecios' => $detalle->IdDatPrecios,
                'Linea' => $index + 1,
                'Recorte' => $detalle->Recorte == '0' ? 0 : 1,
                'IdEncDescuento' => $detalle->IdEncDescuento,
                'IdRosticero' => $idRostisado,
            ]);
        }
    }

    public function ventaRostisados()
    {
        $rostisados = PreventaTmp::select('DatVentaTmp.*')
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', '=', 'DatVentaTmp.IdArticulo')
            ->leftJoin('CatRosticeroArticulos', 'CatRosticeroArticulos.CodigoVenta', '=', 'CatArticulos.CodArticulo')
            ->whereNotNull('CatRosticeroArticulos.IdCatRosticeroArticulos')
            ->get();

        foreach ($rostisados as $rostisado) {
            $detRostisado = DatDetalleRosticero::where('IdDatDetalleRosticero', $rostisado->CodigoEtiqueta)
                ->where('Status', 0)
                ->where('Vendida', 1)
                ->first();

            // Validamos que la etiqueta se encuentre activa
            if (!$detRostisado) {
                return redirect()->route('Pos')->with('Pos', 'Rostisado no disponible para venta.');
            }

            $rotisado = DatRosticero::where('IdRosticero', $detRostisado->IdRosticero)->first();
            $rotisado->update([
                'Disponible' => $rotisado->Disponible - $detRostisado->Cantidad,
                'subir' => 0
            ]);

            // Actualizamos la etiqueta para que no se pueda volver a usar
            $detRostisado->update([
                'subir' => 0,
                'Vendida' => 0,
            ]);
        }
    }

    public function ventaPreparados($idTienda)
    {
        $paquetesPreparados = PreventaTmp::select(
            'DatVentaTmp.IdPaquete',
            DB::raw('COUNT(DatVentaTmp.CantArticulo) as Cantidad')
        )
            ->where('IdTienda', $idTienda)
            ->whereNotNull('DatVentaTmp.IdPaquete')
            ->groupBy('DatVentaTmp.IdPaquete', 'DatVentaTmp.IdArticulo')
            ->distinct()
            ->get();

        foreach ($paquetesPreparados as $pp) {
            $paquetesConPreparado = CatPaquete::where('CatPaquetes.IdPaquete', $pp->IdPaquete)
                ->whereNotNull('CatPaquetes.IdPreparado')
                ->get();

            // Buscamos que los paquetes tengan id de prepadado
            if (count($paquetesConPreparado) != 0) {
                // Obtenemos la cantidad ya vendida
                $cantidadTotal = DatAsignacionPreparadosLocal::where('DatAsignacionPreparados.IdPreparado', $paquetesConPreparado[0]->IdPreparado)
                    ->where('DatAsignacionPreparados.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->value('CantidadEnvio');

                $cantidadVendida = DatAsignacionPreparadosLocal::where('DatAsignacionPreparados.IdPreparado', $paquetesConPreparado[0]->IdPreparado)
                    ->where('DatAsignacionPreparados.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->value('CantidadVendida');

                if (($cantidadTotal - (!$cantidadVendida ? $pp->Cantidad : $cantidadVendida + $pp->Cantidad)) < 0) {
                    return redirect()->route('Pos')->with('Pos', 'Inventario insuficiente para el paquete de preparado!');
                }

                if (($cantidadTotal - (!$cantidadVendida ? $pp->Cantidad : $cantidadVendida + $pp->Cantidad)) == 0) {
                    CatPaquete::where('CatPaquetes.IdPaquete', $pp->IdPaquete)
                        ->whereNotNull('CatPaquetes.IdPreparado')
                        ->update([
                            'Status' => 1,
                        ]);
                }

                // Sumamos la cantidad vendida, mas la nueva cantidad que se esta vendiendo
                DatAsignacionPreparadosLocal::where('DatAsignacionPreparados.IdPreparado', $paquetesConPreparado[0]->IdPreparado)
                    ->where('DatAsignacionPreparados.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->update([
                        'CantidadVendida' => !$cantidadVendida ? $pp->Cantidad : $cantidadVendida + $pp->Cantidad,
                    ]);
            }
        }
    }

    public function descontarInventario($idEncabezado, $idTienda)
    {
        $datDetalle = DatDetalle::where('IdEncabezado', $idEncabezado)
            ->get();

        foreach ($datDetalle as $key => $detalle) {
            $articulo = Articulo::where('IdArticulo', $detalle->IdArticulo)
                ->first();

            $stockArticulo = InventarioTienda::where('IdTienda', $idTienda)
                ->where('CodArticulo', $articulo->CodArticulo)
                ->sum('StockArticulo');

            InventarioTienda::where('IdTienda', $idTienda)
                ->where('CodArticulo', $articulo->CodArticulo)
                ->update([
                    'StockArticulo' => $stockArticulo - $detalle->CantArticulo,
                ]);
        }
    }

    public function descontarMonedero($idEncabezado, $numNomina, $monederoDescuento)
    {
        // return 'fnsdob';
        if (!empty($numNomina) && !empty($monederoDescuento)) {
            $pagoMonedero = $monederoDescuento;

            // generar batchGasto
            $countBatch = DatMonederoAcumulado::max('IdDatMonedero') + 1;

            $numCaja = DB::table('DatCajas as a')
                ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->where('a.Activa', 0)
                ->where('a.Status', 0)
                ->value('NumCaja');

            $batchGasto = Auth::user()->usuarioTienda->IdTienda . $numCaja . $countBatch; // batchGasto

            $monederoE = MonederoElectronico::where('Status', 0)
                ->first();

            $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
            $fechaExpiracion = date('d-m-Y', $fecha);

            // Insertamos el registro para gasto
            DatMonederoAcumulado::insert([
                'IdEncabezado' => $idEncabezado,
                'NumNomina' => $numNomina,
                'FechaExpiracion' => $fechaExpiracion,
                'FechaGenerado' => date('d-m-Y H:i:s'),
                'Monedero' => -$pagoMonedero,
                'BatchGasto' => $batchGasto,
                'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
            ]);

            // Guardamos el movimiento del monedero electronico
            MovimientoMonederoElectronico::insert([
                'NumNomina' => $numNomina,
                'IdEncabezado' => $idEncabezado,
                'FechaMovimiento' => date('d-m-Y H:i:s'),
                'Monedero' => -$pagoMonedero,
                'BatchGasto' => $batchGasto,
            ]);

            // return "exec Sp_Monedero_Pago " . $numNomina . ", '" . date('d-m-Y') . "', " . $pagoMonedero;
            // Corremos el procedimiento para que descuente el monedero de las lineas correspondientes
            DB::statement("exec Sp_Monedero_Pago " . $numNomina . ", '" . date('d-m-Y') . "', " . $pagoMonedero);
            // [Sp_Monedero_Pago]
        }
    }

    public function generarMonedero($idEncabezado, $numNomina, $monederoDescuento)
    {
        $monederoE = MonederoElectronico::where('Status', 0)
            ->first();

        $importeProcesado = DB::table('DatDetalle as a')
            ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
            ->where('a.IdEncabezado', $idEncabezado)
            ->where('b.IdGrupo', $monederoE->IdGrupo)
            ->sum('a.ImporteArticulo');

        // guardar monedero, si genero el empleado
        if (!empty($numNomina) && $importeProcesado - $monederoDescuento >= $monederoE->MonederoMultiplo) {
            $puntosGenerados = ($importeProcesado - $monederoDescuento) / $monederoE->MonederoMultiplo;
            $puntosTotales = intval($puntosGenerados);

            $monederoGenerado = $puntosTotales * $monederoE->PesosPorMultiplo;

            $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
            $fechaExpiracion = date('d-m-Y', $fecha);

            $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
                ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                ->sum('MonederoPorGastar');

            MovimientoMonederoElectronico::insert([
                'NumNomina' => $numNomina,
                'IdEncabezado' => $idEncabezado,
                'FechaMovimiento' => date('d-m-Y H:i:s'),
                'Monedero' => $monederoGenerado,
            ]);

            $faltanteMaximo = $monederoE->MaximoAcumulado - $monederoEmpleado;
            $monederoGenerado + $monederoEmpleado > $monederoE->MaximoAcumulado ? $monederoGenerado = $faltanteMaximo : $monederoGenerado = $monederoGenerado;

            // Guardamos el monedero generado, con el monedero gastado en 0 y el monedero por gastar igual al monedero
            DatMonederoAcumulado::insert([
                'IdEncabezado' => $idEncabezado,
                'NumNomina' => $numNomina,
                'FechaExpiracion' => $fechaExpiracion,
                'FechaGenerado' => date('d-m-Y H:i:s'),
                'Monedero' => $monederoGenerado,
                'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
                'MonederoGastado' => 0,
                'MonederoPorGastar' => $monederoGenerado,
            ]);
        }
    }
}
