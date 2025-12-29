<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiendasController extends Controller
{
    public function CatTiendas(Request $request)
    {
        $filtroTienda = $request->get('filtroTienda');

        $tiendas = DB::table('CatTiendas')
            ->leftJoin('CatCiudades', 'CatCiudades.IdCiudad', 'CatTiendas.IdCiudad')
            ->leftJoin('CatEstados', 'CatEstados.IdEstado', 'CatCiudades.IdEstado')
            ->leftJoin('CatPlazas', 'CatPlazas.IdPlaza', 'CatTiendas.IdPlaza')
            ->select(
                'CatTiendas.*',
                'CatPlazas.IdPlaza as cpIdPlaza*',
                'CatPlazas.NomPlaza as cpNomPlaza',
                'CatCiudades.IdCiudad as ccIdCiudad',
                'CatCiudades.NomCiudad as ccNomCiudad',
                'CatEstados.IdEstado as ceIdEstado',
                'CatEstados.NomEstado as ceNomEstado',
            )
            ->when($filtroTienda, function ($query) use ($filtroTienda) {
                $query->where(function ($q) use ($filtroTienda) {
                    $q->where('CatPlazas.NomPlaza', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatCiudades.NomCiudad', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatEstados.NomEstado', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatTiendas.Organization_Name', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatTiendas.NomTienda', 'like', '%' . $filtroTienda . '%');
                });
            })
            ->paginate(10);
        //return $tiendas;

        $plazas = DB::table('CatPlazas')->where('Status', 0)->get();
        $ciudades = Ciudad::get();

        return view('Tiendas/CatTiendas', compact('tiendas', 'filtroTienda', 'ciudades', 'plazas'));
    }

    public function CrearTienda(Request $request)
    {
        $tiendas = new Tienda();
        $tiendas->NomTienda = $request->get('NomTienda');
        $tiendas->Correo = $request->get('Correo');
        $tiendas->Direccion = $request->get('Direccion');
        $tiendas->Colonia = $request->get('Colonia');
        $tiendas->Telefono = $request->get('Telefono');
        $tiendas->RFC = $request->get('RFC');
        $tiendas->IdListaPrecios = $request->get('IdListaPrecios');
        $tiendas->TiendaActiva = $request->get('TiendaActiva');
        $tiendas->Inventario = $request->get('Inventario');
        $tiendas->CentroCosto = $request->get('CentroCosto');
        $tiendas->IdCiudad = $request->get('IdCiudad');
        $tiendas->IdPlaza = $request->get('IdPlaza');
        $tiendas->Almacen = $request->get('Almacen');
        $tiendas->Organization_Name = $request->get('Organization_Name');
        $tiendas->Subinventory_Code = $request->get('Subinventory_Code');
        $tiendas->Order_Type_Cloud = $request->get('Order_Type_Cloud');
        $tiendas->ServicioaDomicilio = $request->get('ServicioaDomicilio');
        $tiendas->CostoaDomicilio = $request->get('CostoaDomicilio');
        $tiendas->Comentario = $request->get('Comentario');
        $tiendas->Status = 0;
        $tiendas->save();

        return redirect("CatTiendas")->with('msjAdd', 'Tienda Agregada con Exito!');
    }

    public function EditarTienda(Request $request, $id)
    {
        $direccion = $request->get('Direccion');
        $plaza = $request->get('IdPlaza');
        $colonia = $request->get('Colonia');
        $nombreCorto = $request->get('NombreCorto');
        $correo = $request->get('Correo');
        $telefono = $request->get('Telefono');
        $centroCosto = $request->get('CentroCosto');
        $ciudad = $request->get('IdCiudad');
        $almacen = $request->get('Almacen');
        $organizationName = $request->get('Organization_Name');
        $subinventoryCloud = $request->get('Subinventory_Cloud');
        $servicioADomicilio = $request->get('ServicioaDomicilio');
        $costo = $request->get('CostoaDomicilio');
        $comentario = $request->get('Comentario');
        $orderTypeCloud = $request->get('Order_Type_Cloud');
        $tiendaActiva = $request->get('TiendaActiva');
        $inventario = $request->get('Inventario');
        $listaPrecios = $request->get('IdListaPrecios');

        Tienda::where('IdTienda', $id)
            ->update([
                'Direccion' => $direccion,
                'IdPlaza' => $plaza,
                'Colonia' => $colonia,
                'NombreCorto' => $nombreCorto,
                'Correo' => $correo,
                'Telefono' => $telefono,
                'CentroCosto' => $centroCosto,
                'IdCiudad' => $ciudad,
                'Almacen' => $almacen,
                'Organization_Name' => $organizationName,
                'Subinventory_Code' => $subinventoryCloud,
                'ServicioaDomicilio' => $servicioADomicilio,
                'CostoaDomicilio' => $costo,
                'Comentario' => $comentario,
                'Order_Type_Cloud' => $orderTypeCloud,
                'TiendaActiva' => $tiendaActiva,
                'Inventario' => $inventario,
                'IdListaPrecios' => $listaPrecios,
            ]);

        $tienda = Tienda::find($id);

        return back()->with('msjupdate', 'Se ha Editado la Tienda: ' . $tienda->NomTienda . ' Con Exito!');
    }

    public function EliminarTienda($id)
    {
        Tienda::where('IdTienda', $id)
            ->update([
                'Status' => 1,
            ]);

        $tiendaDelete = Tienda::where('IdTienda', $id)
            ->first();

        return back()->with('msjdelete', 'Tienda ' . $tiendaDelete->NomTienda . ' Eliminada Con Exito!');
    }

    public function CatTiendasProcesar(Request $request)
    {
        $filtroTienda = $request->get('filtroTienda');
        $filtroStatus = $request->get('filtroStatus');

        $tiendas = DB::table('CatTiendas')
            ->leftJoin('CatCiudades', 'CatCiudades.IdCiudad', 'CatTiendas.IdCiudad')
            ->leftJoin('CatEstados', 'CatEstados.IdEstado', 'CatCiudades.IdEstado')
            ->leftJoin('CatPlazas', 'CatPlazas.IdPlaza', 'CatTiendas.IdPlaza')
            ->leftJoin('CatUsuarios', 'CatUsuarios.IdUsuario', 'CatTiendas.usuarioprocesarcorte')
            ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatUsuarios.NumNomina')
            ->select(
                'CatTiendas.*',
                'CatPlazas.IdPlaza as cpIdPlaza*',
                'CatPlazas.NomPlaza as cpNomPlaza',
                'CatCiudades.IdCiudad as ccIdCiudad',
                'CatCiudades.NomCiudad as ccNomCiudad',
                'CatEstados.IdEstado as ceIdEstado',
                'CatEstados.NomEstado as ceNomEstado',
                'CatUsuarios.NomUsuario as cuNomUsuario',
                'CatEmpleados.Nombre as ceNombre',
                'CatEmpleados.Apellidos as ceApellidos',
            )
            ->when($filtroTienda, function ($query) use ($filtroTienda) {
                $query->where(function ($q) use ($filtroTienda) {
                    $q->where('CatPlazas.NomPlaza', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatCiudades.NomCiudad', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatEstados.NomEstado', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatTiendas.Organization_Name', 'like', '%' . $filtroTienda . '%')
                        ->orWhere('CatTiendas.NomTienda', 'like', '%' . $filtroTienda . '%');
                });
            })
            ->when($filtroStatus !== null && $filtroStatus !== '', function ($query) use ($filtroStatus) {
                $query->where('CatTiendas.Status', 'like', $filtroStatus);
            })
            ->get();

        return view('Tiendas/CatTiendasProcesar', compact('tiendas', 'filtroTienda'));
    }

    public function actualizarProcesarCorte(Request $request, $id)
    {
        $tienda = Tienda::find($id);
        if (!$tienda) {
            return response()->json(['ok' => false, 'msg' => 'Tienda no encontrada']);
        }

        try {
            $tienda->procesarcorte = $request->procesarcorte;
            $tienda->fechaprocesarcorte = DB::raw("CAST(GETDATE() AS smalldatetime)");
            $tienda->usuarioprocesarcorte = Auth::user()->IdUsuario;
            $tienda->save();

            // Recargar datos reales desde SQL Server
            // $tienda->refresh();
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Error al guardar los cambios en la tienda',
            ]);
        }

        $tiendaConUsuario = Tienda::from('CatTiendas as t')
            ->leftJoin('CatUsuarios as u', 'u.IdUsuario', '=', 't.usuarioprocesarcorte')
            ->leftJoin('CatEmpleados as e', 'e.NumNomina', '=', 'u.NumNomina')
            ->where('t.IdTienda', $id)
            ->select([
                't.IdTienda',
                't.procesarcorte',
                't.fechaprocesarcorte',
                't.usuarioprocesarcorte',
                DB::raw("RTRIM(LTRIM(e.Nombre)) as ceNombre"),
                DB::raw("RTRIM(LTRIM(e.Apellidos)) as ceApellidos"),
            ])
            ->first();
        return response()->json(['ok' => true, 'tienda' => $tiendaConUsuario]);
    }

    //+============================================================================================================================================+//
    //Mostrar Tiendas Que Van A Procesar Cortes Rutas (RUTAS)
    public function CatRutasProcesar(Request $request)
    {
        $sucursales = DB::connection('server4.20')->table('XXKW_DB_SUCURSAL')
            ->leftJoin('XXKW_AUT_MAYOREOS_VW', 'XXKW_AUT_MAYOREOS_VW.SUB_INVENT_MAY', 'XXKW_DB_SUCURSAL.MAYOREO')
            ->select('*')
            ->get();

        $idsUsuarios = $sucursales
            ->pluck('usuarioprocesarcorte')
            ->filter()
            ->unique()
            ->values();

        $usuarios = DB::table('CatUsuarios')
            ->whereIn('IdUsuario', $idsUsuarios)
            ->get()
            ->keyBy('IdUsuario');


        $numsNomina = $usuarios
            ->pluck('NumNomina')
            ->filter()
            ->unique()
            ->values();

        $empleados = DB::table('CatEmpleados')
            ->whereIn('NumNomina', $numsNomina)
            ->get()
            ->keyBy('NumNomina');

        $sucursales->transform(function ($sucursal) use ($usuarios, $empleados) {

            $usuario = $usuarios[$sucursal->usuarioprocesarcorte] ?? null;
            $empleado = $usuario
                ? ($empleados[$usuario->NumNomina] ?? null)
                : null;

            $sucursal->ceNombre = $empleado->Nombre ?? null;
            $sucursal->ceApellidos = $empleado->Apellidos ?? null;

            return $sucursal;
        });

        return view('Tiendas/CatRutasProcesar', compact('sucursales'));
    }

    public function actualizarProcesarCorteRutas(Request $request, $id)
    {
        $sucursal = DB::connection('server4.20')->table('XXKW_DB_SUCURSAL')->where('MAYOREO', $id)->first();

        if (!$sucursal) {
            return response()->json(['ok' => false, 'msg' => 'Sucursal no encontrada']);
        }

        $updated = DB::connection('server4.20')
            ->table('XXKW_DB_SUCURSAL')
            ->where('MAYOREO', $id)
            ->update([
                'procesarcorte' => $request->procesarcorte,
                'fechaprocesarcorte' => DB::raw("CAST(GETDATE() AS smalldatetime)"),
                'usuarioprocesarcorte' => Auth::user()->IdUsuario,
            ]);

        $sucursal = DB::connection('server4.20')->table('XXKW_DB_SUCURSAL')->where('MAYOREO', $id)->first();
        $usuario = DB::table('CatUsuarios')->where('IdUsuario', $sucursal->usuarioprocesarcorte)->first();
        $empleado = DB::table('CatEmpleados')->where('NumNomina', $usuario->NumNomina)->first();

        $sucursal->ceNombre = $empleado->Nombre ?? null;
        $sucursal->ceApellidos = $empleado->Apellidos ?? null;

        return response()->json(['ok' => true, 'sucursal' => $sucursal]);
    }

    //+============================================================================================================================================+//
    //Mostrar Centros De Venta Que Van A Procesar Cortes (ECCOMERCE)
    public function CatCentrosVentaProcesar(Request $request)
    {
        $centrosVenta = DB::table('SERVER.ecommerceapp.dbo.DatCentroVenta')
            ->leftJoin('CatUsuarios', 'CatUsuarios.IdUsuario', 'DatCentroVenta.usuarioprocesarcorte')
            ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatUsuarios.NumNomina')
            ->select('DatCentroVenta.*', 'CatEmpleados.Nombre as ceNombre', 'CatEmpleados.Apellidos as ceApellidos')
            ->get();

        return view('Tiendas/CatCentrosDeVentaProcesar', compact('centrosVenta'));
    }

    public function actualizarProcesarCorteCentrosVenta(Request $request, $id)
    {
        $centroVenta = DB::table('SERVER.ecommerceapp.dbo.DatCentroVenta')->where('Almacen_Oracle', $id)->first();

        if (!$centroVenta) {
            return response()->json(['ok' => false, 'msg' => 'Centro de venta no encontrada']);
        }

        $updated = DB::table('SERVER.ecommerceapp.dbo.DatCentroVenta')
            ->where('Almacen_Oracle', $id)
            ->update([
                'procesarcorte' => $request->procesarcorte,
                'fechaprocesarcorte' => DB::raw("CAST(GETDATE() AS smalldatetime)"),
                'usuarioprocesarcorte' => Auth::user()->IdUsuario,
            ]);

        $centroVenta = DB::table('SERVER.ecommerceapp.dbo.DatCentroVenta')
            ->leftJoin('CatUsuarios', 'CatUsuarios.IdUsuario', 'DatCentroVenta.usuarioprocesarcorte')
            ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatUsuarios.NumNomina')
            ->select('DatCentroVenta.*', 'CatEmpleados.Nombre as ceNombre', 'CatEmpleados.Apellidos as ceApellidos')
            ->where('DatCentroVenta.Almacen_Oracle', $id)
            ->first();

        return response()->json(['ok' => true, 'centroVenta' => $centroVenta]);
    }
}
