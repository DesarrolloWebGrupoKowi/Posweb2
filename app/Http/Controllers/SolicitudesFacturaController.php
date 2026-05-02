<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\Cliente;
use App\Models\SolicitudFactura;
use App\Models\CorteTienda;
use App\Services\TiendaService;
use Illuminate\Support\Facades\DB;

class SolicitudesFacturaController extends Controller
{
    protected $tiendaService;
    protected $tiendasIds;
    protected $tiendas;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;

        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });
    }

    public function VerSolicitudes(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha = $request->fecha;
        $rfc = $request->rfc;
        $nombre = $request->nombre;
        $searchQuery = $request->query('search') ? $request->query('search') : '';
        $searchQuery = $request->search ? $request->search : '';

        $tiendas = $this->tiendas;
        $ids = $this->tiendasIds;

        // Obtener todas las solicitudes
        $solicitudes = SolicitudFactura::select(
            'SolicitudFactura.Id',
            'SolicitudFactura.IdSolicitudFactura',
            'SolicitudFactura.FechaSolicitud',
            'SolicitudFactura.IdEncabezado',
            'SolicitudFactura.IdTienda',
            'CatTiendas.NomTienda',
            'SolicitudFactura.NomCliente',
            'SolicitudFactura.RFC',
            'SolicitudFactura.Email',
            'SolicitudFactura.Status',
            'SolicitudFactura.Editar',
            'SolicitudFactura.MetodoPago',
            'SolicitudFactura.UsoCFDI',
            'SolicitudFactura.RegimenFiscal'
        )
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->when($fecha, function ($query) use ($fecha) {
                $query->whereDate('SolicitudFactura.FechaSolicitud', $fecha);
            })
            // ->when($idSolicitudFactura, function ($query) use ($idSolicitudFactura) {
            //     $query->where('SolicitudFactura.IdSolicitudFactura', $idSolicitudFactura);
            // })
            // ->when($idEncabezado, function ($query) use ($idEncabezado) {
            //     $query->where('SolicitudFactura.IdEncabezado', $idEncabezado);
            // })
            ->when($rfc, function ($query) use ($rfc) {
                $query->where('SolicitudFactura.RFC', $rfc);
            })
            ->when($nombre, function ($query) use ($nombre) {
                $query->where('SolicitudFactura.NomCliente', 'like', '%' . $nombre . '%');
            })
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('SolicitudFactura.IdTienda', $idTienda);
            })
            // ->where('SolicitudFactura.Status', 0)
            ->orderBy('SolicitudFactura.FechaSolicitud', 'DESC')
            ->paginate(10)
            ->appends(request()->query());

        // Obtener todos los IDs de encabezado y solicitud para una sola consulta
        $idsEncabezado = $solicitudes->pluck('IdEncabezado')->toArray();
        $idsSolicitud = $solicitudes->pluck('IdSolicitudFactura')->toArray();

        // Una sola consulta para obtener todos los datos de ventas
        $todasLasVentas = DB::table('DatCortesTienda')
            ->whereIn('IdEncabezado', $idsEncabezado)
            ->whereIn('IdSolicitudFactura', $idsSolicitud)
            ->select(
                'IdEncabezado',
                'IdSolicitudFactura',
                'ImporteArticulo',
                'Source_Transaction_Identifier',
                'FechaVenta',
                'IdCortesTienda'
            )
            ->get()
            ->groupBy(function ($item) {
                return $item->IdEncabezado . '|' . $item->IdSolicitudFactura;
            });

        // Procesar cada solicitud
        foreach ($solicitudes as $solicitud) {
            $key = $solicitud->IdEncabezado . '|' . $solicitud->IdSolicitudFactura;
            $datosVenta = $todasLasVentas->get($key, collect());

            $solicitud->TotalFactura = $datosVenta->sum('ImporteArticulo');
            $solicitud->TotalArticulos = $datosVenta->count();
            $solicitud->PrimeraVenta = $datosVenta->min('FechaVenta');
            $solicitud->UltimaVenta = $datosVenta->max('FechaVenta');

            $sources = $datosVenta
                ->pluck('Source_Transaction_Identifier')
                ->filter(function ($value) {
                    return !is_null($value) && $value !== '';
                })
                ->unique()
                ->toArray();

            $solicitud->Source_Transaction_Identifier = implode(', ', $sources);

            // Obtener datos de la tabla XXKW_HEADERS_IVENTAS para cada Source_Transaction_Identifier
            $interfaceData = collect();
            foreach ($sources as $source) {
                $data = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
                    ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier')
                    ->where('Source_Transaction_Identifier', $source)
                    ->first();

                if ($data) {
                    $interfaceData->push($data);
                }
            }

            // Asignar los datos de interfaz a la solicitud
            $solicitud->InterfaceStatus = $interfaceData->pluck('STATUS')->implode(', ');
            $solicitud->InterfaceMensajeError = $interfaceData->pluck('MENSAJE_ERROR')->implode(' | ');
            $solicitud->InterfaceBatchName = $interfaceData->pluck('Batch_Name')->implode(', ');
            $solicitud->InterfaceTransactionOn = $interfaceData->pluck('Transaction_On')->implode(', ');
            $solicitud->InterfaceSourceTransactionNumber = $interfaceData->pluck('Source_Transaction_Number')->implode(', ');
            $solicitud->InterfaceData = $interfaceData; // Guardar toda la colección si la necesitas
        }

        // return $solicitudes;


        // $solicitudes = SolicitudFactura::select(
        //     'SolicitudFactura.Id',
        //     'SolicitudFactura.IdSolicitudFactura',
        //     'SolicitudFactura.IdEncabezado',
        //     'DatEncabezado.IdTicket',
        //     'DatEncabezado.ImporteVenta',
        //     'CatTiendas.NomTienda',
        //     'SolicitudFactura.FechaSolicitud',
        //     'SolicitudFactura.TipoPersona',
        //     'SolicitudFactura.NomCliente',
        //     'SolicitudFactura.RFC',
        //     'SolicitudFactura.MetodoPago',
        //     'SolicitudFactura.UsoCFDI',
        //     'SolicitudFactura.Status'
        // )
        //     ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'SolicitudFactura.IdEncabezado')
        //     ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
        //     ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
        //     ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
        //     ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
        //     ->where('NomCliente', 'LIKE', '%' . $searchQuery . '%')
        //     ->where(function ($query) {
        //         $query->whereNull('SolicitudFactura.Editar')
        //             ->orWhere('SolicitudFactura.Status', '1');
        //     })
        //     ->whereIn('SolicitudFactura.IdTienda', $ids)
        //     ->where('SolicitudFactura.IdTienda', 'LIKE', $idTienda)
        //     ->groupBy(
        //         'SolicitudFactura.Id',
        //         'SolicitudFactura.IdSolicitudFactura',
        //         'SolicitudFactura.IdEncabezado',
        //         'DatEncabezado.IdTicket',
        //         'DatEncabezado.ImporteVenta',
        //         'CatTiendas.NomTienda',
        //         'SolicitudFactura.FechaSolicitud',
        //         'SolicitudFactura.TipoPersona',
        //         'SolicitudFactura.NomCliente',
        //         'SolicitudFactura.RFC',
        //         'SolicitudFactura.MetodoPago',
        //         'SolicitudFactura.UsoCFDI',
        //         'SolicitudFactura.Status'
        //     )
        //     ->orderBy('SolicitudFactura.FechaSolicitud', 'desc')
        //     ->paginate(10)
        //     ->onEachSide(1);

        $solicitudesPendientes = collect(null);

        // $solicitudesPendientes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco', 'DatEncabezado.IdTicket')
        //     ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'SolicitudFactura.IdEncabezado')
        //     ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
        //     ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
        //     ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
        //     ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
        //     ->where('SolicitudFactura.Status', '0')
        //     ->whereNotNull('SolicitudFactura.Editar')
        //     ->whereIn('SolicitudFactura.IdTienda', $ids)
        //     ->orderBy('SolicitudFactura.FechaSolicitud', 'desc')
        //     ->get();

        return view('SolicitudesFactura.SolicitudesFactura', compact('solicitudes', 'solicitudesPendientes', 'tiendas', 'idTienda'));
    }

    public function VerSolicitud($id, Request $request)
    {
        // Primera consulta: Obtener la solicitud de factura
        $solicitud = SolicitudFactura::select(
            'SolicitudFactura.*',
            'CatTiendas.NomTienda',
            'ct.NomTipoPago',
            'dt.NumTarjeta',
            'cb.NomBanco',
            'rf.NomRegimenFiscal',
            'uc.NomCFDI',
            'mt.Descripcion as NomMetodoPago'
        )
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->leftJoin('CatRegimenFiscal as rf', 'rf.RegimenFiscal', 'SolicitudFactura.RegimenFiscal')
            ->leftJoin('CatUsoCFDI as uc', 'uc.UsoCFDI', 'SolicitudFactura.UsoCFDI')
            ->leftJoin('CatMetodoPago as mt', 'mt.MetPago', 'SolicitudFactura.MetodoPago')
            ->where('SolicitudFactura.IdSolicitudFactura', $id)
            ->first();

        // Verificar si se encontró la solicitud
        if (!$solicitud) {
            return response()->json(['error' => 'Solicitud no encontrada'], 404);
        }

        // Segunda consulta: Obtener los cortes agrupados
        // ->where('ct.IdSolicitudFactura', $solicitud->IdSolicitudFactura)
        // ->where('ct.IdEncabezado', $solicitud->IdEncabezado)
        $ventasDetalle  = CorteTienda::query()
            ->select([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                DB::raw('SUM(DatCortesTienda.CantArticulo) as CantArticulo'),
                DB::raw('SUM(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo'),
                DB::raw('SUM(DatCortesTienda.IvaArticulo) as IvaArticulo'),
                DB::raw('SUM(DatCortesTienda.ImporteArticulo) as ImporteArticulo'),
                // Datos del artículo
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                // Datos de cancelación
                'sc.IdEncabezado as SolicitudCancelacion',
                'sc.SolicitudAprobada',
                // Datos de solicitudFactura
                'DatCortesTienda.IdSolicitudFactura as IdSolicitudFactura'
            ])
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', '=', 'DatCortesTienda.IdArticulo')
            ->leftJoin(
                'SolicitudCancelacionTicket as sc',
                'sc.IdEncabezado',
                '=',
                'DatCortesTienda.IdEncabezado'
            )
            ->where('DatCortesTienda.StatusVenta', 0)
            ->whereNotNull('DatCortesTienda.IdSolicitudFactura')
            ->where('DatCortesTienda.IdSolicitudFactura', $solicitud->IdSolicitudFactura) // Filtro principal por encabezado
            ->where('DatCortesTienda.IdEncabezado', $solicitud->IdEncabezado) // Filtro principal por encabezado
            ->groupBy([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                'sc.IdEncabezado',
                'sc.SolicitudAprobada',
                'DatCortesTienda.IdSolicitudFactura'
            ])
            ->orderBy('DatCortesTienda.Source_Transaction_Identifier')
            ->get();

        // $resultados = $query->get();
        // return $ventas = CorteTienda::from('DatCortesTienda as ct')
        //     ->leftJoin('SolicitudFactura as sf', 'sf.IdSolicitudFactura', '=', 'ct.IdSolicitudFactura')
        //     ->select(
        //         'ct.IdEncabezado',
        //         'ct.Bill_To',
        //         'sf.NomCliente',
        //         'sf.Email',
        //         'ct.Source_Transaction_Identifier',
        //         'sf.Editar',
        //         DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
        //         DB::raw('SUM(ct.CantArticulo) as total_cantidad')
        //     )
        //     ->where('ct.StatusVenta', 0)
        //     ->where('sf.Status', 0)
        //     ->whereNotNull('ct.IdSolicitudFactura')
        //     ->groupBy(
        //         'ct.IdEncabezado',
        //         'ct.Bill_To',
        //         'sf.NomCliente',
        //         'sf.Email',
        //         'ct.Source_Transaction_Identifier',
        //         'sf.Editar'
        //     )
        //     ->orderBy('ct.Source_Transaction_Identifier')
        //     ->get();

        // Obtener el/los Source_Transaction_Identifier de los resultados
        $sourceTransactionIdentifiers = $ventasDetalle->pluck('Source_Transaction_Identifier');


        $oracleData = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
            ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier', 'UUID')
            ->whereIn('Source_Transaction_Identifier', $sourceTransactionIdentifiers)
            ->first();

        // $clienteSolicitud = Cliente::with([
        //     'CorreoCliente' => function ($query) {
        //         $query->select('IdClienteCloud', 'Email');
        //         $query->groupBy('IdClienteCloud', 'Email');
        //     }
        // ])
        //     ->where('RFC', $solicitud->RFC)
        //     // ->where('Bill_To', $solicitud->Bill_To)
        //     ->get();

        // $clientes = Cliente::where('RFC', $solicitud->RFC)
        //     ->get();

        // $oracleData = collect(null);
        // $ventas = collect(null);
        $pagosPorTipo = collect(null);
        // $oracleData = collect(null);

        // return [
        //     'solicitud' => $solicitud,
        //     'oracleData' => $oracleData,
        //     'ventas' => $ventas
        // ];


        return view('SolicitudesFactura.SolicitudFactura', compact('solicitud', 'oracleData', 'ventasDetalle'));
    }

    public function Relacionar($id, $billTo, Request $request)
    {
        $cliente = Cliente::where('Bill_To', $billTo)->first();

        SolicitudFactura::where('Id', $id)->update([
            'Bill_To' => $cliente->Bill_To,
            'IdClienteCloud' => $cliente->IdClienteCloud,
            'IdUsuarioCliente' => Auth::user()->IdUsuario,
            'Fecha_Cliente' => date('d-m-Y H:i:s')
        ]);

        $idSolid = SolicitudFactura::where('Id', $id)->value('IdSolicitudFactura');

        CorteTienda::where('IdSolicitudFactura', $idSolid)->update([
            'Bill_To' => $cliente->Bill_To
        ]);

        return back()->with('msjAdd', 'Cliente relacionado correctamente');
    }

    public function Finalizar($id, Request $request)
    {
        SolicitudFactura::where('Id', $id)->update([
            'Editar' => null,
        ]);

        return redirect('SolicitudesFactura')->with('msjAdd', 'Solicitud de factura finalizada correctamente');
    }

    public function Cancelar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Status' => 1,
                'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
                'FechaCancelacion' => date('d-m-Y H:i:s')
            ]);

            // Falta quitar el source del corte

            return redirect('SolicitudesFactura')->with('msjAdd', 'Solicitud de factura cancelada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
