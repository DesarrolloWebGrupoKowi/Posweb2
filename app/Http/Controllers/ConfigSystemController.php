<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigSystemController extends Controller
{
    public function Index(Request $request)
    {
        $comando = 'c:\windows\system32\cmd.exe /c C:\inetpub\wwwroot\posweb2\public\clone.bat';

        exec($comando, $salida, $codigoSalida);

        // dump("El código de salida fue: " . $codigoSalida);
        // dump("Ahora imprimiré las líneas de salida:");
        // dump($salida);

        return redirect('/');
    }
}
