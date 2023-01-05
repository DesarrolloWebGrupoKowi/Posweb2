<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LimiteCredito;
use App\Models\CreditoEmpleado;
use App\Models\Empleado;
use App\Models\HistorialCredito;
use App\Models\CorteTienda;

class InterfazCreditosController extends Controller
{
    public function InterfazCreditos(Request $request){
        $tiposNomina = LimiteCredito::all();

        $chkNomina = $request->chkNomina;
        $numNomina = $request->numNomina;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idTipoNomina = $request->tipoNomina;

        if($chkNomina == 'on'){
            $creditos = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('a.NumNomina', $numNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->select('a.*', 'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 'd.NomCiudad')
                ->get();

            $totalAdeudo = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('a.NumNomina', $numNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->sum('ImporteCredito');
        }else{
            $creditos = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('c.TipoNomina', $idTipoNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->select('a.*', 'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 'd.NomCiudad')
                ->get();

            $totalAdeudo = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('c.TipoNomina', $idTipoNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->sum('ImporteCredito');
        }

        $nomTipoNomina = LimiteCredito::where('TipoNomina', $idTipoNomina)
            ->value('NomTipoNomina');

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        //return $creditos;

        return view('InterfazCreditos.InterfazCreditos', compact('tiposNomina', 'chkNomina', 'numNomina', 'fecha1', 
            'fecha2', 'idTipoNomina', 'creditos', 'nomTipoNomina', 'empleado', 'totalAdeudo'));
    }

    public function InterfazarCreditos($fecha1, $fecha2, $idTipoNomina, $numNomina){
        try {
            DB::beginTransaction();

            HistorialCredito::insert([
                'FechaDesde' => $fecha1,
                'FechaHasta' => $fecha2,
                'FechaInterfaz' => date('d-m-Y H:i:s'),
                'TipoNomina' => empty($idTipoNomina) ? null : $idTipoNomina,
                'IdUsuario' => Auth::user()->IdUsuario,
                'NumNomina' => empty($numNomina) ? null : $numNomina
            ]);

            $idHistorialCredito = HistorialCredito::max('IdHistorialCredito');

            if($idTipoNomina == 0 && !empty($numNomina)){

                DB::statement("SET XACT_ABORT ON;
                    insert into SPARH_TEST..D2000.KW_INTERFASE_VENTAS
                    select a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda,
                    d.NomCiudad, e.ImporteArticulo, f.IdTicket, ". $idHistorialCredito ."
                    from DatCreditos as a
                    left join CatTiendas as b on b.IdTienda=a.IdTienda
                    left join CatEmpleados as c on c.NumNomina=a.NumNomina
                    left join CatCiudades as d on d.IdCiudad=b.IdCiudad
                    left join DatCortesTienda as e on e.IdEncabezado=a.IdEncabezado
                    left join DatEncabezado as f on f.IdEncabezado=a.IdEncabezado
                    where a.StatusCredito = 0
                    and a.StatusVenta = 0
                    and a.NumNomina = ". $numNomina ."
                    and e.StatusCredito = 0
                    and a.Interfazado is null
                    and CAST(a.FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'
                    SET XACT_ABORT OFF;");

                CreditoEmpleado::whereRaw("CAST(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                    ->where('NumNomina', $numNomina)
                    ->where('StatusCredito', 0)
                    ->where('StatusVenta', 0)
                    ->whereNull('Interfazado')
                    ->update([
                        'StatusCredito' => 1,
                        'Interfazado' => $idHistorialCredito
                    ]);

                CorteTienda::whereRaw("CAST(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                    ->where('NumNomina', $numNomina)
                    ->where('StatusCredito', 0)
                    ->where('StatusVenta', 0)
                    ->whereNull('Interfazado')
                    ->update([
                        'StatusCredito' => 1,
                        'Interfazado' => $idHistorialCredito
                    ]);

            }if(!empty($idTipoNomina) && $numNomina == 0){
                
                DB::statement("SET XACT_ABORT ON;
                    insert into SPARH_TEST..D2000.KW_INTERFASE_VENTAS
                    select a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda,
                    d.NomCiudad, e.ImporteArticulo, f.IdTicket, ". $idHistorialCredito ."
                    from DatCreditos as a
                    left join CatTiendas as b on b.IdTienda=a.IdTienda
                    left join CatEmpleados as c on c.NumNomina=a.NumNomina
                    left join CatCiudades as d on d.IdCiudad=b.IdCiudad
                    left join DatCortesTienda as e on e.IdEncabezado=a.IdEncabezado
                    left join DatEncabezado as f on f.IdEncabezado=a.IdEncabezado
                    where a.StatusCredito = 0
                    and a.StatusVenta = 0
                    and e.StatusCredito = 0
                    and c.TipoNomina = ". $idTipoNomina ."
                    and a.Interfazado is null
                    and CAST(a.FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'
                    SET XACT_ABORT OFF;");

                CreditoEmpleado::whereRaw("CAST(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                    ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'DatCreditos.NumNomina')
                    ->where('StatusCredito', 0)
                    ->where('StatusVenta', 0)
                    ->where('CatEmpleados.TipoNomina', $idTipoNomina)
                    ->whereNull('Interfazado')
                    ->update([
                        'DatCreditos.StatusCredito' => 1,
                        'DatCreditos.Interfazado' => $idHistorialCredito
                    ]);

                CorteTienda::whereRaw("CAST(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                    ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'DatCortesTienda.NumNomina')
                    ->where('StatusCredito', 0)
                    ->where('StatusVenta', 0)
                    ->where('CatEmpleados.TipoNomina', $idTipoNomina)
                    ->whereNull('Interfazado')
                    ->update([
                        'DatCortesTienda.StatusCredito' => 1,
                        'Interfazado' => $idHistorialCredito
                    ]);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se interfazaron los créditos exitosamente!');
    }
}
