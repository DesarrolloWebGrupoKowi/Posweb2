<?php

namespace App\Http\Controllers;

use App\Models\DatCaja;
use App\Models\DatEncabezado;
use App\Models\SolicitudFactura;
use App\Models\Tienda;
use App\Models\TipoMenu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Condition
{
    public $IdTipoMenu;
}

class DashboardController extends Controller
{
    public function Dashboard()
    {
        if (Auth::user()->IdTipoUsuario == 1) {
            return $this->DashboardAdminCat();
        }

        $idCaja =  DatCaja::where('status', 0)->where('activa', 0)->value('IdCaja');

        $tipoMenus = DB::table('CatMenus as a')
            ->leftJoin('CatTipoMenu as b', 'b.IdTipoMenu', 'a.IdTipoMenu')
            ->leftJoin('DatMenuTipoUsuario as c', 'c.IdMenu', 'a.IdMenu')
            ->select('b.IdTipoMenu')
            ->where('c.IdTipoUsuario', Auth::user()->IdTipoUsuario)
            ->distinct('b.IdTipoMenu')
            ->get();

        //Instanciar clase condition para hacer la validacion cuando el Tipo de usuario no tiene Menus asignados
        $condition[] = new Condition;
        $condition[0]->IdTipoMenu = "0";

        //return $condition;

        $tipoMenus = count($tipoMenus) == 0 ? $condition : $tipoMenus;

        foreach ($tipoMenus as $key => $tipoMenuItem) {
            if ($idCaja == 1)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
            else
            if ($tipoMenuItem->IdTipoMenu != 5)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
        }

        // return $tipoMenu;

        $menus = TipoMenu::with('DetalleMenu')
            ->whereIn('IdTipoMenu', $tipoMenu)
            ->orderBy('Posicion')
            ->get();

        //return $menus;

        return view('Dashboard.DashboardMaterial', compact('menus', 'idCaja'));
    }

    public function DashboardAdmin()
    {
        $fecha = $request->fecha ?? Carbon::now()->format('Y-m-d');
        $tiendas = $this->getTiendas();

        $ventasData = DatEncabezado::select(
            DB::raw('
                SUM(ImporteArticulo) AS total_ventas,
                COUNT(DISTINCT IdTienda) AS total_tiendas_activas,
                COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
            ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->whereIn('IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', $fecha)
            ->first();

        $ventasPorTienda = DatEncabezado::select(
            DB::raw('
                    CatTiendas.NombreCorto AS tienda,
                    SUM(ImporteArticulo) AS total_ventas,
                    COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                    SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatEncabezado.IdTienda')
            ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', $fecha)
            ->groupBy('DatEncabezado.IdTienda', 'CatTiendas.NombreCorto')
            ->get();

        $ultimoMes = DatEncabezado::select(
            DB::raw('
                        CONVERT(VARCHAR(10), DatEncabezado.FechaVenta, 103) AS fecha,
                        SUM(ImporteArticulo) AS total_ventas,
                        COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                        SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                    ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', '>', Carbon::parse($fecha)->subMonth())
            ->groupBy(DB::raw('CONVERT(VARCHAR(10), DatEncabezado.FechaVenta, 103)'))  // Agrupamos solo por la fecha sin la parte de hora
            ->orderBy(DB::raw('MIN(DatEncabezado.FechaVenta)'))  // Usamos MIN para ordenar por la fecha más antigua de cada grupo
            ->get();

        $topProductos = DB::table('DatEncabezado AS de')
            ->select(
                'ca.NomArticulo',
                DB::raw('SUM(dd.CantArticulo) AS total_vendido'),
                DB::raw('SUM(dd.ImporteArticulo) AS total_ventas')
            )
            ->leftJoin('DatDetalle AS dd', 'de.IdEncabezado', '=', 'dd.IdEncabezado')
            ->leftJoin('CatArticulos AS ca', 'ca.IdArticulo', '=', 'dd.IdArticulo')
            ->whereIn('de.IdTienda', $tiendas)
            ->where('de.StatusVenta', 0)
            ->where('de.SolicitudFE', 0)  // Solo solicitudes de factura no nulas
            ->whereDate('de.FechaVenta', '>', Carbon::parse($fecha)->subMonth())  // Último mes
            ->groupBy('ca.NomArticulo')
            ->orderByDesc(DB::raw('SUM(dd.CantArticulo)'))  // Ordenar por cantidad vendida
            ->limit(10)  // Limitar a los primeros 10 productos
            ->get();

        $solicitudes = SolicitudFactura::leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->whereDate('FechaSolicitud', $fecha)
            ->where('SolicitudFactura.Status', '0')
            // ->whereNotNull('SolicitudFactura.Editar')
            ->get();

        // Formatear los datos para el gráfico de ventas del ultimo mes
        $labelsTiendas = $ventasPorTienda->pluck('tienda')->toArray();
        $dataTiendas = [
            'total_ventas' => $ventasPorTienda->pluck('total_ventas')->toArray(),
            'solicitudes_factura' => $ventasPorTienda->pluck('solicitudes_factura')->toArray(),
            'total_kilos' => $ventasPorTienda->pluck('total_kilos')->toArray(),
        ];

        // Formatear los datos para el gráfico de ventas del ultimo mes
        $labels = $ultimoMes->pluck('fecha')->toArray();
        $data = [
            'total_ventas' => $ultimoMes->pluck('total_ventas')->toArray(),
            'solicitudes_factura' => $ultimoMes->pluck('solicitudes_factura')->toArray(),
            'total_kilos' => $ultimoMes->pluck('total_kilos')->toArray(),
        ];

        return view('Dashboard.DashboardMaterialAdmin', compact('ventasData', 'labelsTiendas', 'dataTiendas', 'labels', 'data', 'topProductos', 'solicitudes'));
    }


    public function DashboardAdminCat()
    {
        $routeName = request()->route()->getName();

        $idCaja =  DatCaja::where('status', 0)->where('activa', 0)->value('IdCaja');

        $tipoMenus = DB::table('CatMenus as a')
            ->leftJoin('CatTipoMenu as b', 'b.IdTipoMenu', 'a.IdTipoMenu')
            ->leftJoin('DatMenuTipoUsuario as c', 'c.IdMenu', 'a.IdMenu')
            ->select('b.IdTipoMenu')
            ->where('c.IdTipoUsuario', Auth::user()->IdTipoUsuario)
            ->distinct('b.IdTipoMenu')
            ->get();

        //Instanciar clase condition para hacer la validacion cuando el Tipo de usuario no tiene Menus asignados
        $condition[] = new Condition;
        $condition[0]->IdTipoMenu = "0";

        //return $condition;

        $tipoMenus = count($tipoMenus) == 0 ? $condition : $tipoMenus;

        foreach ($tipoMenus as $key => $tipoMenuItem) {
            if ($idCaja == 1)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
            else
            if ($tipoMenuItem->IdTipoMenu != 5)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
        }

        // return $routeName;
        $idTipoMenu = 0;
        switch ($routeName) {
            case 'dashboardcatalogos':
                $idTipoMenu = 1;
                break;
            case 'dashboardadministracion':
                $idTipoMenu = 2;
                break;
            case 'dashboardReportes':
                $idTipoMenu = 3;
                break;
            case 'dashboardInterfaz':
                $idTipoMenu = 6;
                break;
        }


        $menus = TipoMenu::with('DetalleMenu')
            ->whereIn('IdTipoMenu', $tipoMenu)
            // ->where('IdTipoMenu', $idTipoMenu)
            ->orderBy('Posicion')
            ->get();

        // return $menus;

        return view('Dashboard.DashboardAdminSections', compact('menus', 'idCaja'));
    }

    function getTiendas()
    {
        $usuarioTienda = Auth::user()->usuarioTienda;
        $tiendasQuery = Tienda::where('Status', 0)->orderBy('IdTienda');

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendasQuery->where('IdTienda', $usuarioTienda->IdTienda);
        }

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendasQuery->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        $tiendas = $tiendasQuery->get();
        return $tiendas->pluck('IdTienda')->toArray();
    }
}
