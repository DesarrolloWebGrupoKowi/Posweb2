<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;
use File;
use App\Models\Usuario;
use App\Models\UsuarioTienda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Session;
use App\Models\TipoMenu;
use App\Models\Menu;
use App\Models\TipoUsuario;
use App\Models\VtMenuTipoUsuario;
use App\Models\ItemCloudTable;
use App\Models\CentroCosto;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;
use App\Models\Tienda;
use App\Models\ListaPrecioTienda;
use App\Models\Empleado;
use PDF;
use Response;
use App\Models\SolicitudFactura;
use App\Models\ConstanciaSituacionFiscal;
use App\Models\MonederoElectronico;
use App\Models\DatMonederoAcumulado;
use App\Models\DatTipoPago;
use App\Models\DatEncabezado;
use App\Models\CorreoTienda;
use Mail;
use App\Models\CapRecepcion;

class PruebasController extends Controller
{
    public function pruebas(Request $request){
        $recepcion = CapRecepcion::with(['DetalleRecepcion' => function ($query){
            $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo');
        }])
            ->where('IdTienda', 1)
            ->where('IdCapRecepcion', 2)
            ->first();
        
        return $recepcion;

        $identityEncabezado = DB::select("select IDENT_CURRENT('DatEncabezado') as identityMax");

        foreach ($identityEncabezado as $key => $identity) {
            $identityMax = $identity->identityMax;
        }

        return $identityMax+1;

        //ENVIAR CORREO DE MERMA REALIZADA
        try {
            $nomTienda = Tienda::where('IdTienda', 9)
                ->value('NomTienda');

            $correoTienda = CorreoTienda::where('IdTienda', 9)
                ->first();

            $asunto = "NUEVA MERMA EN ". $nomTienda .". FECHA CAPTURA: ". date('d-m-y H:i:s') ." ";
            $mensaje = 'LA TIENDA HA CAPTURADO NUEVA(S) MERMA(S). Usuario: ' . Auth::user()->NomUsuario;

            $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx; cponce@kowi.com.mx; ". $correoTienda->EncargadoCorreo ."; ". $correoTienda->GerenteCorreo ."; ', '".$asunto."', '".$mensaje."'";
            return $enviarCorreo;
            DB::connection('server')->statement($enviarCorreo);
        } catch (\Throwable $th) {
            
        }

        $correosTienda = DB::table('DatCorreosTienda')
                ->where('IdTienda', 6)
                ->first();
                //->pluck('EncargadoCorreo', 'SupervisorCorreo', 'GerenteCorreo');

                $asunto = 'asunto';
                $mensaje = 'mensaje';

                $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx; cponce@kowi.com.mx; kwi@jd-.com;" . $correosTienda->EncargadoCorreo . 
                $correosTienda->GerenteCorreo . $correosTienda->SupervisorCorreo . $correosTienda->AdministrativaCorreo . $correosTienda->FacturistaCorreo
                . Auth::user()->Correo ."', '".$asunto."', '".$mensaje."'";
            
        return $enviarCorreo;
        
        return strftime('%d %B %Y', strtotime('2022-10-04'));

        $nomOrigenTienda = 'KOWI EXPRESS SAN JOSE DEL CABO';
        $nomDestinoTienda = 'KOWI EXPRESS TALAMANTE';


        $mensaje = nl2br("Envia: $nomOrigenTienda \nRecibe: $nomDestinoTienda \nId de Recepción: 30");

        return $mensaje;

        $referencia = 'KOWI EXPRESS SAN JOSE DEL CABO';

        return $referencia == 'MANUAL' ? 3 : 1;

        $datTipoPago = DatTipoPago::where('IdEncabezado', 9110602)
                    ->get();

        $totalVenta = DatEncabezado::where('IdEncabezado', 9110602)
            ->value('ImporteVenta');
                
        $restante = 0;
        foreach ($datTipoPago as $key => $pagos) {
            $restanteVenta = abs($restante) - $totalVenta;
            $totalVenta = abs($restanteVenta) - $pagos->Pago;
            echo -$totalVenta;
            echo '<br>';
        }

        return 1;

        $fechaIngreso = Empleado::where('NumNomina', 23604)
            ->value('Fecha_Ingreso');

        


        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        return $idTienda;

        $dlpt = DB::table('DatListaPrecioTienda')
                ->where('IdTienda', $idTienda)
                ->get();

        foreach ($dlpt as $key => $val) {
            echo $id[$key] = $val->IdListaPrecio; 
        }

        return $id[2];

        $pagoMonedero = 20;
        $pagoRestante = $pagoMonedero;
        
        $monederoEmpleado = DatMonederoAcumulado::where('NumNomina', 33)
            ->whereRaw("'".date('Y-m-d')."' <= cast(FechaExpiracion as date)")
            ->where('MonederoPorGastar', '<>', 0)
            ->orderBy('FechaExpiracion')
            ->get();

        echo 'Pago Monedero: ' . $pagoMonedero;
        echo '<br>';
        foreach ($monederoEmpleado as $key => $mEmpleado) {
            $pagoMonedero = $pagoRestante;
            $pagoRestante = $mEmpleado->MonederoPorGastar - abs($pagoMonedero);

            $gastoMonedero = $pagoRestante <= 0 ? $mEmpleado->MonederoPorGastar : abs($pagoMonedero); 
            $monederoxGastar = $mEmpleado->MonederoPorGastar - $gastoMonedero;
            echo $mEmpleado->IdEncabezado . ' Existente: ' . $mEmpleado->MonederoGenerado .' gasto => ' . -$gastoMonedero . ' por gastar => ' . $monederoxGastar;
            echo '<br>';

            if($pagoRestante >= 0){
                break;
            }
        }



        //$monederoGenerado = 41;
        //$monederoEmpleado = 160;

        //$faltanteMaximo = 200 - $monederoEmpleado;

        //$monederoGenerado + $monederoEmpleado > 200 ? $monederoGenerado = $faltanteMaximo : $monederoGenerado = $monederoGenerado;

        //return $monederoGenerado;

        /*$date = date('Y-m-d');

        $fecha = strtotime(date('Y-m-d')."+ 30 days");

        $fechaExpiracion = date('d-m-Y', $fecha);

        return $fechaExpiracion;*/

        /*
        if(Gate::allows('isAdmin')){
            //dd('Usuario Admin');
            dd(session('Usuario'));
        }
        else{
            //dd('otro tipo de usuario');
            dd(session('Usuario'));
        }

        */
        //return $request->session()->all();

        //return Usuario::with('usuarioTienda')->find(Auth::user()->IdUsuario);

        //return Auth::user()->usuarioTienda;

        /*
        $usuarios = Usuario::find(30058);
        return $usuarios->menusTipoUsuario;

        //return Auth::user()->menusTipoUsuario;
        /*
        foreach ($usuarios as $usuario) {
            echo $usuario->NomUsuario;
            echo '<br>';
            foreach ($usuario->menusTipoUsuario as $menu) {
                echo $menu->NomMenu;
                echo '<br>';
            }
        }
        */

        
        //return Auth::user()->vistaMenusTipoUsuario;

        //return Usuario::with('vistaMenusTipoUsuario')->find(30064);
        //$usuario = Usuario::with('vistaMenusTipoUsuario')->get();
        //return $usuario;

        //$tipoUsuario = TipoUsuario::with('menus')->find(1);
        //return $tipoUsuario;
        //return $tipoMenu->tipoUsuario;

        //foreach ($users as $user) {
        //    echo $user->Link;
        //}
        //$filtro = $request->filtro;

        //$usuarios = DB::table('CatUsuarios')
        //                ->where('NomUsuario', 'like', '%'.$filtro.'%')
        //               ->get();
        //$usuarios = Usuario::where('Status', 0)
        //                   ->get();
        //return view('pruebas.promesa');

        //return date('y-m-d');

        //return view('Pruebas.pruebas');
        //return $now = new DateTime();
        //$fecha = date('d-m-y');
       // return $fecha;

        /*
        $xxkw_items = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
                                             ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                                             ->get();
        return $xxkw_items;
*/

    //$usuarioAuth = Usuario::with('usuarioTienda')->find(Auth::user()->IdTipoUsuario);
    //return $usuarioAuth->tipoUsuario->NomTipoUsuario;
    //return 'hope';
/*
    $centroCostos = DB::connection('Cloud_Tables')->table('XXKW_CENTRO_COSTO')
                        ->where('LIBRO', 'LIBRO_02_ALIMENTOS_KOWI')
                        ->where('DESCRIPTION', 'like', '%KE%')
                        ->get();
    return $centroCostos;
    */
/*
    return CentroCosto::where('LIBRO', 'LIBRO_02_ALIMENTOS_KOWI')
                        ->where('DESCRIPTION', 'like', '%KE%')
                        ->get();
    
        */
        //return date('d-m-Y H:i:s');

    //return Auth::user()->usuarioTienda->IdTienda;
    /*$venta = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('IdTienda', 5)
                    ->where('a.IdEncabezado', 1)
                    ->where('a.IdTicket', 1)
                    ->get();
    return $venta->count();

    $tienda = DB::table('CatTiendas as a')
                    ->leftJoin('CatCiudades as b', 'b.IdCiudad', 'a.IdCiudad')
                    ->leftJoin('CatEstados as c', 'c.IdEstado', 'b.IdEstado')
                    ->where('a.IdTienda', 1 )
                    ->first();

        


    $nombreImpresora = "PosWeb2";
        $connector = new WindowsPrintConnector($nombreImpresora);
        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("ALIMENTOS KOWI SA DE CV\n");
        $impresora->text("AKO971007558\n");
        $impresora->text("CARRETERA FEDERAL MEXICO-NOGALES KM 1788\n");
        $impresora->text("NAVOJOA, SONORA C.P. 85230\n");
        $impresora->text("EXPEDIDO EN:\n");
        $impresora->text($tienda->NomTienda."\n");
        $impresora->text("BLVRD. RAFAEL J ALMADA ENTRE AV. MARIANO JIMENEZ\n");
        $impresora->text($tienda->NomCiudad.", ".$tienda->NomEstado."\n");
        $impresora->text($tienda->Telefono."\n");
        $impresora->text("=======================================\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("TICKET: 1\n");
        $impresora->text("CAJA: 1\n");
        $impresora->text("CAJERO: KOMANDER RIOS\n");
        $impresora->text("ARTICULOS: ".$venta->count()."\n");
        $impresora->text("FECHA: 2022-05-02 10:32:57\n");
        $impresora->text("FOLIO CUPÓN: ?\n");
        $impresora->text("=======================================\n");
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("ARTICULO           CANT    PREC    IMPORTE\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("TOCINO             9.999   160     1999.99\n");
        $impresora->text("=======================================\n");
        $impresora->feed(1);
        $impresora->setJustification(Printer::JUSTIFY_RIGHT);
        $impresora->text("SUBTOTAL 1999.99\n");
        $impresora->text("IVA 1999.99\n");
        $impresora->text("MONEDERO 0000.00\n");
        $impresora->text("DESCUENTO 0000.00\n");
        $impresora->text("USTED PAGO 1999.99\n");
        $impresora->text("CAMBIO 1999.99\n");
        $impresora->text("================\n");
        $impresora->text("TOTAL 9999.999\n");
        $impresora->text("================\n");
        $impresora->feed(2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("¡ALTA CALIDAD EN CARNE DE CERDO!\n");
        $impresora->text("WWW.KOWI.COM.MX\n");
        $impresora->text("¡GRACIAS POR SU COMPRA!\n");
        $impresora->feed(5);
        $impresora->cut();
        $impresora->close();
    //return view('Pruebas.Pruebas');
    */

    //$variable = "50";
    //$var = $this->pruebas2($variable);
    //return view('Pruebas.Pruebas');

    /*$idListasPrecio = ListaPrecioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->select('IdListaPrecio')
                            ->get();

    //return $idListasPrecio;

    $idListasPrecio = ListaPrecioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                                            ->select('IdListaPrecio')
                                            ->get();


        foreach ($idListasPrecio as $key => $itemIdListaPrecio) {
            $id[$key] = $itemIdListaPrecio->IdListaPrecio;
        }

        $dlpt = DB::table('DatListaPrecioTienda')
                ->where('IdTienda', 9)
                ->get();

                return $dlpt;*/

                //return view('Pruebas.pruebas');

    //DB::statement("exec SP_GENERAR_TICKET_CORTE 91291, 9, '28/06/2022'");
    //DB::statement('exec SP_GENERAR_TICKET_CORTE("91291", "9", "28/06/2022")');
    //DB::statement('exec SP_GENERAR_TICKET_CORTE(?,?,?)', array('91291', '9', "'28/06/2022'"));

//    DB::select("exec SP_GENERAR_TICKET_CORTE 91291, 9, '28/06/2022'");

    //$n = explode(' ', Auth::user()->Empleado->Nombre);
    //$a = explode(' ', Auth::user()->Empleado->Apellidos);
    //$nombre = $n[0];
    //$apellido = $a[0];
    //return $nombre . " " . $apellido;

    /*
        $carpeta = "meny";              
        $file = $request->file('meny'); 
        $nombre_archivo = $file->getClientOriginalName();
        Storage::disk('ftp')->put($carpeta.'/'.$nombre_archivo.'/', \File::get($file));
    */

    /*$pagos = [150, 200, 6.5];
    $precio = 155;
    $total = 356.5;
    $cantidad = 2.3;

    for ($i=0; $i < count($pagos); $i++) { 
        $cantPorPago = ($pagos[$i] * $cantidad) / $total;
        echo number_format($cantPorPago, 4);
        echo '<br>';
    }

    //return view('Pruebas.pruebas');
    */
    }

    public function SubirArchivo(Request $request){

        $pdf = $request->file('constanciaFiscal');

        $nomArchivo = $pdf->getClientOriginalName();

        $pdfEncoded = chunk_split(base64_encode(file_get_contents($pdf)));

        //return $pdfEncoded;

        $pdfPos = strlen($pdfEncoded)/10;

        $pos = ceil($pdfPos);

        //return $pos;

        $constancia = str_split($pdfEncoded, $pos);

        return $constancia;


        //$constancia = DB::table('SolicitudFactura as a')
                    //->leftJoin('ConstanciaSituacionFiscal as b', 'b.IdSolicitudFactura', 'a.IdSolicitudFactura')
                    //->where('a.IdSolicitudFactura', 5291394)
                    //->first();

        //return $constancia;

        //$base64Pdf = $constancia->Constancia1.$constancia->Constancia2.$constancia->Constancia3.$constancia->Constancia4.$constancia->Constancia5.$constancia->Constancia6.$constancia->Constancia7.$constancia->Constancia8.$constancia->Constancia9.$constancia->Constancia10;

        //return $constancia;

        $nomArchivo = 'menito.pdf';

        $pdfDecoded = base64_decode($base64Pdf);
        
        $pdf2 = fopen('c:\menito\\'.$constancia->NomConstancia.'', 'w');

        fwrite($pdf2, $pdfDecoded);

        fclose($pdf2);

        $path = 'c:\menito\\'.$constancia->NomConstancia.'';

        return response()->file($path);
        
    }


    public function pruebas2($variable){
       return $variable+5;
    }

    public function promesas(Request $request){
        $nomUsuario = $request->NomUsuario;
        $usuarios = Usuario::where('NomUsuario', 'like', '%'.$nomUsuario.'%')
                            ->where('Status', 0)
                            ->get();
        return $usuarios;
    }
}
