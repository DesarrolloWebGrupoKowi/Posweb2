<?php

namespace App\Http\Controllers;

use App\Models\DatCaja;
use App\Models\Tabla;
use App\Models\TablaUpdate;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablasUpdateController extends Controller
{
    public function TablasUpdate(Request $request)
    {
        $tiendas = Tienda::all();

        $idTienda = $request->idTienda;

        $tablasActualizables = TablaUpdate::where('IdTienda', $idTienda)
            ->orderBy('Descargar')
            ->get();

        $tablasPorDescargar = TablaUpdate::where('Descargar', 0)
            ->where('IdTienda', $idTienda)
            ->count();

        $checkedTodas = 1;
        if ($tablasActualizables->count() == $tablasPorDescargar) {
            $checkedTodas = 0;
        }

        $nomTablas = TablaUpdate::where('IdTienda', $idTienda)
            ->pluck('NombreTabla');

        $tablas = Tabla::where('Status', 0)
            ->whereNotIn('NomTabla', $nomTablas)
            ->get();

        return view('TablasUpdate.TablasUpdate', compact('tiendas', 'tablasActualizables', 'idTienda', 'tablasPorDescargar', 'checkedTodas', 'tablas'));
    }

    public function CatTablas(Request $request)
    {
        $tablas = Tabla::all();

        return view('TablasUpdate.Tablas', compact('tablas'));
    }

    public function AgregarTablas(Request $request)
    {
        $tabla = new Tabla();
        $tabla->NomTabla = $request->nomTabla;
        $tabla->Status = 0;

        $tabla->save();

        return back()->with('msjAdd', 'Tabla agregada correctamente');
    }

    public function AgregarTablasActualizablesTienda($idTienda)
    {
        if (Tienda::where('IdTienda', $idTienda)->exists()) {
            try {
                DB::beginTransaction();

                $insert = "insert into CatTablasUpdate select '" . $idTienda . "', NomTabla, 1 from CatTablas"; //Descargar en 1 por el momento, despues sera: 0
                DB::insert($insert);

                DB::commit();
                return back()->with('msjAdd', 'Se Agregaron Las Tablas a la Tienda');
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }
        }

        return back()->with('msjdelete', 'No Existe Tienda: ' . $idTienda);
    }

    public function ActualizarTablas(Request $request, $idTienda)
    {
        $tablas = $request->descargado;

        try {
            DB::beginTransaction();
            if (!empty($tablas)) {
                foreach ($tablas as $key => $tabla) {
                    TablaUpdate::where('IdTienda', $idTienda)
                        ->where('NombreTabla', $tabla)
                        ->update([
                            'Descargar' => 0,
                            'FechaDescarga' => date('d-m-Y H:i:s'),
                        ]);
                }

                TablaUpdate::where('IdTienda', $idTienda)
                    ->whereNotIn('NombreTabla', $tablas)
                    ->update([
                        'Descargar' => 1,
                    ]);
            } else {
                TablaUpdate::where('IdTienda', $idTienda)
                    ->update([
                        'Descargar' => 1,
                    ]);
            }

            DB::commit();
            return back()->with('msjAdd', 'Tablas a Espera de Actualización');

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th);
        }
    }

    public function AgregarTablaUpdate($idTienda, Request $request)
    {
        $cajas = DatCaja::where('IdTienda', $idTienda)
            ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
            ->get();

        $nomTablas = $request->nomTablas;

        foreach ($cajas as $key => $caja) {
            foreach ($nomTablas as $key => $nomTabla) {
                TablaUpdate::insert([
                    'IdTienda' => $idTienda,
                    'IdCaja' => $caja->IdCaja,
                    'NombreTabla' => $nomTabla,
                    'Descargar' => 1,
                ]);
            }
        }

        return back();

    }
}
