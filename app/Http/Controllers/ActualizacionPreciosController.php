<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActualizacionPreciosController extends Controller
{
    public function index(Request $request)
    {
        // Buscamos el archivo de la bascula
        $nombre_fichero = 'C:\Program Files (x86)\AdminScale\DATA\KOWI.mdb';

        if (!file_exists($nombre_fichero)) {
            return back()->with('msjdelete', 'No se encontro la base de datos Access de la bascula.');
        }

        // $conexion = odbc_connect("bascula", "sa", "Caradeperr0n0essa");
        $conexion = odbc_connect("KOWI", "", "");

        // ============================= Insert en ACCESS
        // return $sql = "INSERT INTO Articulos (codigo, nombre, tipoplu, precio, impuesto, fcad, depto, grupo, pendiente, numplu, numplu2, actualizado, user1, user2, user3, ingredientes)
        //     VALUES('100601', 'PECHOS', '1', '0', '1', '0', '0', '0', '0', '2', '0', '11/11/2023', '0', '0', '0', '0')";
        // odbc_exec($conexion, $sql);

        // ============================= DELETE en ACCESS
        $sql = "DELETE FROM PLUs";
        odbc_exec($conexion, $sql);

        $sql = "DELETE FROM Articulos";
        odbc_exec($conexion, $sql);

        try {
            # Se forma la cadena de conexión
            $conexion = odbc_connect("KOWI", "", "");

            if (!$conexion)
                return back()->with('msjdelete', 'No se pudo completar la conexión.');

            $articulos = Articulo::select('CatArticulos.CodArticulo', 'CatArticulos.NomArticulo', 'CatArticulos.Iva', 'CatArticulos.CodEtiqueta', 'DatPrecios.PrecioArticulo')
                ->leftJoin('DatPrecios', 'DatPrecios.CodArticulo', 'CatArticulos.CodArticulo')
                ->where('DatPrecios.IdListaPrecio', '1')
                ->get();

            foreach ($articulos as $articulo) {
                $precio = (int)$articulo->PrecioArticulo;
                try {
                    $sql = "INSERT INTO Articulos (codigo, nombre, tipoplu, precio, impuesto, fcad, depto, grupo, pendiente, numplu, numplu2, actualizado, user1, user2, user3, ingredientes)
                    VALUES('$articulo->CodEtiqueta', '$articulo->NomArticulo', '1', '$precio', '0', '0', '0', '0', '0', '$articulo->CodEtiqueta', '0', '11/11/2023', '0', '0', '0', '0')";

                    odbc_exec($conexion, $sql);
                } catch (\Throwable $th) {
                    return $th;
                }
            }

            // $ssql = "SELECT * FROM Articulos";
            // if ($rs_access = odbc_exec($conexion, $ssql)) {
            //     dump($ssql);
            //     dump($rs_access);

            //     while ($fila = odbc_fetch_object($rs_access)) {
            //         dump($fila->nombre);
            //     }

            //     return 'andamos bien';

            //     return redirect('Dashboard')->with('msjAdd', 'La sentencia se ejecutó correctamente');
            // } else {
            //     return redirect('Dashboard')->with('msjdelete', 'Error al ejecutar la sentencia SQL.');
            // }

            return redirect('Dashboard')->with('msjAdd', 'La sentencia se ejecutó correctamente');
            return Auth::user();
        } catch (\Throwable $th) {
            return $th;
            return redirect('Dashboard')->with('msjdelete', 'Error en la transacción.');
        }


        // $ssql = "SELECT * FROM Articulos";
        // if ($rs_access = odbc_exec($conexion, $ssql)) {
        //     dump($ssql);
        //     dump($rs_access);

        //     while ($fila = odbc_fetch_object($rs_access)) {
        //         dump($fila->nombre);
        //     }

        //     return 'andamos bien';

        //     return redirect('Dashboard')->with('msjAdd', 'La sentencia se ejecutó correctamente');
        // } else {
        //     return redirect('Dashboard')->with('msjdelete', 'Error al ejecutar la sentencia SQL.');
        // }
    }
}
