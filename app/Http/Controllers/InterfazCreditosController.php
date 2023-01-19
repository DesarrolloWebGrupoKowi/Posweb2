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
        $chkNomina = $request->chkNomina;
        $numNomina = $request->numNomina;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idTipoNomina = $request->tipoNomina;

        if($chkNomina == 'on'){
            $creditosVentaWeb_New = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('a.NumNomina', $numNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->select('a.IdTienda', 'a.IdEncabezado', 'a.FechaVenta', 'a.NumNomina',
                    DB::raw("SUM(a.ImporteCredito) as ImporteCredito"), 'a.StatusCredito', 'a.StatusVenta', 
                    'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 'd.NomCiudad')
                ->selectSub(function ($query) {
                        $query->selectRaw('1');
                }, 'isSistemaNuevo')                
                ->groupBy('a.NumNomina', 'a.IdTienda', 'a.IdEncabezado', 'a.FechaVenta', 'a.StatusCredito',
                    'a.StatusVenta', 'a.Interfazado', 'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 
                    'd.NomCiudad')
                    ->get()
                    ->toArray();

            $creditosVentaWeb = DB::connection('server4.3')->table("datencabezado as a")
                ->leftJoin("cattiendas as b", function($join){
                    $join->on("a.idtienda", "=", "b.id");
                })
                ->leftJoin("catempleados as c", function($join){
                    $join->on("a.empleado", "=", "c.num_nomina");
                })
                ->leftJoin("capcteacum as d", function($join){
                    $join->on("a.ID", "=", "d.idvta")
                    ->where('d.PUNTOS', '<', 0);
                })
                ->select("a.ID as IdEncabezado", "a.empleado as NumNomina", "c.nombre as Nombre", 
                    "c.apellidos as Apellidos", "a.importe as ImporteCredito", 
                    "a.fecha as FechaVenta", "a.idtienda as IdTienda", "b.nombre as NomTienda", 
                    "b.ciudad as NomCiudad", "a.edoventa as StatusVenta")
                ->selectSub(function ($query) {
                    $query->selectRaw('0');
                }, 'isSistemaNuevo')
                ->where('a.IdTIenda', '<>', 30)
                ->where("edoventa", "=", 0)
                ->whereRaw("cast(a.Fecha as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->where("exportado", "=", 0)
                ->where("empleado", "=", $numNomina)
                ->where("exportado", "<>", -1)
                ->where("a.tipopago", "=", 28)
                ->where("c.status", "=", 1)
                ->get()
                ->toArray();

            // union a las dos consultas
            $creditos = array_merge($creditosVentaWeb, $creditosVentaWeb_New);

            $totalAdeudoVentaWeb_New = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('a.NumNomina', $numNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->sum('ImporteCredito');

            $totalAdeudoVentaWeb = DB::connection('server4.3')->select("SELECT SUM(A.IMPORTE) as ImporteCredito 
                FROM DATENCABEZADO AS A
                LEFT JOIN CATTIENDAS AS B ON A.IDTIENDA=B.ID 
                LEFT JOIN CATEMPLEADOS AS C ON CAST(A.EMPLEADO AS INT)=C.NUM_NOMINA
                LEFT JOIN CAPCTEACUM AS D ON A.ID = D.IDVTA
                AND D.PUNTOS<0
                WHERE A.IDTIENDA<>30
                AND EMPLEADO = '". $numNomina ."'
                AND EDOVENTA=0 
                AND CAST(A.FECHA AS DATE) BETWEEN '". $fecha1 ."' AND '". $fecha2 ."'
                AND EXPORTADO=0
                AND EXPORTADO <> '-1' 
                AND A.TIPOPAGO=28
                and c.STATUS = 1");

            foreach ($totalAdeudoVentaWeb as $key => $importeCreditoVentaWeb) {
                $importeCredito = $importeCreditoVentaWeb->ImporteCredito;
            }

            // union all a las tablas nuevas y viejas
            $totalAdeudo = $totalAdeudoVentaWeb_New + $importeCredito;

        }else{
            $creditosVentaWeb_New = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('c.TipoNomina', $idTipoNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->select('a.IdTienda', 'a.IdEncabezado', 'a.FechaVenta', 'a.NumNomina',
                    DB::raw("SUM(a.ImporteCredito) as ImporteCredito"), 'a.StatusCredito', 'a.StatusVenta', 
                    'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 'd.NomCiudad')
                ->selectSub(function ($query) {
                    $query->selectRaw('1');
                }, 'isSistemaNuevo')
                ->groupBy('a.NumNomina', 'a.IdTienda', 'a.IdEncabezado', 'a.FechaVenta', 'a.StatusCredito',
                    'a.StatusVenta', 'a.Interfazado', 'b.NomTienda', 'c.TipoNomina', 'c.Nombre', 'c.Apellidos', 
                    'd.NomCiudad')
                ->get()
                ->toArray();

            $creditosVentaWeb = DB::connection('server4.3')->table("datencabezado as a")
                ->leftJoin("cattiendas as b", function($join){
                    $join->on("a.idtienda", "=", "b.id");
                })
                ->leftJoin("catempleados as c", function($join){
                    $join->on("a.empleado", "=", "c.num_nomina");
                })
                ->leftJoin("capcteacum as d", function($join){
                    $join->on("a.ID", "=", "d.idvta")
                    ->where('d.PUNTOS', '<', 0);
                })
                ->select("a.ID as IdEncabezado", "a.empleado as NumNomina", "c.nombre as Nombre", 
                "c.apellidos as Apellidos", "a.importe as ImporteCredito", 
                "a.fecha as FechaVenta", "a.idtienda as IdTienda", "b.nombre as NomTienda", 
                "b.ciudad as NomCiudad", "a.edoventa as StatusVenta")
                ->selectSub(function ($query) {
                    $query->selectRaw('0');
                }, 'isSistemaNuevo')
                ->where('a.IdTIenda', '<>', 30)
                ->where("edoventa", "=", 0)
                ->whereRaw("cast(a.Fecha as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->where("exportado", "=", 0)
                ->where('a.TipoNomina', $idTipoNomina)
                ->where("exportado", "<>", -1)
                ->where("a.tipopago", "=", 28)
                ->where("c.status", "=", 1)
                ->get()
                ->toArray();

            // union a las dos consultas
            $creditos = array_merge($creditosVentaWeb, $creditosVentaWeb_New);

            $totalAdeudoVentaWeb_New = DB::table('DatCreditos as a')
                ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
                ->leftJoin('CatEmpleados as c', 'c.NumNomina', 'a.NumNomina')
                ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'b.IdCiudad')
                ->where('StatusCredito', 0)
                ->where('StatusVenta', 0)
                ->where('c.TipoNomina', $idTipoNomina)
                ->whereNull('a.Interfazado')
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->sum('ImporteCredito');

            $totalAdeudoVentaWeb = DB::connection('server4.3')->select("SELECT SUM(A.IMPORTE) as ImporteCredito 
                FROM DATENCABEZADO AS A
                LEFT JOIN CATTIENDAS AS B ON A.IDTIENDA=B.ID 
                LEFT JOIN CATEMPLEADOS AS C ON CAST(A.EMPLEADO AS INT)=C.NUM_NOMINA
                LEFT JOIN CAPCTEACUM AS D ON A.ID = D.IDVTA
                AND D.PUNTOS<0
                WHERE A.IDTIENDA<>30
                AND A.TIPONOMINA= ". $idTipoNomina ."
                AND EDOVENTA=0 
                AND CAST(A.FECHA AS DATE) BETWEEN '". $fecha1 ."' AND '". $fecha2 ."'
                AND EXPORTADO=0
                AND EXPORTADO <> '-1' 
                AND A.TIPOPAGO=28
                and c.STATUS = 1");

            foreach ($totalAdeudoVentaWeb as $key => $importeCreditoVentaWeb) {
                $importeCredito = $importeCreditoVentaWeb->ImporteCredito;
            }

            // union all a las tablas nuevas y viejas
            $totalAdeudo = $totalAdeudoVentaWeb_New + $importeCredito;
        }

        $nomTipoNomina = LimiteCredito::where('TipoNomina', $idTipoNomina)
            ->value('NomTipoNomina');

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        empty($empleado) ? $empleado = 'No se encontró el empleado' : $empleado = $empleado->Nombre . ' ' . $empleado->Apellidos;

        $tiposNomina = LimiteCredito::all();

        return view('InterfazCreditos.InterfazCreditos', compact('tiposNomina', 'chkNomina', 'numNomina', 'fecha1', 
            'fecha2', 'idTipoNomina', 'creditos', 'nomTipoNomina', 'empleado', 'totalAdeudo'));
    }

    public function InterfazarCreditos($fecha1, $fecha2, $idTipoNomina, $numNomina){
        try {
            DB::beginTransaction();// inicio de transacciones

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
                    select a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda, d.NomCiudad, 
                    SUM(a.ImporteCredito), f.IdTicket, ". $idHistorialCredito ." 
                    from DatCreditos as a 
                    left join CatTiendas as b on b.IdTienda=a.IdTienda 
                    left join CatEmpleados as c on c.NumNomina=a.NumNomina 
                    left join CatCiudades as d on d.IdCiudad=b.IdCiudad 
                    left join DatEncabezado as f on f.IdEncabezado=a.IdEncabezado 
                    where a.StatusCredito = 0 
                    and a.StatusVenta = 0 
                    and a.NumNomina = ". $numNomina ."
                    and a.Interfazado is null 
                    and CAST(a.FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'
                    group by a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda, d.NomCiudad, f.IdTicket
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
                    select a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda, d.NomCiudad, 
                    SUM(a.ImporteCredito), f.IdTicket, ". $idHistorialCredito ." 
                    from DatCreditos as a 
                    left join CatTiendas as b on b.IdTienda=a.IdTienda 
                    left join CatEmpleados as c on c.NumNomina=a.NumNomina 
                    left join CatCiudades as d on d.IdCiudad=b.IdCiudad 
                    left join DatEncabezado as f on f.IdEncabezado=a.IdEncabezado 
                    where a.StatusCredito = 0 
                    and a.StatusVenta = 0 
                    and c.TipoNomina = ". $idTipoNomina ."
                    and a.Interfazado is null 
                    and CAST(a.FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."'
                    group by a.NumNomina, a.FechaVenta, a.IdEncabezado, a.IdTienda, b.NomTienda, d.NomCiudad, f.IdTicket
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
            DB::rollback();// hubo algun error
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit(); // todo salio bien
        return back()->with('IdentificadorSparh', 'Identificador SPARH: ' . $idHistorialCredito);
    }
}