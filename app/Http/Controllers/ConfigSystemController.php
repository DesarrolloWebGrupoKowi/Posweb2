<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigSystemController extends Controller
{
    public function Index(Request $request)
    {
        exec("C:\\inetpub\\wwwroot\\Descarga_Catalogos.bat");
        exec("C:\\inetpub\\wwwroot\\Descarga_CatClientes.bat");
        exec("C:\\inetpub\\wwwroot\\Descarga_Monedero.bat");
        exec("C:\\inetpub\\wwwroot\\Subir_Ventas.bat");

        // exec($comando, $salida, $codigoSalida);

        // dump("El código de salida fue: " . $codigoSalida);
        // dump("Ahora imprimiré las líneas de salida:");
        // dump($salida);

        return redirect('/');
    }
}
