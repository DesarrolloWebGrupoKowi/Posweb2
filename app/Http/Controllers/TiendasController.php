<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiendasController extends Controller
{
    public function CatTiendas(Request $request)
    {
        $filtroEstado = trim($request->get('filtroEstado'));
        $filtroCiudad = trim($request->get('filtroCiudad'));

        if ($filtroCiudad == 0) {
            $tiendas = DB::table('CatTiendas')
                ->leftJoin('CatCiudades', 'CatCiudades.IdCiudad', 'CatTiendas.IdCiudad')
                ->leftJoin('CatEstados', 'CatEstados.IdEstado', 'CatCiudades.IdEstado')
                ->leftJoin('CatPlazas', 'CatPlazas.IdPlaza', 'CatTiendas.IdPlaza')
                ->where('CatEstados.IdEstado', 'like', '%' . $filtroEstado . '%')
                ->select(
                    'CatTiendas.*',
                    'CatPlazas.IdPlaza as cpIdPlaza*',
                    'CatPlazas.NomPlaza as cpNomPlaza',
                    'CatCiudades.IdCiudad as ccIdCiudad',
                    'CatCiudades.NomCiudad as ccNomCiudad',
                    'CatEstados.IdEstado as ceIdEstado',
                    'CatEstados.NomEstado as ceNomEstado',
                )
                ->paginate(15);
        } else {
            $tiendas = DB::table('CatTiendas')
                ->leftJoin('CatCiudades', 'CatCiudades.IdCiudad', 'CatTiendas.IdCiudad')
                ->leftJoin('CatEstados', 'CatEstados.IdEstado', 'CatCiudades.IdEstado')
                ->leftJoin('CatPlazas', 'CatPlazas.IdPlaza', 'CatTiendas.IdPlaza')
                ->where('CatTiendas.IdCiudad', '=', $filtroCiudad)
                ->select(
                    'CatTiendas.*',
                    'CatPlazas.IdPlaza as cpIdPlaza*',
                    'CatPlazas.NomPlaza as cpNomPlaza',
                    'CatCiudades.IdCiudad as ccIdCiudad',
                    'CatCiudades.NomCiudad as ccNomCiudad',
                    'CatEstados.IdEstado as ceIdEstado',
                    'CatEstados.NomEstado as ceNomEstado',
                )
                ->paginate(15);
        }
        //return $tiendas;

        $plazas = DB::table('CatPlazas')
            ->where('Status', 0)
            ->get();
        $ciudades = Ciudad::all();
        $estados = DB::table('CatEstados')
            ->where('Status', '=', 0)
            ->get();

        return view('Tiendas/CatTiendas', compact('tiendas', 'ciudades', 'estados', 'plazas', 'filtroEstado', 'filtroCiudad'));
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
}
