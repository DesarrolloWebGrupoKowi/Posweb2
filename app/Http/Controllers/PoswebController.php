<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Banco;
use App\Models\BloqueoEmpleado;
use App\Models\CatPaquete;
use App\Models\ClienteCloudTienda;
use App\Models\CorteTienda;
use App\Models\DatAsignacionPreparadosLocal;
use App\Models\DatCaja;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;
use App\Models\DatEncPedido;
use App\Models\DatMonederoAcumulado;
use App\Models\DatTipoPago;
use App\Models\Empleado;
use App\Models\FrecuenteSocio;
use App\Models\Grupo;
use App\Models\InventarioTienda;
use App\Models\LimiteCredito;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioTienda;
use App\Models\MonederoElectronico;
use App\Models\MovimientoMonederoElectronico;
use App\Models\PreventaTmp;
use App\Models\SolicitudFactura;
use App\Models\TemporalPos;
use App\Models\Tienda;
use App\Models\TipoPago;
use App\Models\TipoPagoTienda;
use App\Models\UsuarioTienda;
use App\Models\VentaCreditoEmpleado;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use PDF;

class PoswebController extends Controller
{
    public function Pos(Request $request)
    {

        $temporalPos = TemporalPos::first(); // variables temporales usadas para la venta

        $numNomina = $temporalPos->NumNomina;
        $idEncabezado = $temporalPos->IdEncabezado;
        $monederoDescuento = $temporalPos->MonederoDescuento;

        $cliente = Empleado::with('LimiteCredito')
            ->where('NumNomina', $numNomina)
            ->first();

        $frecuenteSocio = FrecuenteSocio::with('TipoCliente')
            ->where('FolioViejo', $numNomina)
            ->first();

        if (!empty($cliente)) {

            // compras a credito del empleado que no han sido pagadas
            $gastoEmpleado = VentaCreditoEmpleado::where('NumNomina', $numNomina)
                ->sum('CreditoActual');

            // pagos parciales del empleado -> credito
            $pagoParcial = DatTipoPago::where('IdEncabezado', $idEncabezado)
                ->where('IdTipoPago', 2)
                ->sum('Pago');

            $creditoDisponible = ($cliente->LimiteCredito->Limite - $gastoEmpleado) - $pagoParcial;
        } else {
            $creditoDisponible = 0;
        }

        $fechaHoy = strftime("%e de %B", strtotime(date('Y-m-d')));

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $n = explode(' ', empty(Auth::user()->Empleado->Nombre) ? 'Nomina' : Auth::user()->Empleado->Nombre);
        $a = explode(' ', empty(Auth::user()->Empleado->Apellidos) ? 'Vacia' : Auth::user()->Empleado->Apellidos);
        $nombre = $n[0];
        $apellido = $a[0];

        $caja = DB::table('DatCajas as a')
            ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
            ->where('IdTienda', $idTienda)
            ->where('a.Activa', 0)
            ->where('a.Status', 0)
            ->first();

        $usuario = DB::table('CatUsuarios as a')
            ->leftJoin('CatUsuariosTienda as b', 'b.IdUsuario', 'a.IdUsuario')
            ->leftJoin('CatTiendas as c', 'c.IdTienda', 'b.IdTienda')
            ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'c.IdCiudad')
            ->where('a.IdUsuario', Auth::user()->IdUsuario)
            ->first();

        $preventa = DB::table('DatVentaTmp as a')
            ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
            ->where('a.IdTienda', $idTienda)
            ->get();

        $subTotal = PreventaTmp::where('IdTienda', $idTienda)
            ->sum('SubTotalArticulo');

        $subTotalPreventa = number_format($subTotal, 2);

        $iva = PreventaTmp::where('IdTienda', $idTienda)
            ->sum('IvaArticulo');

        $ivaPreventa = number_format($iva, 2);

        $total = PreventaTmp::where('IdTienda', $idTienda)
            ->sum('ImporteArticulo');

        $totalPreventa = number_format($total, 2);

        $banderaMultiPago = PreventaTmp::where('IdTienda', $idTienda)
            ->select('MultiPago')
            ->sum('MultiPago');

        $datTipoPago = DB::table('DatTipoPago as a')
            ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
            ->where('a.IdEncabezado', $idEncabezado)
            ->get();

        $tiposPago = TipoPagoTienda::with('TiposPago')
            ->where('IdTienda', $idTienda)
            ->get();

        $bancos = Banco::where('Status', 0)
            ->get();

        $banArticuloSinPrecio = PreventaTmp::where('PrecioVenta', 0)
            ->get();

        $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
            ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
            ->sum('Monedero') - $monederoDescuento;

        $paquetes = CatPaquete::where('Status', 0)
            ->whereNull('FechaEliminacion')
            ->get();

        $pedidosPendientes = DatEncPedido::where('IdTienda', $idTienda)
            ->where('Status', 0)
            ->whereDate('FechaRecoger', date('Y-m-d'))
            ->count();

        return view('Posweb.Pos', compact(
            'usuario',
            'fechaHoy',
            'preventa',
            'subTotalPreventa',
            'ivaPreventa',
            'totalPreventa',
            'idTienda',
            'banderaMultiPago',
            'datTipoPago',
            'caja',
            'tiposPago',
            'bancos',
            'nombre',
            'apellido',
            'cliente',
            'creditoDisponible',
            'banArticuloSinPrecio',
            'monederoEmpleado',
            'monederoDescuento',
            'paquetes',
            'pedidosPendientes',
            'frecuenteSocio'
        ));
    }

    public function EliminarPago($idDatTipoPago)
    {
        try {
            DB::beginTransaction(); // inicio de transaccion

            $idTienda = Auth::user()->usuarioTienda->IdTienda;

            $idEncabezado = TemporalPos::where('TemporalPos', 1)
                ->value('IdEncabezado');

            $idTipoPago = DatTipoPago::where('IdDatTipoPago', $idDatTipoPago)
                ->value('IdTipoPago');

            if ($idTipoPago == 7) {

                $pagoEliminado = DatTipoPago::where('IdDatTipoPago', $idDatTipoPago)
                    ->sum('Pago');

                $pagoMonedero = TemporalPos::where('TemporalPos', 1)
                    ->sum('MonederoDescuento');

                TemporalPos::where('TemporalPos', 1)
                    ->update([
                        'MonederoDescuento' => ($pagoMonedero - $pagoEliminado) == 0 ? null : $pagoMonedero - $pagoEliminado,
                    ]);
            }

            DatTipoPago::where('IdDatTipoPago', $idDatTipoPago)
                ->delete();

            $pagosRestantes = DatTipoPago::where('IdEncabezado', $idEncabezado)
                ->count();

            if ($pagosRestantes == 0) {

                PreventaTmp::where('IdTienda', $idTienda)
                    ->update([
                        'MulTiPago' => null,
                    ]);

                DatDetalle::where('IdEncabezado', $idEncabezado)
                    ->delete();

                DatEncabezado::where('IdEncabezado', $idEncabezado)
                    ->delete();

                TemporalPos::where('TemporalPos', 1)
                    ->update([
                        'IdEncabezado' => null,
                        'MonederoDescuento' => null,
                    ]);
            } else {
                $datTipoPago = DatTipoPago::where('IdEncabezado', $idEncabezado)
                    ->get();

                $totalVenta = DatEncabezado::where('IdEncabezado', $idEncabezado)
                    ->value('ImporteVenta');

                $restante = 0;
                foreach ($datTipoPago as $key => $pagos) {
                    $restanteVenta = abs($restante) - $totalVenta;
                    $totalVenta = abs($restanteVenta) - $pagos->Pago;

                    DatTipoPago::where('IdDatTipoPago', $pagos->IdDatTipoPago)
                        ->update([
                            'Restante' => -$totalVenta,
                        ]);
                }
            }

            DB::commit(); // todo salio correctamente

            return redirect()->route('Pos');
        } catch (\Throwable $th) {
            DB::rollback(); // hubo algun error
            return redirect()->route('Pos')->with('msjdelete', 'Error' . $th->getMessage());
        }
    }

    public function CancelarDescuento()
    {
        try {
            DB::beginTransaction();
            TemporalPos::where('TemporalPos', 1)
                ->update([
                    'MonederoDescuento' => null,
                ]);
            DB::commit();

            return redirect()->route('Pos')->with('msjdelete', 'Cobro en Dinero Electrónico Cancelado!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('Pos')->with('msjdelete', 'Error' . $th->getMessage());
        }
    }

    public function PagoMonedero(Request $request)
    {
        try {
            DB::beginTransaction();

            DB::commit();

            return redirect()->route('Pos')->with('msjAdd', 'Descuento en Monedero: $' . number_format($pagoMonedero, 2));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('Pos')->with('msjdelete', 'Error' . $th->getMessage());
        }
    }

    public function BuscarEmpleado(Request $request)
    {
        try {
            DB::beginTransaction();

            $numNomina = $request->numNomina;

            $empleado = Empleado::with(['LimiteCredito', 'BloqueoEmpleado' => function ($query) {
                $query->where('Status', 0);
            }])
                ->where('NumNomina', $numNomina)
                ->first();

            $frecuenteSocio = FrecuenteSocio::with('TipoCliente')
                ->where('FolioViejo', $numNomina)
                ->first();

            if (!empty($frecuenteSocio)) {
                // validar que el frecuente / socio este activo
                if ($frecuenteSocio->Status == 1) {
                    return 'El cliente: ' . $frecuenteSocio->TipoCliente->NomTipoCliente . ' ' . $frecuenteSocio->Nombre . ' esta inactivo';
                }

                DB::commit();
                return view('Posweb.Ifrempleado', compact('frecuenteSocio')); // va al cliente frecuente
            }

            if (!empty($empleado->BloqueoEmpleado)) {
                return '¡Empleado Bloqueado!, motivo: ' . $empleado->BloqueoEmpleado->MotivoBloqueo;
            }

            // validar que el empleado este activo en la empresa
            $statusEmpleado = 0;
            if (!empty($empleado) && $empleado->Status == 1) {
                $statusEmpleado = 1;
            }

            if (!empty($empleado)) {
                // gastos del empleado
                $gastoEmpleado = VentaCreditoEmpleado::where('NumNomina', $numNomina)
                    ->sum('CreditoActual');

                $saldoEmpleado = $empleado->LimiteCredito->Limite - $gastoEmpleado;
            } else {
                $saldoEmpleado = 0;
            }

            // consultar numero de ventas del dia del empleado
            $ventasDiariasEmpleado = VentaCreditoEmpleado::whereDate('FechaVenta', date('d-m-Y'))
                ->where('NumNomina', $numNomina)
                ->sum('TotalNumVenta');

            if (!empty($empleado)) {
                $limiteCredito = LimiteCredito::where('TipoNomina', $empleado->TipoNomina)
                    ->first();

                $totalVentasDiarias = $limiteCredito->TotalVentaDiaria;

                if ($ventasDiariasEmpleado < $totalVentasDiarias) {
                    $banVentasDiarias = 0;
                } else {
                    $banVentasDiarias = 1;
                }
            } else {
                $banVentasDiarias = 2;
            }

            //return $empleado;

            DB::commit();
            return view('Posweb.Ifrempleado', compact('empleado', 'saldoEmpleado', 'banVentasDiarias', 'statusEmpleado'));
        } catch (\Throwable $th) {
            DB::rollback();
            return 'Error Controlado: ' . $th->getMessage();
        }
    }

    public function CobroEmpleado(Request $request)
    {
        $numNomina = $request->numNomina;

        TemporalPos::where('TemporalPos', 1)
            ->update([
                'NumNomina' => $numNomina,
                'MonederoDescuento' => null,
            ]);

        $preventa = PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get();

        //Lista de Precios EMPYSOC
        $empySoc = ListaPrecio::where('IdListaPrecio', 4)
            ->first();

        $nomArticulos = [];
        foreach ($preventa as $key => $pArticulo) {

            $buscarArticulo = Articulo::where('IdArticulo', $pArticulo->IdArticulo)
                ->first();

            if (empty($numNomina) || $pArticulo->CantArticulo > $empySoc->PesoMaximo) {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva'
                    )
                    ->where('a.CodEtiqueta', $buscarArticulo->CodEtiqueta)
                    ->where('d.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('c.IdListaPrecio', '<>', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pArticulo->CantArticulo)
                    ->first();
            } else {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva'
                    )
                    ->where('a.CodEtiqueta', $buscarArticulo->CodEtiqueta)
                    ->where('d.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('c.IdListaPrecio', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pArticulo->CantArticulo)
                    ->first();
            }

            $subTotal = $articulo->PrecioArticulo * $pArticulo->CantArticulo;
            if ($articulo->Iva == 0) {
                $iva = $subTotal * $articulo->PorcentajeIva;
            } else {
                $iva = 0;
            }

            if ($articulo->PrecioArticulo == 0) {
                $nomArticulos[] = $articulo->NomArticulo;
                $iva = 0;
            }

            $total = $subTotal + $iva;

            // validar que el precio del paquete no se vea afectado, al pasar la tarjeta del empleado/socio/frecuente
            if (empty($pArticulo->IdPaquete)) {

                PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('IdArticulo', $pArticulo->IdArticulo)
                    ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                    ->update([
                        'PrecioLista' => $articulo->PrecioArticulo,
                        'PrecioVenta' => $articulo->PrecioArticulo,
                        'IdListaPrecio' => empty($numNomina) ? $articulo->IdListaPrecio : 4,
                        'SubTotalArticulo' => $subTotal,
                        'IvaArticulo' => $iva,
                        'ImporteArticulo' => $total,
                    ]);
            } else {
                PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('IdArticulo', $pArticulo->IdArticulo)
                    ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                    ->update([
                        'IdListaPrecio' => empty($numNomina) ? $articulo->IdListaPrecio : 4,
                    ]);
            }
        }

        if (count($nomArticulos) > 0) {
            $articulos = '';
            for ($i = 0; $i < count($nomArticulos); $i++) {
                $articulos = $articulos . $nomArticulos[$i] . ', ';
            }

            return redirect()->route('Pos')->with('Pos', 'Los Siguientes Articulos: ' . $articulos . 'No Tienen Precio de Empleado, Comunicarse con el Gerente!');
        }

        return redirect()->route('Pos');
    }

    public function CobroFrecuenteSocio($folioFrecuenteSocio)
    {
        TemporalPos::where('TemporalPos', 1)
            ->update([
                'NumNomina' => $folioFrecuenteSocio,
                'MonederoDescuento' => null,
            ]);

        $preventa = PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get();

        //Lista de Precios EMPYSOC
        $empySoc = ListaPrecio::where('IdListaPrecio', 4)
            ->first();

        $nomArticulos = [];
        foreach ($preventa as $key => $pArticulo) {

            $buscarArticulo = Articulo::where('IdArticulo', $pArticulo->IdArticulo)
                ->first();

            if (empty($folioFrecuenteSocio) || $pArticulo->CantArticulo > $empySoc->PesoMaximo) {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva'
                    )
                    ->where('a.CodEtiqueta', $buscarArticulo->CodEtiqueta)
                    ->where('d.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('c.IdListaPrecio', '<>', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pArticulo->CantArticulo)
                    ->first();
            } else {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva'
                    )
                    ->where('a.CodEtiqueta', $buscarArticulo->CodEtiqueta)
                    ->where('d.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('c.IdListaPrecio', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pArticulo->CantArticulo)
                    ->first();
            }

            $subTotal = $articulo->PrecioArticulo * $pArticulo->CantArticulo;
            if ($articulo->Iva == 0) {
                $iva = $subTotal * $articulo->PorcentajeIva;
            } else {
                $iva = 0;
            }

            if ($articulo->PrecioArticulo == 0) {
                $nomArticulos[] = $articulo->NomArticulo;
                $iva = 0;
            }

            $total = $subTotal + $iva;

            // validar que el precio del paquete no se vea afectado, al pasar la tarjeta del empleado/socio/frecuente
            if (empty($pArticulo->IdPaquete)) {

                PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('IdArticulo', $pArticulo->IdArticulo)
                    ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                    ->update([
                        'PrecioLista' => $articulo->PrecioArticulo,
                        'PrecioVenta' => $articulo->PrecioArticulo,
                        'IdListaPrecio' => empty($numNomina) ? $articulo->IdListaPrecio : 4,
                        'SubTotalArticulo' => $subTotal,
                        'IvaArticulo' => $iva,
                        'ImporteArticulo' => $total,
                    ]);
            } else {
                PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('IdArticulo', $pArticulo->IdArticulo)
                    ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                    ->update([
                        'IdListaPrecio' => empty($numNomina) ? $articulo->IdListaPrecio : 4,
                    ]);
            }
        }

        if (count($nomArticulos) > 0) {
            $articulos = '';
            for ($i = 0; $i < count($nomArticulos); $i++) {
                $articulos = $articulos . $nomArticulos[$i] . ', ';
            }

            return redirect()->route('Pos')->with('Pos', 'Los Siguientes Articulos: ' . $articulos . 'No Tienen Precio de Empleado, Comunicarse con el Gerente!');
        }

        return redirect()->route('Pos');
    }

    public function QuitarEmpleado()
    {

        try {
            DB::beginTransaction();

            $preventa = PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->get();

            TemporalPos::where('TemporalPos', 1)
                ->update([
                    'NumNomina' => null,
                    'MonederoDescuento' => null,
                ]);

            foreach ($preventa as $key => $pArticulo) {
                $buscarArticulo = Articulo::where('IdArticulo', $pArticulo->IdArticulo)
                    ->first();

                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva'
                    )
                    ->where('a.CodEtiqueta', $buscarArticulo->CodEtiqueta)
                    ->where('d.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('c.IdListaPrecio', '<>', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pArticulo->CantArticulo)
                    ->first();

                //return $articulo;

                $subTotal = $articulo->PrecioArticulo * $pArticulo->CantArticulo;
                if ($articulo->Iva == 0) {
                    $iva = $subTotal * $articulo->PorcentajeIva;
                } else {
                    $iva = 0;
                }

                $total = $subTotal + $iva;

                // validar que exista un paquete para no cambiar el precio
                if (empty($pArticulo->IdPaquete)) {

                    PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('IdArticulo', $pArticulo->IdArticulo)
                        ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                        ->update([
                            'PrecioLista' => $articulo->PrecioArticulo,
                            'PrecioVenta' => $articulo->PrecioArticulo,
                            'IdListaPrecio' => $articulo->IdListaPrecio,
                            'SubTotalArticulo' => $subTotal,
                            'IvaArticulo' => $iva,
                            'ImporteArticulo' => $total,
                        ]);
                } else {
                    PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('IdArticulo', $pArticulo->IdArticulo)
                        ->where('IdDatVentaTmp', $pArticulo->IdDatVentaTmp)
                        ->update([
                            'IdListaPrecio' => $articulo->IdListaPrecio,
                        ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return redirect('Pos');
    }

    public function CalculosPreventa(Request $request)
    {
        //return $request->all();
        $numNomina = TemporalPos::where('TemporalPos', 1)
            ->value('NumNomina');

        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $cantidad = $request->txtCantidad;
        $codigo = $request->txtCodigo;

        //Extraer codigo de etiqueta
        $inicio = substr($codigo, 0, 3);
        $codEtiqueta = substr($codigo, 3, 4);
        $primerPeso = substr($codigo, 7, 5);
        $peso = $primerPeso / 1000;

        //Lista de Precios EMPYSOC
        $empySoc = ListaPrecio::where('IdListaPrecio', 4)
            ->first();

        //return $empySoc;

        if ($inicio == '200') {
            is_null($cantidad) ? $peso = $peso : $peso = $cantidad;

            if (empty($numNomina) || $peso > $empySoc->PesoMaximo) {
                $listaPrecioTienda = DB::table('CatListasPrecio as a')
                    ->leftJoin('DatListaPrecioTienda as b', 'b.IdListaPrecio', 'a.IdListaPrecio')
                    ->where('b.IdTienda', $idTienda)
                    ->where('a.IdListaPrecio', '<>', 4)
                    ->whereRaw('? between a.PesoMinimo and a.PesoMaximo', $peso)
                    ->first();
            } else {
                $listaPrecioTienda = DB::table('CatListasPrecio as a')
                    ->leftJoin('DatListaPrecioTienda as b', 'b.IdListaPrecio', 'a.IdListaPrecio')
                    ->where('b.IdTienda', $idTienda)
                    ->where('a.IdListaPrecio', 4)
                    ->whereRaw('? between a.PesoMinimo and a.PesoMaximo', $peso)
                    ->first();
            }

            //return $listaPrecioTienda;

            //La Tienda no tiene esa lista de precio
            if (empty($listaPrecioTienda)) {
                return redirect()->route('Pos')->with('Pos', 'La Tienda no Tiene la Lista de Precio, Para el Peso: ' . number_format($peso, 2));
            }

            if (empty($numNomina) || $peso > $empySoc->PesoMaximo) {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva',
                        'b.IdDatPrecios'
                    )
                    ->where('a.CodEtiqueta', $codEtiqueta)
                    ->where('d.IdTienda', $idTienda)
                    ->where('c.IdListaPrecio', '<>', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $peso)
                    ->first();
            } else {
                $articulo = DB::table('CatArticulos as a')
                    ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                    ->select(
                        'a.IdArticulo',
                        'a.CodArticulo',
                        'a.NomArticulo',
                        'a.Peso',
                        'a.Iva',
                        'a.Status',
                        'b.PrecioArticulo',
                        'c.IdListaPrecio',
                        'c.NomListaPrecio',
                        'c.PorcentajeIva',
                        'b.IdDatPrecios'
                    )
                    ->where('a.CodEtiqueta', $codEtiqueta)
                    ->where('d.IdTienda', $idTienda)
                    ->where('c.IdListaPrecio', 4)
                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $peso)
                    ->first();
            }

            //return $articulo;

        } else {
            $articuloPorAmece = Articulo::where('Amece', $codigo)
                ->where('Status', 0)
                ->first();

            //Si la etiqueta esta mal escrita
            if (empty($articuloPorAmece)) {
                return redirect()->route('Pos')->with('Pos', 'Código Amece Incorrecto: ' . $codigo);
            }

            //Si el articulo no tiene peso fijo
            if (is_null($articuloPorAmece->Peso) and is_null($cantidad)) {
                return redirect()->route('Pos')->with('Pos', 'El Articulo: ' . $articuloPorAmece->NomArticulo . ' No tiene Peso Fijo. Comunicarse con el Gerente ó Coloque el Peso!');
            }

            if (!empty($articuloPorAmece)) {

                $pesoFijo = $articuloPorAmece->Peso;

                is_null($cantidad) ? $pesoFijo = $pesoFijo : $pesoFijo = $cantidad;

                if (empty($numNomina) || $pesoFijo > $empySoc->PesoMaximo) {
                    $listaPrecioTienda = DB::table('CatListasPrecio as a')
                        ->leftJoin('DatListaPrecioTienda as b', 'b.IdListaPrecio', 'a.IdListaPrecio')
                        ->where('b.IdTienda', $idTienda)
                        ->where('a.IdListaPrecio', '<>', 4)
                        ->whereRaw('? between a.PesoMinimo and a.PesoMaximo', $pesoFijo)
                        ->first();
                } else {
                    $listaPrecioTienda = DB::table('CatListasPrecio as a')
                        ->leftJoin('DatListaPrecioTienda as b', 'b.IdListaPrecio', 'a.IdListaPrecio')
                        ->where('b.IdTienda', $idTienda)
                        ->where('a.IdListaPrecio', 4)
                        ->whereRaw('? between a.PesoMinimo and a.PesoMaximo', $pesoFijo)
                        ->first();
                }

                //La Tienda no tiene esa lista de precio
                if (empty($listaPrecioTienda)) {
                    return redirect()->route('Pos')->with('Pos', 'La Tienda no Tiene la Lista de Precio, Para el Peso: ' . number_format($pesoFijo, 2));
                }

                if (empty($numNomina) || $pesoFijo > $empySoc->PesoMaximo) {
                    $articulo = DB::table('CatArticulos as a')
                        ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                        ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                        ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                        ->where('a.Amece', $codigo)
                        ->where('d.IdTienda', $idTienda)
                        ->where('c.IdListaPrecio', '<>', 4)
                        ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pesoFijo)
                        ->toSql();

                    return $articulo;
                } else {
                    $articulo = DB::table('CatArticulos as a')
                        ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
                        ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
                        ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'c.IdListaPrecio')
                        ->where('a.Amece', $codigo)
                        ->where('d.IdTienda', $idTienda)
                        ->where('c.IdListaPrecio', 4)
                        ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $pesoFijo)
                        ->first();
                }
            }
            //Asignar el peso segun el peso escrito en el txt o el peso fijo
            $peso = $pesoFijo;
        }

        //Si el peso de la etiqueta es igual a 0
        if ($peso == 0) {
            return redirect()->route('Pos')->with('Pos', 'La Etiqueta No Trae Peso: ' . $codigo);
        }
        //Si no encontro el articulo con ese codigo de etiqueta!
        if (empty($articulo)) {
            return redirect()->route('Pos')->with('Pos', 'No se Encontro el Articulo con el PLU: ' . $codEtiqueta);
        }
        //Si el articulo (status) esta caducado
        if ($articulo->Status == 1) {
            return redirect()->route('Pos')->with('Pos', 'Articulo: ' . $articulo->NomArticulo . ' Caducado en Base de Datos, Comunicarse con su Gerente!');
        }
        //si el precio del articulo es cero. comunicarse con el gerente para que le asigne
        if ($articulo->PrecioArticulo == 0) {
            return redirect()->route('Pos')->with('Pos', 'El Articulo: ' . $articulo->NomArticulo . ' No Tiene Precio' . ' Para la Lista de: ' . $articulo->NomListaPrecio . ' Comunicarse con el Gerente!');
        }

        //Consultar Inventario de la Tienda
        $cantVentaTmp = PreventaTmp::where('IdTienda', $idTienda)
            ->where('IdArticulo', $articulo->IdArticulo)
            ->sum('CantArticulo');

        $pesoAcumulado = $cantVentaTmp + $peso;

        $stockArticulo = InventarioTienda::where('IdTienda', $idTienda)
            ->where('CodArticulo', $articulo->CodArticulo)
            ->sum('StockArticulo');

        if ($stockArticulo < $pesoAcumulado) {
            return redirect()->route('Pos')->with('Pos', 'Inventario Insuficiente Para el Articulo: ' . $articulo->NomArticulo);
        }

        //Sacar Iva del articulo
        if ($articulo->Iva == 0) {
            $porcentajeIva = $articulo->PorcentajeIva;
            $importePorIva = $articulo->PrecioArticulo * $peso;
            $iva = $porcentajeIva * $importePorIva;
            $ivaArticulo = number_format($iva, 2);
        } else {
            $ivaArticulo = 0;
        }

        //calculos venta temporal
        $idDatVentaTmp = PreventaTmp::where('IdTienda', $idTienda)
            ->max('IdDatVentaTmp') + 1;

        $subTotal = $articulo->PrecioArticulo * $peso;
        $subTotalArticulo = number_format($subTotal, 2);
        $importe = $subTotal + $ivaArticulo;
        $importeArticulo = number_format($importe, 2);

        DB::table('DatVentaTmp')
            ->insert([
                'IdDatVentaTmp' => $idDatVentaTmp,
                'IdTienda' => $idTienda,
                'IdArticulo' => $articulo->IdArticulo,
                'CantArticulo' => $peso,
                'PrecioLista' => $articulo->PrecioArticulo,
                'PrecioVenta' => $articulo->PrecioArticulo,
                'IdListaPrecio' => $articulo->IdListaPrecio,
                'IvaArticulo' => $ivaArticulo,
                'SubTotalArticulo' => $subTotalArticulo,
                'ImporteArticulo' => $importeArticulo,
                'Status' => 0,
                'IdDatPrecios' => $articulo->IdDatPrecios,
            ]);

        return redirect()->route('Pos');
    }

    public function iframeConsultarArticulo(Request $request)
    {
        $opcionBusqueda = $request->radioBuscar;
        $filtroArticulo = $request->filtroArticulo;

        $idsListaPrecio = ListaPrecioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->pluck('IdListaPrecio');

        if ($opcionBusqueda == 'codigo' || empty($opcionBusqueda)) {
            $articulos = Articulo::with(['PrecioArticulo' => function ($listaPrecio) use ($idsListaPrecio) {
                $listaPrecio->whereIn('CatListasPrecio.IdListaPrecio', $idsListaPrecio);
            }])
                ->where('CodArticulo', $filtroArticulo)
                ->get();
        } else {
            $articulos = Articulo::with(['PrecioArticulo' => function ($listaPrecio) use ($idsListaPrecio) {
                $listaPrecio->whereIn('CatListasPrecio.IdListaPrecio', $idsListaPrecio);
            }])
                ->where('NomArticulo', 'like', '%' . $filtroArticulo . '%')
                ->get();
        }

        //return $articulos;

        return view('Posweb.iframeConsultarArticulo', compact('articulos'));
    }

    public function EliminarArticuloPreventa(Request $request, $id)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        try {
            DB::beginTransaction();
            $idPaquete = PreventaTmp::where('IdDatVentaTmp', $id)
                ->where('IdTienda', $idTienda)
                ->value('IdPaquete');

            if (!empty($idPaquete)) {
                PreventaTmp::where('IdPaquete', $idPaquete)
                    ->where('IdTienda', $idTienda)
                    ->delete();
            } else {
                PreventaTmp::where('IdDatVentaTmp', $id)
                    ->where('IdTienda', $idTienda)
                    ->delete();
            }

            DB::commit();
            return redirect()->route('Pos');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('Pos')->with('msjDelete', 'Error: ' . $th->getMessage());
        }
    }

    public function EliminarPreventa(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        PreventaTmp::where('IdTienda', $idTienda)
            ->delete();

        return redirect()->route('Pos');
    }

    public function GuardarVenta(Request $request)
    {
        $temporalPos = TemporalPos::first();

        $numNomina = $temporalPos->NumNomina;

        $multipago = PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->select('MultiPago')
            ->distinct()
            ->first();

        try {
            DB::beginTransaction();

            if ($multipago->MultiPago == null) {
                $idTipoPago = $request->tipoPago;

                $idUsuario = Auth::user()->IdUsuario;

                $idTienda = Auth::user()->usuarioTienda->IdTienda;

                $preventaIdPedido = PreventaTmp::select('IdPedido')
                    ->distinct()
                    ->where('IdTienda', $idTienda)
                    ->whereNotNull('IdPedido')
                    ->first();

                empty($preventaIdPedido) ? $idPedidoDist = null : $idPedidoDist = $preventaIdPedido->IdPedido;

                $pago = $request->txtPago;

                // Aqui obtenemos la preventa
                $detalle = PreventaTmp::where('IdTienda', $idTienda)
                    ->get();

                $subTotal = PreventaTmp::where('IdTienda', $idTienda)
                    ->sum('SubTotalArticulo');

                $subTotalVenta = number_format($subTotal, 2);

                $iva = PreventaTmp::where('IdTienda', $idTienda)
                    ->sum('IvaArticulo');
                $ivaVenta = number_format($iva, 2);

                $totalVentaSinFormat = PreventaTmp::where('IdTienda', $idTienda)
                    ->sum('ImporteArticulo') - $temporalPos->MonederoDescuento;

                $totalVenta = number_format($totalVentaSinFormat, 2); // format a la venta (2)

                $idTicket = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', date('d-m-Y'))
                    ->max('IdTicket') + 1;

                $caja = DB::table('DatCajas as a')
                    ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                    ->where('IdTienda', $idTienda)
                    ->where('a.Activa', 0)
                    ->where('a.Status', 0)
                    ->first();

                if (empty($caja)) {
                    return redirect('Pos')->with('Pos', 'La Tienda No Tiene Caja Activa, Comuniquese con Sistemas!');
                }

                if ($idTipoPago == 2) {
                    $cliente = Empleado::with('LimiteCredito')
                        ->where('NumNomina', $numNomina)
                        ->first();

                    // compras a credito del empleado que no han sido pagadas
                    $gastoEmpleado = VentaCreditoEmpleado::where('NumNomina', $numNomina)
                        ->sum('CreditoActual');

                    $creditoDisponible = $cliente->LimiteCredito->Limite - $gastoEmpleado;

                    $date1 = new DateTime($cliente->Fecha_Ingreso);
                    $date2 = new DateTime(date('Y-m-d'));
                    $diff = $date1->diff($date2);

                    $diasTrabajados = $diff->days;

                    if ($diasTrabajados <= 30) {
                        return redirect()->route('Pos')->with('Pos', 'El Empleado no puede llevar a Crédito, al no tener más de 30 dias trabajados. Dias Trabajados: ' . $diasTrabajados);
                    }

                    if ($pago > $creditoDisponible) {
                        return redirect()->route('Pos')->with('Pos', 'Crédito Insuficiente, Verifique el Crédito Disponible del Empleado!');
                    }
                }

                if ($idTipoPago == 7) {
                    $temporalPos = TemporalPos::first();
                    $numNomina = $temporalPos->NumNomina;
                    $descuentoMonedero = $temporalPos->MonederoDescuento;

                    $monederoE = MonederoElectronico::where('Status', 0)
                        ->first();

                    $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
                        ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                        ->sum('Monedero') - $descuentoMonedero;

                    $importeProcesado = DB::table('DatVentaTmp as a')
                        ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
                        ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('b.IdGrupo', $monederoE->IdGrupo)
                        ->sum('a.ImporteArticulo');

                    $pagoMonedero = $pago;

                    //Validaciones de Monedero Electrónico
                    if ($pagoMonedero > $monederoEmpleado) {
                        return redirect()->route('Pos')->with('Pos', 'Saldo en Monedero Insuficiente, Saldo Actual: $' . number_format($monederoEmpleado, 2));
                    }

                    if ($importeProcesado == 0) {
                        return redirect()->route('Pos')->with('Pos', 'No Puede Pagar Con Monedero Electrónico Porque No Lleva Producto Procesado!');
                    }

                    if ($pagoMonedero > $importeProcesado) {
                        return redirect()->route('Pos')->with('Pos', 'Solo Puede Pagar $' . number_format($importeProcesado, 2) . ' En Monedero Electrónico!');
                    }

                    TemporalPos::where('TemporalPos', 1)
                        ->update([
                            'MonederoDescuento' => $pagoMonedero,
                        ]);
                }

                if ($idTipoPago != 1 && $pago > $totalVenta) {
                    return redirect('Pos')->with('Pos', 'No Puede Pagar Más del Importe Total! (1)');
                }

                DB::table('DatEncabezado')
                    ->insert([
                        'IdEncabezado' => 0,
                        'IdTienda' => $idTienda,
                        'IdDatCaja' => $caja->IdDatCajas,
                        'IdTicket' => $idTicket,
                        'FechaVenta' => date('d-m-Y H:i:s'),
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

                $idDatEncabezado = DatEncabezado::where('IdTienda', $idTienda)
                    ->max('IdDatEncabezado');

                $idEncabezado = $idTienda . $caja->NumCaja . $idDatEncabezado;

                DatEncabezado::where('IdTienda', $idTienda)
                    ->where('IdDatEncabezado', $idDatEncabezado)
                    ->update([
                        'IdEncabezado' => $idEncabezado,
                    ]);

                $preventa = PreventaTmp::where('IdTienda', $idTienda)
                    ->get();

                foreach ($preventa as $index => $detalle) {
                    DatDetalle::insert([
                        'IdEncabezado' => $idEncabezado,
                        'IdArticulo' => $detalle->IdArticulo,
                        'CantArticulo' => $detalle->CantArticulo,
                        'PrecioArticulo' => $detalle->PrecioVenta,
                        'IdListaPrecio' => $detalle->IdListaPrecio,
                        'PrecioRecorte' => null,
                        'CapturaManual' => null,
                        'ImporteArticulo' => $detalle->ImporteArticulo,
                        'IvaArticulo' => $detalle->IvaArticulo,
                        'SubTotalArticulo' => $detalle->SubTotalArticulo,
                        'IdPaquete' => $detalle->IdPaquete,
                        'IdPedido' => $detalle->IdPedido,
                        'IdDatPrecios' => $detalle->IdDatPrecios,
                        'Linea' => $index + 1,
                    ]);
                }

                // Obtenemos los paquetes de la venta
                $paquetesPreparados = PreventaTmp::select(
                    'DatVentaTmp.IdPaquete',
                    DB::raw('COUNT(DatVentaTmp.CantArticulo) as Cantidad')
                )
                    ->where('IdTienda', $idTienda)
                    ->whereNotNull('DatVentaTmp.IdPaquete')
                    ->groupBy('DatVentaTmp.IdPaquete', 'DatVentaTmp.IdArticulo')
                    ->distinct()
                    ->get();

                // Iteramos la lista de paquetes
                foreach ($paquetesPreparados as $pp) {
                    $paquetesConPreparado = CatPaquete::where('CatPaquetes.IdPaquete', $pp->IdPaquete)
                        ->whereNotNull('CatPaquetes.IdPreparado')
                        ->get();

                    // Buscamos que los paquetes tengan id de prepadado
                    if (count($paquetesConPreparado) != 0) {
                        // Obtenemos la cantidad ya vendida
                        $paquetesConPreparado[0]->IdPreparado;
                        $cantidadTotal =  DatAsignacionPreparadosLocal::where('DatAsignacionPreparados.IdPreparado', $paquetesConPreparado[0]->IdPreparado)
                            ->value('CantidadEnvio');

                        $cantidadVendida =  DatAsignacionPreparadosLocal::where('DatAsignacionPreparados.IdPreparado', $paquetesConPreparado[0]->IdPreparado)
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
                            ->update([
                                'CantidadVendida' => !$cantidadVendida ? $pp->Cantidad : $cantidadVendida + $pp->Cantidad,
                            ]);
                    }
                }

                $importeVenta = PreventaTmp::where('IdTienda', $idTienda)
                    ->select('ImporteArticulo')
                    ->sum('ImporteArticulo');

                //Si hay IdPedido en el detalle para marcarlo como vendido
                if ($idPedidoDist != null) {
                    DatEncPedido::where('IdTienda', $idTienda)
                        ->where('IdPedido', $idPedidoDist)
                        ->update([
                            'Status' => 2,
                        ]);
                }

                if ($pago > $importeVenta || $pago == $importeVenta) {

                    $restanteSinFormat = $pago - $importeVenta;
                    $restante = number_format($restanteSinFormat, 2);

                    DatTipoPago::insert([
                        'IdEncabezado' => $idEncabezado,
                        'IdTipoPago' => $idTipoPago,
                        'Pago' => $pago,
                        'Restante' => $restante,
                        'IdBanco' => $request->idBanco,
                        'numTarjeta' => $request->numTarjeta,
                    ]);

                    PreventaTmp::where('IdTienda', $idTienda)
                        ->delete();

                    TemporalPos::where('TemporalPos', 1)
                        ->update([
                            'NumNomina' => null,
                            'IdEncabezado' => null,
                            'MonederoDescuento' => null,
                        ]);

                    //Descontar Inventario
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

                    //Descontar Monedero Si Uso Para Pagar
                    if (!empty($numNomina) && !empty($temporalPos->MonederoDescuento)) {
                        $pagoMonedero = $temporalPos->MonederoDescuento;

                        $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
                            ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                            ->orderBy('FechaExpiracion')
                            ->get();

                        // generar batchGasto
                        $countBatch = DatMonederoAcumulado::max('IdDatMonedero') + 1;

                        $numCaja = DB::table('DatCajas as a')
                            ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                            ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->where('a.Activa', 0)
                            ->where('a.Status', 0)
                            ->value('NumCaja');

                        $batchGasto = Auth::user()->usuarioTienda->IdTienda . $numCaja . $countBatch; // batchGasto

                        //Consultar Catalogo de Monedero
                        $monederoE = MonederoElectronico::where('Status', 0)
                            ->first();

                        $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
                        $fechaExpiracion = date('d-m-Y', $fecha);

                        DatMonederoAcumulado::insert([
                            'IdEncabezado' => $idEncabezado,
                            'NumNomina' => $numNomina,
                            'FechaExpiracion' => $fechaExpiracion,
                            'FechaGenerado' => date('d-m-Y H:i:s'),
                            'Monedero' => -$pagoMonedero,
                            'BatchGasto' => $batchGasto,
                            'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
                        ]);

                        MovimientoMonederoElectronico::insert([
                            'NumNomina' => $numNomina,
                            'IdEncabezado' => $idEncabezado,
                            'FechaMovimiento' => date('d-m-Y H:i:s'),
                            'Monedero' => -$pagoMonedero,
                            'BatchGasto' => $batchGasto,
                        ]);
                    }

                    //Consultar Catalogo de Monedero
                    $monederoE = MonederoElectronico::where('Status', 0)
                        ->first();

                    $importeProcesado = DB::table('DatDetalle as a')
                        ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
                        ->where('a.IdEncabezado', $idEncabezado)
                        ->where('b.IdGrupo', $monederoE->IdGrupo)
                        ->sum('a.ImporteArticulo');

                    // guardar monedero, si genero el empleado
                    if (!empty($numNomina) && $importeProcesado >= $monederoE->MonederoMultiplo) {
                        $puntosGenerados = $importeProcesado / $monederoE->MonederoMultiplo;
                        $puntosTotales = intval($puntosGenerados);

                        $monederoGenerado = $puntosTotales * $monederoE->PesosPorMultiplo;

                        $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
                        $fechaExpiracion = date('d-m-Y', $fecha);

                        $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $numNomina)
                            ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                            ->sum('Monedero');

                        MovimientoMonederoElectronico::insert([
                            'NumNomina' => $numNomina,
                            'IdEncabezado' => $idEncabezado,
                            'FechaMovimiento' => date('d-m-Y H:i:s'),
                            'Monedero' => $monederoGenerado,
                        ]);

                        $faltanteMaximo = $monederoE->MaximoAcumulado - $monederoEmpleado;
                        $monederoGenerado + $monederoEmpleado > $monederoE->MaximoAcumulado ? $monederoGenerado = $faltanteMaximo : $monederoGenerado = $monederoGenerado;

                        DatMonederoAcumulado::insert([
                            'IdEncabezado' => $idEncabezado,
                            'NumNomina' => $numNomina,
                            'FechaExpiracion' => $fechaExpiracion,
                            'FechaGenerado' => date('d-m-Y H:i:s'),
                            'Monedero' => $monederoGenerado,
                            'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
                        ]);
                    }

                    //subir venta
                    DatEncabezado::where('IdEncabezado', $idEncabezado)
                        ->update([
                            'Subir' => 0,
                        ]);

                    if (!empty($numNomina)) {
                        // validar el pago para saber si es credito o no
                        $pago = $idTipoPago == 2 ? $pago : 0;

                        // guardar numero de ventas e importe del credito del empleado
                        DB::statement("exec Sp_Guardar_DatConcenVenta " . $idTienda . ", '" . $idEncabezado . "', " . $numNomina . ", '" . date('d-m-Y') . "', " . $pago . ", 1");
                    }

                    // imprimir ticket
                    DB::select("exec SP_GENERAR_TICKET_CORTE " . $idEncabezado . ", " . $idTienda . ", '" . date('d-m-Y H:i:s') . "'");
                    DB::commit();

                    return redirect()->route('ImprimirTicketVenta', compact('idEncabezado', 'restante', 'pago'));
                }
                // si se realiza un pago parcial (multipago) -> pago menos del total de la venta //
                else {
                    $restanteSinFormat = $pago - $importeVenta;
                    $restante = number_format($restanteSinFormat, 2);

                    if ($idTipoPago == 2) {
                        $cliente = Empleado::with('LimiteCredito')
                            ->where('NumNomina', $numNomina)
                            ->first();

                        // compras a credito del empleado que no han sido pagadas
                        $gastoEmpleado = VentaCreditoEmpleado::where('NumNomina', $numNomina)
                            ->sum('CreditoActual');

                        // pago parcial del empleado -> credito
                        $pagoParcial = DatTipoPago::where('IdEncabezado', $idEncabezado)
                            ->where('IdTipoPago', 2)
                            ->sum('Pago');

                        $creditoDisponible = ($cliente->LimiteCredito->Limite - $gastoEmpleado) - $pagoParcial;

                        $date1 = new DateTime($cliente->Fecha_Ingreso);
                        $date2 = new DateTime(date('Y-m-d'));
                        $diff = $date1->diff($date2);

                        $diasTrabajados = $diff->days;

                        if ($diasTrabajados <= 30) {
                            return redirect()->route('Pos')->with('Pos', 'El Empleado no puede llevar a Crédito, al no tener más de 30 dias trabajados. Dias Trabajados: ' . $diasTrabajados);
                        }

                        if ($pago > $creditoDisponible) {
                            return redirect()->route('Pos')->with('Pos', 'Crédito Insuficiente, Verifique el Crédito Disponible del Empleado!');
                        }
                    }

                    DatTipoPago::insert([
                        'IdEncabezado' => $idEncabezado,
                        'IdTipoPago' => $idTipoPago,
                        'Pago' => $pago,
                        'Restante' => $restante,
                        'IdBanco' => $request->idBanco,
                        'numTarjeta' => $request->numTarjeta,
                    ]);

                    PreventaTmp::where('IdTienda', $idTienda)
                        ->update([
                            'MulTiPago' => 1,
                        ]);

                    TemporalPos::where('TemporalPos', 1)
                        ->update([
                            'IdEncabezado' => $idEncabezado,
                        ]);
                    DB::commit();

                    return redirect()->route('Pos');
                }
            } else {
                $caja = DB::table('DatCajas as a')
                    ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                    ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('a.Activa', 0)
                    ->where('a.Status', 0)
                    ->first();

                if (empty($caja)) {
                    return redirect('Pos')->with('Pos', 'La Tienda No Tiene Caja Activa, Comuniquese con Sistemas!');
                }

                $idDatEncabezado = DatEncabezado::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->max('IdDatEncabezado');

                $idEncabezado = Auth::user()->usuarioTienda->IdTienda . $caja->NumCaja . $idDatEncabezado;

                $datTipoPago = DatTipoPago::where('IdEncabezado', $idEncabezado)
                    ->orderBy('IdDatTipoPago', 'desc')
                    ->first();

                $restante = $datTipoPago->Restante;

                $pago = $request->txtPago;

                $idTipoPago = $request->tipoPago;

                empty($request->idBanco) ? $idBanco = 0 : $idBanco = $request->idBanco;

                empty($request->numTarjeta) ? $numTarjeta = 0 : $numTarjeta = $request->numTarjeta;

                return redirect()->route('CalculoMultiPago', compact('idEncabezado', 'restante', 'pago', 'idTipoPago', 'idBanco', 'numTarjeta'));
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('Pos')->with('Pos', 'Error: ' . $th->getMessage());
        }
    }

    public function CalculoMultiPago(Request $request, $idEncabezado, $restante, $pago, $idTipoPago, $idBanco, $numTarjeta)
    {

        try {
            DB::beginTransaction();
            if ($idTipoPago != 1) {
                if ($pago > abs($restante)) {
                    return redirect('Pos')->with('Pos', 'No Puede Pagar Más del Restante! (2)');
                }
            }

            $restanteSinFormato = $pago - abs($restante);
            $restante = number_format($restanteSinFormato, 2);

            $idBanco == 0 ? $idBanco = null : $idBanco = $idBanco;
            $numTarjeta == 0 ? $numTarjeta = null : $numTarjeta = $numTarjeta;

            $temporalPos = TemporalPos::where('TemporalPos', 1)
                ->first();

            if ($idTipoPago == 2) {

                $cliente = Empleado::with('LimiteCredito')
                    ->where('NumNomina', $temporalPos->NumNomina)
                    ->first();

                // compras a credito del empleado que no han sido pagadas
                $gastoEmpleado = VentaCreditoEmpleado::where('NumNomina', $temporalPos->NumNomina)
                    ->sum('CreditoActual');

                // pago parcial del empleado -> credito
                $pagoParcial = DatTipoPago::where('IdEncabezado', $idEncabezado)
                    ->where('IdTipoPago', 2)
                    ->sum('Pago');

                $creditoDisponible = ($cliente->LimiteCredito->Limite - $gastoEmpleado) - $pagoParcial;

                $date1 = new DateTime($cliente->Fecha_Ingreso);
                $date2 = new DateTime(date('Y-m-d'));
                $diff = $date1->diff($date2);

                $diasTrabajados = $diff->days;

                if ($diasTrabajados <= 30) {
                    return redirect()->route('Pos')->with('Pos', 'El Empleado no puede llevar a Crédito, al no tener más de 30 dias trabajados. Dias Trabajados: ' . $diasTrabajados);
                }

                if ($pago > $creditoDisponible) {
                    return redirect()->route('Pos')->with('Pos', 'Crédito Insuficiente, Verifique el Crédito Disponible del Empleado!');
                }
            }

            if ($idTipoPago == 7) {
                $monederoExist = TemporalPos::where('TemporalPos', 1)
                    ->sum('MonederoDescuento');

                TemporalPos::where('TemporalPos', 1)
                    ->update([
                        'MonederoDescuento' => $monederoExist + $pago,
                    ]);
            }

            DatTipoPago::insert([
                'IdEncabezado' => $idEncabezado,
                'IdTipoPago' => $idTipoPago,
                'Pago' => $pago,
                'Restante' => $restante,
                'IdBanco' => $idBanco,
                'NumTarjeta' => $numTarjeta,
            ]);

            if ($restante >= 0) {
                $idTienda = Auth::user()->usuarioTienda->IdTienda;

                PreventaTmp::where('IdTienda', $idTienda)
                    ->delete();

                TemporalPos::where('TemporalPos', 1)
                    ->update([
                        'NumNomina' => null,
                        'IdEncabezado' => null,
                        'MonederoDescuento' => null,
                    ]);

                //Descontar Inventario
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

                //Descontar Monedero Si Uso Para Pagar
                if (!empty($temporalPos->NumNomina) && !empty($temporalPos->MonederoDescuento)) {
                    $pagoMonedero = $temporalPos->MonederoDescuento;

                    $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $temporalPos->NumNomina)
                        ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                        ->orderBy('FechaExpiracion')
                        ->get();

                    // generar batchGasto
                    $countBatch = DatMonederoAcumulado::max('IdDatMonedero') + 1;

                    $numCaja = DB::table('DatCajas as a')
                        ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                        ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('a.Activa', 0)
                        ->where('a.Status', 0)
                        ->value('NumCaja');

                    $batchGasto = Auth::user()->usuarioTienda->IdTienda . $numCaja . $countBatch; // batchGasto

                    //Consultar Catalogo de Monedero
                    $monederoE = MonederoElectronico::where('Status', 0)
                        ->first();

                    $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
                    $fechaExpiracion = date('d-m-Y', $fecha);

                    DatMonederoAcumulado::insert([
                        'IdEncabezado' => $idEncabezado,
                        'NumNomina' => $temporalPos->NumNomina,
                        'FechaExpiracion' => $fechaExpiracion,
                        'FechaGenerado' => date('d-m-Y H:i:s'),
                        'Monedero' => -$pagoMonedero,
                        'BatchGasto' => $batchGasto,
                        'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
                    ]);

                    MovimientoMonederoElectronico::insert([
                        'NumNomina' => $temporalPos->NumNomina,
                        'IdEncabezado' => $idEncabezado,
                        'FechaMovimiento' => date('d-m-Y H:i:s'),
                        'Monedero' => -$pagoMonedero,
                        'BatchGasto' => $batchGasto,
                    ]);
                }

                //Consultar Catalogo de Monedero
                $monederoE = MonederoElectronico::where('Status', 0)
                    ->first();

                $importeProcesado = DB::table('DatDetalle as a')
                    ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
                    ->where('a.IdEncabezado', $idEncabezado)
                    ->where('b.IdGrupo', $monederoE->IdGrupo)
                    ->sum('a.ImporteArticulo');

                // guardar monedero, si genero el empleado
                if (!empty($temporalPos->NumNomina) && $importeProcesado >= $monederoE->MonederoMultiplo) {
                    $puntosGenerados = $importeProcesado / $monederoE->MonederoMultiplo;
                    $puntosTotales = intval($puntosGenerados);

                    $monederoGenerado = $puntosTotales * $monederoE->PesosPorMultiplo;

                    $fecha = strtotime(date('Y-m-d') . "+ " . $monederoE->VigenciaMonedero . " days");
                    $fechaExpiracion = date('d-m-Y', $fecha);

                    $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', $temporalPos->NumNomina)
                        ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                        ->sum('Monedero');

                    MovimientoMonederoElectronico::insert([
                        'NumNomina' => $temporalPos->NumNomina,
                        'IdEncabezado' => $idEncabezado,
                        'FechaMovimiento' => date('d-m-Y H:i:s'),
                        'Monedero' => $monederoGenerado,
                    ]);

                    $faltanteMaximo = $monederoE->MaximoAcumulado - $monederoEmpleado;
                    $monederoGenerado + $monederoEmpleado > $monederoE->MaximoAcumulado ? $monederoGenerado = $faltanteMaximo : $monederoGenerado = $monederoGenerado;

                    DatMonederoAcumulado::insert([
                        'IdEncabezado' => $idEncabezado,
                        'NumNomina' => $temporalPos->NumNomina,
                        'FechaExpiracion' => $fechaExpiracion,
                        'FechaGenerado' => date('d-m-Y H:i:s'),
                        'Monedero' => $monederoGenerado,
                        'IDTIENDA' => Auth::user()->usuarioTienda->IdTienda,
                    ]);
                }

                //subir venta
                DatEncabezado::where('IdEncabezado', $idEncabezado)
                    ->update([
                        'Subir' => 0,
                    ]);

                if (!empty($temporalPos->NumNomina)) {

                    $pagoCredito = DatTipoPago::where('IdEncabezado', $idEncabezado)
                        ->where('IdTipoPago', 2)
                        ->sum('Pago');

                    // guardar numero de ventas e importe del credito
                    DB::statement("exec Sp_Guardar_DatConcenVenta " . Auth::user()->usuarioTienda->IdTienda . ", '" . $idEncabezado . "', " . $temporalPos->NumNomina . ", '" . date('d-m-Y') . "', " . $pagoCredito . ", 1");
                }

                // imprimir ticket venta
                DB::select("exec SP_GENERAR_TICKET_CORTE " . $idEncabezado . ", " . $idTienda . ", '" . date('d-m-Y H:i:s') . "'");
                DB::commit();

                return redirect()->route('ImprimirTicketVenta', compact('idEncabezado', 'restante', 'pago'));
            } else {
                TemporalPos::where('TemporalPos', 1)
                    ->update([
                        'IdEncabezado' => $idEncabezado,
                    ]);

                DB::commit();

                return redirect()->route('Pos');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('Pos', 'Error: ' . $th->getMessage());
        }
    }

    public function CorteDiario(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $idDatCaja = DatCaja::where('IdTienda', $idTienda)
            ->where('Status', 0)
            ->where('Activa', 0)
            ->value('IdDatCajas');

        $fecha = $request->fecha;
        empty($fecha) ? $fecha = date('Y-m-d') : $fecha = $fecha;

        $billsTo = CorteTienda::where('IdTienda', $idTienda)
            ->distinct('Bill_To')
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->whereNull('IdSolicitudFactura')
            ->pluck('Bill_To');

        $cortesTienda = ClienteCloudTienda::with([
            'Customer',
            'CorteTienda' => function ($query) use ($fecha, $idTienda) {
                $query->where('DatCortesTienda.IdTienda', $idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    ->whereDate('FechaVenta', $fecha)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            }
        ])
            ->where('IdTienda', $idTienda)
            ->select('IdClienteCloud', 'Bill_To', 'IdListaPrecio', 'IdTipoNomina')
            ->distinct('Bill_To')
            ->whereIn('Bill_To', $billsTo)
            ->get();

        $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', 7)
            ->where('IdListaPrecio', 4)
            ->where('b.TipoNomina', 4)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $totalMonederoSemanal = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', 7)
            ->where('IdListaPrecio', 4)
            ->where('b.TipoNomina', 3)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', 5)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', 4)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', 1)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $creditoQuincenal = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->whereIn('IdTipoPago', [2])
            ->where('TipoNomina', 4)
            ->sum('ImporteArticulo');

        $creditoSemanal = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->whereIn('IdTipoPago', [2])
            ->where('TipoNomina', 3)
            ->sum('ImporteArticulo');

        $totalTransferencia = DB::table('DatCortesTienda as a')
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->where('IdTipoPago', 3)
            ->sum('ImporteArticulo');

        $totalFactura = CorteTienda::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->whereNotNull('IdSolicitudFactura')
            ->sum('ImporteArticulo');

        $facturas = SolicitudFactura::with(['Factura' => function ($query) {
            $query->whereNotNull('DatCortesTienda.IdSolicitudFactura');
        }])
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaSolicitud', $fecha)
            ->get();

        //return $facturas;

        return view('Posweb.CorteDiario', compact(
            'tienda',
            'cortesTienda',
            'fecha',
            'totalEfectivo',
            'facturas',
            'creditoQuincenal',
            'creditoSemanal',
            'totalTarjetaDebito',
            'totalTarjetaCredito',
            'totalTransferencia',
            'totalFactura',
            'totalMonederoQuincenal',
            'totalMonederoSemanal',
            'idDatCaja'
        ));
    }

    public function GenerarCortePDF($fecha, $idTienda, $idDatCaja)
    {
        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $numCaja = DatCaja::where('IdDatCajas', $idDatCaja)
            ->value('IdCaja');

        if ($idDatCaja == 0) {
            $billsTo = CorteTienda::where('IdTienda', $idTienda)
                ->distinct('Bill_To')
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereNull('IdSolicitudFactura')
                ->pluck('Bill_To');

            $cortesTienda = ClienteCloudTienda::with(['Customer', 'CorteTienda' => function ($query) use ($fecha, $idTienda) {
                $query->where('DatCortesTienda.IdTienda', $idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    ->whereDate('DatCortesTienda.FechaVenta', $fecha)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->select('IdClienteCloud', 'Bill_To', 'IdListaPrecio', 'IdTipoNomina')
                ->distinct('Bill_To')
                ->whereIn('Bill_To', $billsTo)
                ->get();

            $facturas = SolicitudFactura::with(['Factura' => function ($query) {
                $query->whereNotNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaSolicitud', $fecha)
                ->get();

            $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 5)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 4)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 1)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $creditoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 4)
                ->sum('ImporteArticulo');

            $creditoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 3)
                ->sum('ImporteArticulo');

            $totalTransferencia = DB::table('DatCortesTienda as a')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdTipoPago', 3)
                ->sum('ImporteArticulo');

            $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereNotNull('IdSolicitudFactura')
                ->sum('ImporteArticulo');

            $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 4)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalMonederoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 3)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $info = [
                'titulo' => 'Corte Diario de Tienda',
                'nomTienda' => $tienda->NomTienda,
                'numCaja' => $numCaja,
                'fecha' => strftime("%d %B del %Y", strtotime($fecha)),
                'cortesTienda' => $cortesTienda,
                'facturas' => $facturas,
                'totalTarjetaDebito' => $totalTarjetaDebito,
                'totalTarjetaCredito' => $totalTarjetaCredito,
                'totalEfectivo' => $totalEfectivo,
                'creditoQuincenal' => $creditoQuincenal,
                'creditoSemanal' => $creditoSemanal,
                'totalTransferencia' => $totalTransferencia,
                'totalFactura' => $totalFactura,
                'totalMonederoQuincenal' => $totalMonederoQuincenal,
                'totalMonederoSemanal' => $totalMonederoSemanal,
            ];
        } else {
            $billsTo = CorteTienda::where('IdTienda', $idTienda)
                ->distinct('Bill_To')
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->whereNull('IdSolicitudFactura')
                ->pluck('Bill_To');

            $cortesTienda = ClienteCloudTienda::with(['Customer', 'CorteTienda' => function ($query) use ($fecha, $idTienda, $idDatCaja) {
                $query->where('DatCortesTienda.IdTienda', $idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    ->where('DatCortesTienda.IdDatCaja', $idDatCaja)
                    ->whereDate('DatCortesTienda.FechaVenta', $fecha)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->select('IdClienteCloud', 'Bill_To', 'IdListaPrecio', 'IdTipoNomina')
                ->distinct('Bill_To')
                ->whereIn('Bill_To', $billsTo)
                ->get();

            $facturas = SolicitudFactura::with(['Factura' => function ($query) use ($idDatCaja) {
                $query->whereNotNull('DatCortesTienda.IdSolicitudFactura')
                    ->where('DatCortesTienda.IdDatCaja', $idDatCaja);
            }])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaSolicitud', $fecha)
                ->get();

            $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 5)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 4)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 1)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $creditoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 4)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $creditoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 3)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalTransferencia = DB::table('DatCortesTienda as a')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdTipoPago', 3)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->whereNotNull('IdSolicitudFactura')
                ->sum('ImporteArticulo');

            $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 4)
                ->where('StatusVenta', 0)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalMonederoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 3)
                ->where('a.IdDatCaja', $idDatCaja)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $info = [
                'titulo' => 'Corte Diario de Tienda',
                'nomTienda' => $tienda->NomTienda,
                'numCaja' => $numCaja,
                'fecha' => strftime("%d %B del %Y", strtotime($fecha)),
                'cortesTienda' => $cortesTienda,
                'facturas' => $facturas,
                'totalTarjetaDebito' => $totalTarjetaDebito,
                'totalTarjetaCredito' => $totalTarjetaCredito,
                'totalEfectivo' => $totalEfectivo,
                'creditoQuincenal' => $creditoQuincenal,
                'creditoSemanal' => $creditoSemanal,
                'totalTransferencia' => $totalTransferencia,
                'totalFactura' => $totalFactura,
                'totalMonederoQuincenal' => $totalMonederoQuincenal,
                'totalMonederoSemanal' => $totalMonederoSemanal,
            ];
        }

        //return $info;

        view()->share('GenerarCortePDF', $info);
        $pdf = PDF::loadView('Posweb.GenerarCortePDF', $info);
        return $pdf->stream('Corte ' . $fecha . ' ' . $tienda->NomTienda . ' Caja ' . $numCaja . '.pdf');
    }

    public function ImprimirTicketVenta($idEncabezado, $restante, $pago)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = DB::table('CatTiendas as a')
            ->leftJoin('CatCiudades as b', 'b.IdCiudad', 'a.IdCiudad')
            ->leftJoin('CatEstados as c', 'c.IdEstado', 'b.IdEstado')
            ->where('a.IdTienda', $idTienda)
            ->first();

        $venta = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->where('IdTienda', $idTienda)
            ->where('a.IdEncabezado', $idEncabezado)
            ->get();

        $encabezado = DatEncabezado::where('IdEncabezado', $idEncabezado)
            ->where('IdTienda', $idTienda)
            ->first();

        $datTipoPago = DB::table('DatTipoPago as a')
            ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
            ->where('a.IdEncabezado', $idEncabezado)
            ->get();

        $firmaEmpleado = DB::table('DatTipoPago')
            ->where('IdEncabezado', $encabezado->IdEncabezado)
            ->whereIn('IdTipoPago', [2])
            ->get();

        $empleado = DB::table('CatEmpleados')
            ->where('NumNomina', $encabezado->NumNomina)
            ->first();

        $frecuenteSocio = DB::table('CatFrecuentesSocios')
            ->where('FolioViejo', $encabezado->NumNomina)
            ->first();

        if (!empty($empleado) || !empty($frecuenteSocio)) {
            $datMonedero = DatMonederoAcumulado::where('IdEncabezado', $encabezado->IdEncabezado)
                ->where('Monedero', '>', 0)
                ->sum('Monedero');

            $vigenciaMonedero = DatMonederoAcumulado::where('IdEncabezado', $encabezado->IdEncabezado)
                ->where('Monedero', '>', 0)
                ->value('FechaExpiracion');

            $monederoAcumulado = DatMonederoAcumulado::where('NumNomina', $encabezado->NumNomina)
                ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                ->sum('Monedero');
        }

        $caja = DB::table('DatCajas as a')
            ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
            ->where('IdTienda', $idTienda)
            ->where('a.Activa', 0)
            ->where('a.Status', 0)
            ->first();

        $n = explode(' ', empty(Auth::user()->Empleado->Nombre) ? 'Nomina' : Auth::user()->Empleado->Nombre);
        $a = explode(' ', empty(Auth::user()->Empleado->Apellidos) ? 'Vacio' : Auth::user()->Empleado->Apellidos);
        $nombre = $n[0];
        $apellido = $a[0];

        try {
            $logoKowi = EscposImage::load("img/printLogoKowi.png");

            $nombreImpresora = "PosWeb2";
            $connector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($connector);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->bitImage($logoKowi);
            $impresora->feed(1);
            $impresora->text("ALIMENTOS KOWI SA DE CV\n");
            $impresora->text("AKO971007558\n");
            $impresora->text("CARRETERA FEDERAL MEXICO-NOGALES KM 1788\n");
            $impresora->text("NAVOJOA, SONORA C.P. 85230\n");
            $impresora->text("EXPEDIDO EN:\n");
            $impresora->text($tienda->NomTienda . "\n");
            $impresora->text($tienda->Direccion . "\n");
            $impresora->text($tienda->NomCiudad . ", " . $tienda->NomEstado . "\n");
            $impresora->text($tienda->Telefono . "\n");
            $impresora->text("==========================================\n");
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("FECHA: " . date('d/m/Y H:i:s', strtotime($encabezado->FechaVenta)) . "\n");
            $impresora->text("TICKET: " . $encabezado->IdTicket . "\n");
            $impresora->text("ARTICULOS: " . $venta->count() . "\n");
            $impresora->text("CAJA: " . $caja->NumCaja . "\n");
            $impresora->text("CAJERO: " . $nombre . " " . $apellido . "\n");
            $impresora->text("==========================================\n");
            if (!empty($encabezado->NumNomina)) {
                if (!empty($empleado)) {
                    $impresora->text($empleado->NumNomina . " " . $empleado->Nombre . " " . $empleado->Apellidos . "\n");
                }
                if (!empty($frecuenteSocio)) {
                    $impresora->text($frecuenteSocio->FolioViejo . " " . $frecuenteSocio->Nombre . "\n");
                }

                $impresora->text("==========================================\n");
            }
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("ARTICULO         CANT    PRECIO  IMPORTE\n");
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($venta as $index => $datDetalleVenta) {
                $impresora->text(str_pad(substr($datDetalleVenta->NomArticulo, 0, 16), 16) . " " . str_pad(number_format($datDetalleVenta->CantArticulo, 3), 7) . " " . str_pad(number_format($datDetalleVenta->PrecioArticulo, 2), 7) . " " . number_format($datDetalleVenta->ImporteArticulo, 2) . "\n");
            }
            $impresora->text("==========================================\n");
            $impresora->feed(1);
            $impresora->setJustification(Printer::JUSTIFY_RIGHT);
            $impresora->text("SUBTOTAL : " . str_pad(number_format($encabezado->SubTotal, 2), 9, " ", STR_PAD_LEFT) . "\n");
            $impresora->text("IVA : " . str_pad(number_format($encabezado->Iva, 2), 9, " ", STR_PAD_LEFT) . "\n");
            foreach ($datTipoPago as $key => $pago) {
                $impresora->text($pago->NomTipoPago . " : " . str_pad(number_format($pago->Pago, 2), 9, " ", STR_PAD_LEFT) . "\n");
            }
            $impresora->text("CAMBIO : " . str_pad($restante, 9, " ", STR_PAD_LEFT) . "\n");
            $impresora->text("================\n");
            $impresora->text("TOTAL " . number_format($encabezado->ImporteVenta, 2) . "\n");
            $impresora->text("================\n");
            $impresora->feed(2);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            if (!empty($empleado) || !empty($frecuenteSocio)) {
                if ($datMonedero > 0) {
                    $impresora->text("**GENERÓ $" . number_format($datMonedero, 2) . " EN MONEDERO ELECTRÓNICO**\n");
                    $impresora->text("Monedero Válido Hasta: " . date('d/m/Y', strtotime($vigenciaMonedero)) . "\n");
                }
                if ($monederoAcumulado > 0) {
                    $impresora->text("**MONEDERO ACUMULADO: $" . number_format($monederoAcumulado, 2) . "**");
                }
            }
            $impresora->feed(1);
            if ($firmaEmpleado->count() > 0) {
                $impresora->feed(1);
                $impresora->text("Firma del Empleado\n");
                $impresora->feed(1);
                $impresora->text("______________________________________\n");
                $impresora->text("" . $empleado->NumNomina . "\n");
                $impresora->text("" . $empleado->Nombre . " " . $empleado->Apellidos . "\n");
            }
            $impresora->feed(2);
            $impresora->text("********************************\n");
            $impresora->text("FOLIO CUPÓN: " . $idEncabezado . "\n");
            $impresora->text("********************************\n");
            $impresora->feed(2);
            $impresora->text("¡ALTA CALIDAD EN CARNE DE CERDO!\n");
            $impresora->text("WWW.KOWI.COM.MX\n");
            $impresora->text("¡GRACIAS POR SU COMPRA!\n");
            //$impresora->feed(2);
            $impresora->cut();
            $impresora->pulse();
            $impresora->close();
        } catch (\Throwable $th) {
            return redirect('Pos')->with('Cambio', $restante);
        }

        return redirect('Pos')->with('Cambio', $restante);
    }

    public function ReimprimirTicket(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $fechaHoy = date('Y-m-d');

        $idTicket = $request->idTicket;

        $fecha = $request->fechaVenta;

        return view('Posweb.ReimprimirTicket', compact('tienda', 'fechaHoy', 'idTicket', 'fecha'));
    }

    public function ImprimirTicket(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $idTicket = $request->txtIdTicket;

        $fechaVenta = $request->txtFecha;

        $tienda = DB::table('CatTiendas as a')
            ->leftJoin('CatCiudades as b', 'b.IdCiudad', 'a.IdCiudad')
            ->leftJoin('CatEstados as c', 'c.IdEstado', 'b.IdEstado')
            ->where('IdTienda', $idTienda)
            ->first();

        $ticket = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->where('a.IdTienda', $idTienda)
            ->whereDate('a.FechaVenta', $fechaVenta)
            ->where('a.IdTicket', $idTicket)
            ->get();

        $encabezado = DatEncabezado::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fechaVenta)
            ->where('IdTicket', $idTicket)
            ->first();

        if (empty($encabezado) || empty($ticket)) {
            return redirect()->route('ReimprimirTicket', compact('idTicket', 'fechaVenta'))->with('msjdelete', 'No se Encontro el Ticket Solicitado!');
        }

        $datTipoPago = DB::table('DatTipoPago as a')
            ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
            ->where('a.IdEncabezado', $encabezado->IdEncabezado)
            ->get();

        $firmaEmpleado = DB::table('DatTipoPago')
            ->where('IdEncabezado', $encabezado->IdEncabezado)
            ->whereIn('IdTipoPago', [2])
            ->get();

        $empleado = DB::table('CatEmpleados')
            ->where('NumNomina', $encabezado->NumNomina)
            ->first();

        $frecuenteSocio = DB::table('CatFrecuentesSocios')
            ->where('FolioViejo', $encabezado->NumNomina)
            ->first();

        if (!empty($empleado) || !empty($frecuenteSocio)) {
            $datMonedero = DatMonederoAcumulado::where('IdEncabezado', $encabezado->IdEncabezado)
                ->where('Monedero', '>', 0)
                ->sum('Monedero');

            $vigenciaMonedero = DatMonederoAcumulado::where('IdEncabezado', $encabezado->IdEncabezado)
                ->where('Monedero', '>', 0)
                ->value('FechaExpiracion');

            $monederoAcumulado = DatMonederoAcumulado::where('NumNomina', $encabezado->NumNomina)
                ->whereRaw("'" . date('Y-m-d') . "' <= cast(FechaExpiracion as date)")
                ->sum('Monedero');
        }

        $caja = DB::table('DatCajas as a')
            ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
            ->where('IdTienda', $idTienda)
            ->where('a.Activa', 0)
            ->where('a.Status', 0)
            ->first();

        $n = explode(' ', empty(Auth::user()->Empleado->Nombre) ? 'Nomina' : Auth::user()->Empleado->Nombre);
        $a = explode(' ', empty(Auth::user()->Empleado->Apellidos) ? 'Vacio' : Auth::user()->Empleado->Apellidos);
        $nombre = $n[0];
        $apellido = $a[0];

        $cambio = DatTipoPago::where('IdEncabezado', $encabezado->IdEncabezado)
            ->where('Restante', '>=', 0)
            ->first();

        $logoKowi = EscposImage::load("img/printLogoKowi.png");

        $nombreImpresora = "PosWeb2";
        $connector = new WindowsPrintConnector($nombreImpresora);
        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->bitImage($logoKowi);
        $impresora->feed(1);
        $impresora->text("ALIMENTOS KOWI SA DE CV\n");
        $impresora->text("AKO971007558\n");
        $impresora->text("CARRETERA FEDERAL MEXICO-NOGALES KM 1788\n");
        $impresora->text("NAVOJOA, SONORA C.P. 85230\n");
        $impresora->text("EXPEDIDO EN:\n");
        $impresora->text($tienda->NomTienda . "\n");
        $impresora->text($tienda->Direccion . "\n");
        $impresora->text($tienda->NomCiudad . ", " . $tienda->NomEstado . "\n");
        $impresora->text($tienda->Telefono . "\n");
        $impresora->text("==========================================\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("FECHA: " . date('d/m/Y H:i:s', strtotime($encabezado->FechaVenta)) . "\n");
        $impresora->text("TICKET: " . $encabezado->IdTicket . "\n");
        $impresora->text("ARTICULOS: " . $ticket->count() . "\n");
        $impresora->text("CAJA: " . $caja->NumCaja . "\n");
        $impresora->text("CAJERO: " . $nombre . " " . $apellido . "\n");
        $impresora->text("==========================================\n");
        if (!empty($encabezado->NumNomina)) {
            if (!empty($empleado)) {
                $impresora->text($empleado->NumNomina . " " . $empleado->Nombre . " " . $empleado->Apellidos . "\n");
            }
            if (!empty($frecuenteSocio)) {
                $impresora->text($frecuenteSocio->FolioViejo . " " . $frecuenteSocio->Nombre . "\n");
            }

            $impresora->text("==========================================\n");
        }
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("ARTICULO         CANT    PRECIO  IMPORTE\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        foreach ($ticket as $index => $datDetalleVenta) {
            $impresora->text(str_pad(substr($datDetalleVenta->NomArticulo, 0, 16), 16) . " " . str_pad(number_format($datDetalleVenta->CantArticulo, 3), 7) . " " . str_pad(number_format($datDetalleVenta->PrecioArticulo, 2), 7) . " " . number_format($datDetalleVenta->ImporteArticulo, 2) . "\n");
        }
        $impresora->text("==========================================\n");
        $impresora->feed(1);
        $impresora->setJustification(Printer::JUSTIFY_RIGHT);
        $impresora->text("SUBTOTAL : " . str_pad(number_format($encabezado->SubTotal, 2), 9, " ", STR_PAD_LEFT) . "\n");
        $impresora->text("IVA : " . str_pad(number_format($encabezado->Iva, 2), 9, " ", STR_PAD_LEFT) . "\n");
        foreach ($datTipoPago as $key => $pago) {
            $impresora->text($pago->NomTipoPago . " : " . str_pad(number_format($pago->Pago, 2), 9, " ", STR_PAD_LEFT) . "\n");
        }
        $impresora->text("CAMBIO : " . str_pad(number_format($cambio->Restante, 2), 9, " ", STR_PAD_LEFT) . "\n");
        $impresora->text("================\n");
        $impresora->text("TOTAL " . number_format($encabezado->ImporteVenta, 2) . "\n");
        $impresora->text("================\n");
        $impresora->feed(2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        if (!empty($empleado) || !empty($frecuenteSocio)) {
            if ($datMonedero > 0) {
                $impresora->text("**GENERÓ $" . number_format($datMonedero, 2) . " EN MONEDERO ELECTRÓNICO**\n");
                $impresora->text("Monedero Válido Hasta: " . date('d/m/Y', strtotime($vigenciaMonedero)) . "\n");
            }
            if ($monederoAcumulado > 0) {
                $impresora->text("**MONEDERO ACUMULADO: $" . number_format($monederoAcumulado, 2) . "**");
            }
        }
        $impresora->feed(1);
        //$impresora->setJustification(Printer::JUSTIFY_CENTER);
        if ($firmaEmpleado->count() > 0) {
            $impresora->feed(1);
            $impresora->text("Firma del Empleado\n");
            $impresora->feed(1);
            $impresora->text("______________________________________\n");
            $impresora->text("" . $empleado->NumNomina . "\n");
            $impresora->text("" . $empleado->Nombre . " " . $empleado->Apellidos . "\n");
        }
        $impresora->feed(2);
        $impresora->text("********************************\n");
        $impresora->text("FOLIO CUPÓN: " . $encabezado->IdEncabezado . "\n");
        $impresora->text("********************************\n");
        $impresora->feed(2);
        $impresora->text("¡ALTA CALIDAD EN CARNE DE CERDO!\n");
        $impresora->text("WWW.KOWI.COM.MX\n");
        $impresora->text("¡GRACIAS POR SU COMPRA!\n");
        //$impresora->feed(2);
        $impresora->cut();
        $impresora->pulse();
        $impresora->close();

        return redirect()->route('ReimprimirTicket', compact('idTicket', 'fechaVenta'))->with('msjAdd', 'Se Imprimio el Ticket: ' . $idTicket);
    }

    public function VentaTicketDiario(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $fecha = $request->txtFecha;

        empty($fecha) ? $fecha = date('Y-m-d') : $fecha = $fecha;

        $tickets = DatEncabezado::with(['detalle' => function ($detalle) {
            $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
        }, 'TipoPago', 'SolicitudFactura'])
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->orderBy('IdTicket')
            ->get();

        $total = DatEncabezado::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->sum('ImporteVenta');

        $totalIva = DatEncabezado::where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->sum('Iva');

        //return $tickets;

        return view('Posweb.VentaTicketDiario', compact('tienda', 'tickets', 'fecha', 'total', 'totalIva'));
    }

    public function ConcentradoVentas(Request $request)
    {
        $tienda = Tienda::find(Auth::user()->usuarioTienda->IdTienda);

        empty($request->fecha1) ? $fecha1 = date('Y-m-d') : $fecha1 = $request->fecha1;
        empty($request->fecha2) ? $fecha2 = date('Y-m-d') : $fecha2 = $request->fecha2;

        $concentrado = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->select(DB::raw('c.CodArticulo, c.NomArticulo, SUM(b.CantArticulo) as Peso,
                                b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
            ->where('a.IdTienda', $tienda->IdTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo')
            ->orderBy('c.CodArticulo')
            ->get();

        $totalPeso = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->where('a.IdTienda', $tienda->IdTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->sum('b.CantArticulo');

        $totalImporte = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->where('a.IdTienda', $tienda->IdTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->sum('b.ImporteArticulo');

        $totalIva = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->where('a.IdTienda', $tienda->IdTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->sum('b.IvaArticulo');

        //return $totalIva;

        return view('Posweb.ConcentradoVentas', compact('tienda', 'concentrado', 'totalPeso', 'totalImporte', 'totalIva', 'fecha1', 'fecha2'));
    }

    public function VentaPorGrupo(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::find($idTienda);

        $grupos = Grupo::all();

        empty($request->fecha1) ? $fecha1 = date('Y-m-d') : $fecha1 = $request->fecha1;
        empty($request->fecha2) ? $fecha2 = date('Y-m-d') : $fecha2 = $request->fecha2;
        empty($request->idGrupo) ? $idGrupo = 1 : $idGrupo = $request->idGrupo;

        $agrupado = $request->agrupado;

        if ($agrupado == 'on') {
            $ventasPorGrupo = DB::table('DatEncabezado as a')
                ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
                ->select(DB::raw('c.CodArticulo, c.NomArticulo, SUM(b.CantArticulo) as CantArticulo,
                                b.PrecioArticulo, SUM(b.ImporteArticulo) as ImporteArticulo'))
                ->where('a.IdTienda', $idTienda)
                ->where('a.StatusVenta', 0)
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('c.IdGrupo', $idGrupo)
                ->groupBy('c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo')
                ->orderBy('c.CodArticulo')
                ->get();
        } else {
            $ventasPorGrupo = DB::table('DatEncabezado as a')
                ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
                ->select('c.CodArticulo', 'C.NomArticulo', 'b.CantArticulo', 'b.PrecioArticulo', 'b.ImporteArticulo')
                ->where('IdTienda', $idTienda)
                ->where('a.StatusVenta', 0)
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('c.IdGrupo', $idGrupo)
                ->orderBy('c.CodArticulo')
                ->get();
        }

        $totalPeso = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->select('c.CodArticulo', 'C.NomArticulo', 'b.CantArticulo', 'b.PrecioArticulo', 'b.ImporteArticulo')
            ->where('IdTienda', $idTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->where('c.IdGrupo', $idGrupo)
            ->orderBy('c.CodArticulo')
            ->sum('b.CantArticulo');

        $totalImporte = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->select('c.CodArticulo', 'C.NomArticulo', 'b.CantArticulo', 'b.PrecioArticulo', 'b.ImporteArticulo')
            ->where('IdTienda', $idTienda)
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->where('c.IdGrupo', $idGrupo)
            ->orderBy('c.CodArticulo')
            ->sum('b.ImporteArticulo');

        //return $totalPeso;

        return view('Posweb.VentaPorGrupo', compact('grupos', 'ventasPorGrupo', 'fecha1', 'fecha2', 'idGrupo', 'agrupado', 'tienda', 'totalPeso', 'totalImporte'));
    }

    public function ReporteVentasListaPrecio(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $idsListaPrecio = ListaPrecioTienda::where('IdTienda', $idTienda)
            ->pluck('IdListaPrecio');

        $listasPrecio = ListaPrecio::whereIn('IdListaPrecio', $idsListaPrecio)
            ->get();

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idListaPrecio = empty($request->idListaPrecio) ? 0 : $request->idListaPrecio;

        try {
            DB::beginTransaction();

            $query = "select d.CodArticulo, d.NomArticulo, SUM(b.CantArticulo) as CantArticulo," .
                " b.PrecioArticulo, SUM(b.IvaArticulo) as IvaArticulo, SUM(b.ImporteArticulo) as ImporteArticulo" .
                " from DatEncabezado as a" .
                " left join DatDetalle as b on b.IdEncabezado=a.IdEncabezado" .
                " left join CatListasPrecio as c on c.IdListaPrecio=b.IdListaPrecio" .
                " left join CatArticulos as d on d.IdArticulo=b.IdArticulo" .
                " where CAST(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'" .
                " and a.IdTienda = " . $idTienda . " " .
                " and b.IdListaPrecio = " . $idListaPrecio . "" .
                " and a.StatusVenta = 0" .
                " group by d.CodArticulo, d.NomArticulo, b.PrecioArticulo";

            $ventas = DB::select($query);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('ReporteVentasListaPrecio')->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        return view('Posweb.ReporteVentasListaPrecio', compact('listasPrecio', 'ventas', 'fecha1', 'fecha2', 'idListaPrecio'));
    }

    public function PaquetesPreventa(Request $request)
    {
        $idPaquete = $request->idPaquete;

        try {
            DB::beginTransaction();

            $paquete = DB::table('CatPaquetes as a')
                ->leftJoin('DatPaquetes as b', 'b.IdPaquete', 'a.IdPaquete')
                ->where('a.IdPaquete', $idPaquete)
                ->get();

            foreach ($paquete as $key => $articuloPaquete) {

                $articulo = Articulo::where('CodArticulo', $articuloPaquete->CodArticulo)
                    ->where('Status', 0)
                    ->first();

                //Consultar Inventario de la Tienda
                $cantVentaTmp = PreventaTmp::where('IdTienda', Auth::user()->UsuarioTienda->IdTienda)
                    ->where('IdArticulo', $articulo->IdArticulo)
                    ->sum('CantArticulo');

                $pesoAcumulado = $cantVentaTmp + $articuloPaquete->CantArticulo;

                $stockArticulo = InventarioTienda::where('IdTienda', Auth::user()->UsuarioTienda->IdTienda)
                    ->where('CodArticulo', $articulo->CodArticulo)
                    ->sum('StockArticulo');

                if ($stockArticulo < $pesoAcumulado) {
                    return redirect()->route('Pos')->with('Pos', 'Inventario Insuficiente Para el Articulo: ' . $articulo->NomArticulo);
                }

                $iva = 0;
                if ($articulo->Iva == 0) {
                    $idsListaPrecio = ListaPrecioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->pluck('IdListaPrecio');

                    $porcentajeIva = ListaPrecio::whereIn('IdListaPrecio', $idsListaPrecio)
                        ->where('IdListaPrecio', '<>', 4)
                        ->whereRaw($articuloPaquete->CantArticulo . 'between PesoMinimo and PesoMaximo')
                        ->value('PorcentajeIva');

                    $iva = $articuloPaquete->ImporteArticulo * $porcentajeIva;
                }

                $idDatVentaTmp = PreventaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->max('IdDatVentaTmp') + 1;

                // buscar empleado, socio o frecuente para lista de precios EMPYSOC
                $numNomina = TemporalPos::where('TemporalPos', 1)
                    ->value('NumNomina');

                PreventaTmp::insert([
                    'IdDatVentaTmp' => $idDatVentaTmp,
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'IdArticulo' => $articulo->IdArticulo,
                    'CantArticulo' => $articuloPaquete->CantArticulo,
                    'PrecioLista' => $articuloPaquete->PrecioArticulo,
                    'PrecioVenta' => $articuloPaquete->PrecioArticulo,
                    'IdListaPrecio' => empty($numNomina) ? $articuloPaquete->IdListaPrecio : 4,
                    'IvaArticulo' => $iva,
                    'SubTotalArticulo' => $articuloPaquete->ImporteArticulo,
                    'ImporteArticulo' => $articuloPaquete->ImporteArticulo + $iva,
                    'IdPedido' => null,
                    'MultiPago' => null,
                    'IdPaquete' => $articuloPaquete->IdPaquete,
                    'Status' => 0,
                    'IdDatPrecios' => null,
                ]);
            }

            DB::commit();
            return redirect('Pos')->with('msjAdd', 'Paquete Enviado A Preventa!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
