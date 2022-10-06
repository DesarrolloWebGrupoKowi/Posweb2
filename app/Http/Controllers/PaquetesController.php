<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CatPaquete;
use App\Models\DatPaquete;
use App\Models\Articulo;

class PaquetesController extends Controller
{
    public function VerPaquetes(Request $request){
        $nomPaquete = $request->nomPaquete;

        $paquetes = CatPaquete::with(['Usuario' => function($empleado){
            $empleado->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatUsuarios.NumNomina');
        }, 'ArticulosPaquete' => function($articulos){
            $articulos->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatPaquetes.CodArticulo');
        }])
            ->where('NomPaquete', 'like', '%'.$nomPaquete.'%')
            ->where('Status', 0)
            ->get();

        $paquetesActivos = CatPaquete::where('Status', 0)
            ->count();

        //return $paquetes;

        return view('Paquetes.VerPaquetes', compact('paquetes', 'nomPaquete', 'paquetesActivos'));
    }
    public function CatPaquetes(Request $request){
        $nomPaquete = $request->nomPaquete;

        return view('Paquetes.CatPaquetes', compact('nomPaquete'));
    }

    public function BuscarCodArticuloPaquqete(Request $request){
        $codArticulo = $request->codArticulo;

        $articulo =  DB::table('CatArticulos as a')
            ->select('a.NomArticulo', 'b.PrecioArticulo')
            ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.CodArticulo', $codArticulo)
            ->first();

        $pArticulo = empty($articulo) ? '' : $articulo->NomArticulo . ' - $'.$articulo->PrecioArticulo; 

        return $pArticulo;
    }

    public function GuardarPaquete(Request $request){
        $nomPaquete = $request->nomPaquete;
        $importePaquete = $request->importePaquete;

        $codsArticulo = $request->CodArticulo;
        $cantsArticulo = $request->CantArticulo;
        $preciosArticulo = $request->PrecioArticulo;

        try {
            DB::beginTransaction();

            $catPaquete = new CatPaquete();
            $catPaquete->NomPaquete = strtoupper($nomPaquete);
            $catPaquete->ImportePaquete = $importePaquete;
            $catPaquete->FechaCreacion = date('d-m-Y H:i:s');
            $catPaquete->IdUsuario = Auth::user()->IdUsuario;
            $catPaquete->Status = 0;
            $catPaquete->save();

            foreach ($codsArticulo as $key => $codArticulo) {
                $datPaquete = new DatPaquete();
                $datPaquete->IdPaquete = $catPaquete->IdPaquete;
                $datPaquete->IdListaPrecio = 1;
                $datPaquete->CodArticulo = $codArticulo;
                $datPaquete->CantArticulo = $cantsArticulo[$key]; 
                $datPaquete->PrecioArticulo = $preciosArticulo[$key];
                $datPaquete->ImporteArticulo = ($cantsArticulo[$key] * $preciosArticulo[$key]);
                $datPaquete->save();
            }

            DB::commit();

            return back()->with('msjAdd', 'Se Agrego el Paquete: '.$catPaquete->NomPaquete);

        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error : '. $th->getMessage());
            DB::rollback();
        }
    }

    public function EditarPaquete(Request $request, $idPaquete){
        try {
            DB::beginTransaction();

            $paquete = DB::table('CatPaquetes as a')
                ->leftJoin('DatPaquetes as b', 'a.IdPaquete', 'b.IdPaquete')
                ->leftJoin('CatArticulos as c', 'c.CodArticulo', 'b.CodArticulo')
                ->leftJoin('DatPrecios as d', 'd.CodArticulo', 'b.CodArticulo')
                ->select('a.*', 'b.*', 'c.*', 'd.PrecioArticulo as PrecioLista')
                ->where('d.IdListaPrecio', 1)
                ->where('a.IdPaquete', $idPaquete)
                ->where('a.Status', 0)
                ->whereNull('FechaEliminacion')
                ->get();

            if($paquete->count() == 0){
                return back()->with('msjdelete', 'No Existe el Paquete con el Id: '. $idPaquete);
            }

            $nomPaquete = CatPaquete::where('IdPaquete', $idPaquete)
                ->where('Status', 0)
                ->whereNull('FechaEliminacion')
                ->value('NomPaquete');

            $importePaquete = CatPaquete::where('IdPaquete', $idPaquete)
                ->where('Status', 0)
                ->whereNull('FechaEliminacion')
                ->value('ImportePaquete');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMessage());
        }

        //return $paquete;

        return view('Paquetes.EditarPaquete', compact('paquete', 'nomPaquete', 'importePaquete', 'idPaquete'));
    }

    
    public function EditarPaqueteExistente(Request $request, $idPaquete){
        $importePaquete = $request->importePaquete;

        $codsArticulo = $request->CodArticulo;
        $cantsArticulo = $request->CantArticulo;
        $preciosArticulo = $request->PrecioArticulo;

        try {
            DB::beginTransaction();
            $nomPaquete = CatPaquete::where('IdPaquete', $idPaquete)
                ->where('Status', 0)
                ->whereNull('FechaEliminacion')
                ->value('NomPaquete');

            CatPaquete::where('IdPaquete', $idPaquete)
                ->update([
                    'FechaEliminacion' => date('d-m-Y H:i:s'),
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'Status' => 1
                ]);

            $catPaquete = new CatPaquete();
            $catPaquete->NomPaquete = strtoupper($nomPaquete);
            $catPaquete->ImportePaquete = $importePaquete;
            $catPaquete->FechaCreacion = date('d-m-Y H:i:s');
            $catPaquete->IdUsuario = Auth::user()->IdUsuario;
            $catPaquete->Status = 0;
            $catPaquete->save();
    
            foreach ($codsArticulo as $key => $codArticulo) {
                $datPaquete = new DatPaquete();
                $datPaquete->IdPaquete = $catPaquete->IdPaquete;
                $datPaquete->IdListaPrecio = 1;
                $datPaquete->CodArticulo = $codArticulo;
                $datPaquete->CantArticulo = $cantsArticulo[$key]; 
                $datPaquete->PrecioArticulo = $preciosArticulo[$key];
                $datPaquete->ImporteArticulo = ($cantsArticulo[$key] * $preciosArticulo[$key]);
                $datPaquete->save();
            }

            DB::commit();
            return redirect('VerPaquetes')->with('msjAdd', 'Se Edito: ' .$nomPaquete);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMessage());
        }
    }

    public function EliminarPaquete($idPaquete){
        try {
            $nomPaquete = CatPaquete::where('IdPaquete', $idPaquete)
                ->value('NomPaquete');

            DB::beginTransaction();
            CatPaquete::where('IdPaquete', $idPaquete)
                ->update([
                    'FechaEliminacion' => date('d-m-Y H:i:s'),
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'Status' => 1
                ]);
                DB::commit();

            return back()->with('msjdelete', 'Se Elimino: ' .$nomPaquete);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMessage());
        }
    }
}
