<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\Cliente;
use App\Models\DatEncabezado;
use App\Models\Ciudad;
use App\Models\Estado;
use App\Models\SolicitudFactura;
use App\Models\CorteTienda;
use App\Models\UsoCFDI;
use App\Models\TipoPago;
use App\Models\NotificacionClienteCloud;
use App\Models\ConstanciaSituacionFiscal;

class SolicitudFacturaController extends Controller
{
    public function SolicitudFactura(Request $request){
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $usosCFDI = UsoCFDI::where('Status', 0)
                    ->get();

        $tienda = Tienda::find($idTienda);

        $estadoTienda = Ciudad::where('IdCiudad', $tienda->IdCiudad)
                            ->first();

        $rfcCliente =  $request->rfcCliente;

        $numTicket = $request->numTicket;

        $chkTipoPagoTicket = $request->chkTipoPagoTicket;

        $cliente = DB::table('CatClientes as a')
                        ->leftJoin('CatClienteEmail as b', 'b.IdClienteCloud', 'a.IdClienteCloud')
                        ->where('a.RFC', $rfcCliente)
                        ->where('a.Location_Status', 'A')
                        ->whereNotNull('a.RFC')
                        ->where('a.Codigo_Envio', 'BILL_TO')
                        ->get();

        $nomCliente = Cliente::where('RFC', $rfcCliente)
                        ->where('Location_Status', 'A')
                        ->whereNotNull('RFC')
                        ->first();

        $ticket = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', date('Y-m-d'))
                    ->where('IdTicket', $numTicket)
                    ->first();

        $tiposPagoTicket = DB::table('DatCortesTienda as a')
                    ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
                    ->select([
                        DB::raw('a.IdTipoPago'),
                        DB::raw('b.NomTipoPago'),
                        DB::raw('sum(a.ImporteArticulo) as ImporteArticulo'),
                        DB::raw('a.IdSolicitudFactura')
                    ])
                    ->where('a.IdEncabezado', empty($ticket->IdEncabezado) ? 0 : $ticket->IdEncabezado)
                    ->where('a.IdTienda', $idTienda)
                    ->groupBy('a.IdTipoPago', 'b.NomTipoPago', 'a.IdSolicitudFactura')
                    ->get();

        $tieneSolFe = DB::table('DatCortesTienda')
                        ->select('IdSolicitudFactura')
                        ->where('IdTienda', $idTienda)
                        ->where('IdEncabezado', empty($ticket->IdEncabezado) ? 0 : $ticket->IdEncabezado)
                        ->whereNull('IdSolicitudFactura')
                        ->get();

        $auxTicketFacturado = $tieneSolFe->count();

        $tiposPagoDistinct = DB::table('DatCortesTienda as a')
                            ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
                            ->select('a.IdTipoPago')
                            ->where('a.IdEncabezado', empty($ticket->IdEncabezado) ? 0 : $ticket->IdEncabezado)
                            ->where('a.IdTienda', $idTienda)
                            ->distinct('a.IdTipoPago')
                            ->get();

        $tiposPagoDistinct->count() > 1 ? $banderaMultiPagoFact = 0 : $banderaMultiPagoFact = 1;

        //return $ticket;

        return view('SolicitudFactura.SolicitudFactura', compact('auxTicketFacturado', 'tienda', 'rfcCliente', 'cliente', 'numTicket', 'ticket', 'estadoTienda', 'nomCliente', 'usosCFDI', 'tiposPagoTicket', 'banderaMultiPagoFact', 'chkTipoPagoTicket'));
    }

    public function VerSolicitudesFactura(Request $request){
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $fechaSolicitud = empty($request->fechaSolicitud) ? date('Y-m-d') : $request->fechaSolicitud;

        //empty($fechaSolicitud) ? $fechaSolicitud = date('Y-m-d') : $fechaSolicitud = $request->fechaSolicitud;

        $solicitudesFactura = SolicitudFactura::with('ConstanciaSituacionFiscal')
                            ->where('IdTienda', $idTienda)
                            ->whereDate('FechaSolicitud', $fechaSolicitud)
                            ->get();

        //return $solicitudesFactura;

        return view('SolicitudFactura.VerSolicitudesFactura', compact('solicitudesFactura', 'fechaSolicitud'));
    }

    public function VerificarSolicitudFactura($idTicket, $rfcCliente, $bill_To, $correo){
        $usosCFDI = UsoCFDI::where('Status', 0)
                    ->get();

        if($correo == 'NoTieneCorreo'){
            $cliente = DB::table('CatClientes as a')
                        ->leftJoin('CatClienteEmail as b', 'b.IdClienteCloud', 'a.IdClienteCloud')
                        ->where('a.RFC', $rfcCliente)
                        ->where('a.Location_Status', 'A')
                        ->whereNotNull('a.RFC')
                        ->where('Bill_To', $bill_To)
                        ->first();
        }
        else{
            $cliente = DB::table('CatClientes as a')
                        ->leftJoin('CatClienteEmail as b', 'b.IdClienteCloud', 'a.IdClienteCloud')
                        ->where('a.RFC', $rfcCliente)
                        ->where('a.Location_Status', 'A')
                        ->whereNotNull('a.RFC')
                        ->where('Bill_To', $bill_To)
                        ->where('b.Email', $correo)
                        ->first();
        }

        $ticket = DatEncabezado::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->whereDate('FechaVenta', date('Y-m-d'))
                        ->where('IdTicket', $idTicket)
                        ->first();

        $idEncabezado = $ticket->IdEncabezado;

        $tiposPagoTicket = DB::table('DatCortesTienda as a')
                        ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
                        ->select([
                            DB::raw('a.IdTipoPago'),
                            DB::raw('b.NomTipoPago'),
                            DB::raw('sum(a.ImporteArticulo) as ImporteArticulo'),
                            DB::raw('a.IdSolicitudFactura')
                        ])
                        ->where('a.IdEncabezado', $ticket->IdEncabezado)
                        ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->groupBy('a.IdTipoPago', 'b.NomTipoPago', 'a.IdSolicitudFactura')
                        ->get();

        $nomCliente = Cliente::where('RFC', $rfcCliente)
                        ->where('Location_Status', 'A')
                        ->whereNotNull('RFC')
                        ->first();

        $tiposPagoDistinct = DB::table('DatCortesTienda as a')
                            ->leftJoin('CatTipoPago as b', 'b.IdTipoPago', 'a.IdTipoPago')
                            ->select('a.IdTipoPago')
                            ->where('a.IdEncabezado', $ticket->IdEncabezado)
                            ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->distinct('a.IdTipoPago')
                            ->get();

        $tiposPagoDistinct->count() > 1 ? $banderaMultiPagoFact = 0 : $banderaMultiPagoFact = 1;

        //return $tiposPagoTicket;

        return view('SolicitudFactura.VerificarSolicitudFactura', compact('rfcCliente', 'bill_To', 'cliente', 'ticket', 'tiposPagoTicket', 'nomCliente', 'banderaMultiPagoFact', 'usosCFDI'));
    }

    public function GuardarSolicitudFactura(Request $request){
        //return $request->all();
        $request->validate([
            'calle' => 'required',
            'numExt' => 'required',
            'colonia' => 'required',
            'ciudad' => 'required',
            'municipio' => 'required',
            'estado' => 'required',
            'codigoPostal' => 'required',
            'email' => 'required | email',
            'cfdi' => 'required'
        ]);

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $numTicket = $request->numTicket;

        $ticket = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', date('Y-m-d'))
                    ->where('IdTicket', $numTicket)
                    ->first();

        $idEncabezado = $ticket->IdEncabezado;

        $cliente = Cliente::where('Bill_To', $request->bill_To)
                    ->where('Location_Status', 'A')
                    ->whereNotNull('RFC')
                    ->where('Codigo_Envio', 'Bill_To')
                    ->first();

        $cliente->TipoPersona == 'ORGANIZATION' ? $tipoPersona = 1 : $tipoPersona = 0;

        $tiposPagoFactura = $request->chkTipoPagoTicket;

        $auxTiposPago = CorteTienda::where('IdEncabezado', $ticket->IdEncabezado)
                        ->select('IdTipoPago')
                        ->distinct('IdTipoPago')
                        ->get();

        
        //SOLICITUDES CON UN SOLO METODO DE PAGO EN CLIENTE EXISTENTE
        if($auxTiposPago->count() == 1){
            try {
                DB::beginTransaction();
    
                $editarInfo = $request->chkEdit;

                if(!empty($editarInfo)){
                    NotificacionClienteCloud::insert([
                        'IdTienda' => $idTienda,
                        'IdClienteCloud' => $cliente->IdClienteCloud,
                        'NomCliente' => $cliente->NomCliente,
                        'RFC' => $cliente->RFC,
                        'IdMovimiento' => 1,
                        'Status'=> 0
                    ]);

                    $idNotificacion = NotificacionClienteCloud::where('IdTienda', $idTienda)
                        ->max('IdDatNotificacionesClienteCloud');

                    foreach ($editarInfo as $key => $campo) {
                        NotificacionClienteCloud::where('IdDatNotificacionesClienteCloud', $idNotificacion)
                                        ->update([
                                            $campo => $campo == 'email' ? strtolower($request->$campo) : strtoupper($request->$campo)
                                        ]);
                    }
                }
            
                $incrementa = SolicitudFactura::where('IdTienda', $idTienda)
                            ->max('Id')+1;

                $idSolicitudFactura = $incrementa . $ticket->IdEncabezado;

                $pdf = $request->file('cSituacionFiscal');
                if(!empty($pdf)){
                    $nomArchivo = $pdf->getClientOriginalName();

                    $constanciaEncoded = chunk_split(base64_encode(file_get_contents($pdf)));

                    $pdfPos = strlen($constanciaEncoded)/10;

                    $pos = ceil($pdfPos);

                    $constancia = str_split($constanciaEncoded, $pos);

                    //return $constancia;

                    ConstanciaSituacionFiscal::insert([
                        'IdSolicitudFactura' => $idSolicitudFactura,
                        'NomConstancia' => $nomArchivo
                    ]);

                    for ($i=0; $i < count($constancia) ; $i++) { 
                        $campoConstancia = 'Constancia' . ($i + 1);
                        ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
                                            ->update([
                                                $campoConstancia => $constancia[$i]
                                            ]);
                    }

                    //return $constanciaEncoded;
                }

                foreach ($auxTiposPago as $key => $auxTipoPago) {
                    $tipoPago = $auxTipoPago->IdTipoPago;
                }

                DB::table('SolicitudFactura')
                    ->insert([
                        'IdSolicitudFactura' => $idSolicitudFactura,
                        'FechaSolicitud' => date('d-m-Y H:i:s'),
                        'IdEncabezado' => $ticket->IdEncabezado,
                        'IdTienda' => $idTienda,
                        'IdTipoPago' => $tipoPago,
                        'IdClienteCloud' => empty($editarInfo) && empty($pdf) ? $cliente->IdClienteCloud : null,
                        'TipoPersona' =>$tipoPersona,
                        'RFC' => strtoupper($request->rfcCliente),
                        'NomCliente' => $cliente->NomCliente,
                        'Calle' => strtoupper($request->calle),
                        'NumExt' => strtoupper($request->numExt),
                        'NumInt' => strtoupper($request->numInt),
                        'Colonia' => strtoupper($request->colonia),
                        'Ciudad' => strtoupper($request->ciudad),
                        'Municipio' => strtoupper($request->municipio),
                        'Estado' => strtoupper($request->estado),
                        'Pais' => 'MEXICO',
                        'CodigoPostal' => $request->codigoPostal,
                        'Email' => strtolower($request->email),
                        'Telefono' => $request->telefono,
                        'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                        'IdUsuarioCliente' => null,
                        'Bill_To' => empty($editarInfo) && empty($pdf) ? $cliente->Bill_To : null,
                        'UsoCFDI' => strtoupper($request->cfdi)
                ]);

                $pagosFactura = CorteTienda::where('IdEncabezado', $ticket->IdEncabezado)
                                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                                ->get();

                foreach ($pagosFactura as $j => $pagoFactura) {
                    CorteTienda::where('IdCortesTienda', $pagoFactura->IdCortesTienda)
                            ->update([
                                'IdSolicitudFactura' => $idSolicitudFactura
                            ]);
                }

                DatEncabezado::where('IdEncabezado', $ticket->IdEncabezado)
                                ->update([
                                    'SolicitudFE' => 0
                                ]);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }
    
            DB::commit();
            return redirect('SolicitudFactura')->with('msjAdd', 'Se Realizó la Solicitud de Factura del Ticket: '.$numTicket);
        }
        //SOLICITUDES CON MAS DE 1 METODO DE PAGO EN CLIENTE EXISTENTE
        else{   
            try {
                DB::beginTransaction();
    
                if(empty($tiposPagoFactura)){
                    return back()->with('msjdelete', 'Debe Seleccionar Un Metodo de Pago a Facturar!');
                }
    
                $pagosParaFacturar = TipoPago::with(['CortePago' => function($query) use ($idEncabezado){
                    $query->where('DatCortesTienda.IdEncabezado', $idEncabezado);
                }])
                    ->whereIn('IdTipoPago', $tiposPagoFactura)
                    ->get();
    
                    $editarInfo = $request->chkEdit;
    
                    if(!empty($editarInfo)){
                        NotificacionClienteCloud::insert([
                            'IdTienda' => $idTienda,
                            'IdClienteCloud' => $cliente->IdClienteCloud,
                            'NomCliente' => $cliente->NomCliente,
                            'RFC' => $cliente->RFC,
                            'IdMovimiento' => 1,
                            'Status'=> 0
                        ]);
            
                        $idNotificacion = NotificacionClienteCloud::where('IdTienda', $idTienda)
                                    ->max('IdDatNotificacionesClienteCloud');
            
                        foreach ($editarInfo as $key => $campo) {
                            NotificacionClienteCloud::where('IdDatNotificacionesClienteCloud', $idNotificacion)
                                                ->update([
                                                    $campo => $campo == 'email' ? strtolower($request->$campo) : strtoupper($request->$campo)
                                                ]);
                        }
                    }
                    
                    $pdf = $request->file('cSituacionFiscal');
                    if(!empty($pdf)){
                        $nomArchivo = $pdf->getClientOriginalName();
    
                        $constanciaEncoded = chunk_split(base64_encode(file_get_contents($pdf)));
    
                        $pdfPos = strlen($constanciaEncoded)/10;
    
                        $pos = ceil($pdfPos);
    
                        $constancia = str_split($constanciaEncoded, $pos);
                    }
    
                foreach ($pagosParaFacturar as $key => $pagoParaFactura) {
                    $incrementa = SolicitudFactura::where('IdTienda', $idTienda)
                            ->max('Id')+1;
    
                    $idSolicitudFactura = $incrementa . $ticket->IdEncabezado;
    
                    if(!empty($pdf)){
                        ConstanciaSituacionFiscal::insert([
                            'IdSolicitudFactura' => $idSolicitudFactura,
                            'NomConstancia' => $nomArchivo
                        ]);
            
                        for ($i=0; $i < count($constancia) ; $i++) { 
                            $campoConstancia = 'Constancia' . ($i + 1);
                            ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
                                                    ->update([
                                                        $campoConstancia => $constancia[$i]
                                                    ]);
                        }
                    }
    
                    SolicitudFactura::insert([
                        'IdSolicitudFactura' => $idSolicitudFactura,
                        'FechaSolicitud' => date('d-m-Y H:i:s'),
                        'IdEncabezado' =>$ticket->IdEncabezado,
                        'IdTienda' => $idTienda,
                        'IdTipoPago' => $tiposPagoFactura[$key],
                        'IdClienteCloud' => empty($editarInfo) && empty($pdf) ? $cliente->IdClienteCloud : null,
                        'TipoPersona' =>$tipoPersona,
                        'RFC' => strtoupper($request->rfcCliente),
                        'NomCliente' => $cliente->NomCliente,
                        'Calle' => strtoupper($request->calle),
                        'NumExt' => strtoupper($request->numExt),
                        'NumInt' => strtoupper($request->numInt),
                        'Colonia' => strtoupper($request->colonia),
                        'Ciudad' => strtoupper($request->ciudad),
                        'Municipio' => strtoupper($request->municipio),
                        'Estado' => strtoupper($request->estado),
                        'Pais' => 'MEXICO',
                        'CodigoPostal' => $request->codigoPostal,
                        'Email' => strtolower($request->email),
                        'Telefono' => $request->telefono,
                        'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                        'IdUsuarioCliente' => null,
                        'Bill_To' => empty($editarInfo) && empty($pdf) ? $cliente->Bill_To : null,
                        'UsoCFDI' => strtoupper($request->cfdi)
                    ]);
    
                    foreach ($pagoParaFactura->CortePago as $key => $pagoIdCorte) {
                        CorteTienda::where('IdCortesTienda', $pagoIdCorte->IdCortesTienda)
                                ->update([
                                    'IdSolicitudFactura' => $idSolicitudFactura
                                ]);
                    }
                }
    
                DatEncabezado::where('IdEncabezado', $ticket->IdEncabezado)
                            ->update([
                                'SolicitudFE' => 0
                            ]);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }
    
            DB::commit();
            return redirect('SolicitudFactura')->with('msjAdd', 'Se Realizó la Solicitud de Factura del Ticket: '.$numTicket);
        }
    }

    public function GuardarSolicitudFacturaClienteNuevo(Request $request){
        //return $request->all();
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        
        $numTicket = $request->numTicket;

        $ticket = DatEncabezado::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', date('Y-m-d'))
                ->where('IdTicket', $numTicket)
                ->first();

        $idEncabezado = $ticket->IdEncabezado;

        $tiposPagoFactura = $request->chkTipoPagoTicket;

        $auxTiposPago = CorteTienda::where('IdEncabezado', $ticket->IdEncabezado)
                        ->select('IdTipoPago')
                        ->distinct('IdTipoPago')
                        ->get();

        //SOLICITUDES CON UN SOLO METODO DE PAGO EN CLIENTE NUEVO
        if($auxTiposPago->count() == 1){
            try {
                DB::beginTransaction();
    
                $incrementa = SolicitudFactura::where('IdTienda', $idTienda)
                        ->max('Id')+1;

                $idSolicitudFactura = $incrementa . $ticket->IdEncabezado;

                $pdf = $request->file('cSituacionFiscal');

                if(!empty($pdf)){
                    $nomArchivo = $pdf->getClientOriginalName();

                    $constanciaEncoded = chunk_split(base64_encode(file_get_contents($pdf)));

                    $pdfPos = strlen($constanciaEncoded)/10;

                    $pos = ceil($pdfPos);

                    $constancia = str_split($constanciaEncoded, $pos);

                    //return $constancia;

                    ConstanciaSituacionFiscal::insert([
                        'IdSolicitudFactura' => $idSolicitudFactura,
                        'NomConstancia' => $nomArchivo
                    ]);

                    for ($i=0; $i < count($constancia) ; $i++) { 
                        $campoConstancia = 'Constancia' . ($i + 1);
                        ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
                                            ->update([
                                                $campoConstancia => $constancia[$i]
                                            ]);
                    }
                }

                foreach ($auxTiposPago as $key => $auxTipoPago) {
                    $tipoPago = $auxTipoPago->IdTipoPago;
                }

                SolicitudFactura::insert([
                    'IdSolicitudFactura' => $idSolicitudFactura,
                    'FechaSolicitud' => date('d-m-Y H:i:s'),
                    'IdEncabezado' => $idEncabezado,
                    'IdTienda' => $idTienda,
                    'IdTipoPago' => $tipoPago,
                    'IdClienteCloud' => null,
                    'TipoPersona' => $request->tipoPersona,
                    'RFC' => strtoupper($request->rfcCliente),
                    'NomCliente' => strtoupper($request->nomCliente),
                    'Calle' => strtoupper($request->calle),
                    'NumExt' => strtoupper($request->numExt),
                    'NumInt' => strtoupper($request->numInt),
                    'Colonia' => strtoupper($request->colonia),
                    'Ciudad' => strtoupper($request->ciudad),
                    'Municipio' => strtoupper($request->municipio),
                    'Estado' => strtoupper($request->estado),
                    'Pais' => 'MEXICO',
                    'CodigoPostal' => $request->codigoPostal,
                    'Email' => strtolower($request->correo),
                    'Telefono' => $request->telefono,
                    'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                    'IdUsuarioCliente' => null,
                    'Bill_To' => null,
                    'UsoCFDI' => strtoupper($request->cfdi)
                ]);

                $pagosFactura = CorteTienda::where('IdEncabezado', $ticket->IdEncabezado)
                                ->where('IdTienda', $idTienda)
                                ->get();

                foreach ($pagosFactura as $j => $pagoFactura) {
                    CorteTienda::where('IdCortesTienda', $pagoFactura->IdCortesTienda)
                            ->update([
                                'IdSolicitudFactura' => $idSolicitudFactura
                            ]);
                }

                DatEncabezado::where('IdEncabezado', $idEncabezado)
                                ->update([
                                    'SolicitudFE' => 0
                                ]);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }
    
            DB::commit();
            return redirect('SolicitudFactura')->with('msjAdd', 'Se Realizó la Solicitud de Factura del Ticket: '.$numTicket);
        }
        //SOLICITUDES CON MAS DE 1 METODO DE PAGO EN CLIENTE NUEVO
        else{   
            if(empty($tiposPagoFactura)){
                return back()->with('msjdelete', 'Debe Seleccionar Un Metodo de Pago a Facturar!');
            }

            try {
                DB::beginTransaction();
    
                $pagosParaFacturar = TipoPago::with(['CortePago' => function($query) use ($idEncabezado){
                    $query->where('DatCortesTienda.IdEncabezado', $idEncabezado);
                }])
                    ->whereIn('IdTipoPago', $tiposPagoFactura)
                    ->get();
    
                $pdf = $request->file('cSituacionFiscal'); 
                if(!empty($pdf)){
                    $nomArchivo = $pdf->getClientOriginalName();
    
                    $constanciaEncoded = chunk_split(base64_encode(file_get_contents($pdf)));
    
                    $pdfPos = strlen($constanciaEncoded)/10;
    
                    $pos = ceil($pdfPos);
    
                    $constancia = str_split($constanciaEncoded, $pos);
                }
    
                foreach ($pagosParaFacturar as $key => $pagoParaFactura) {
                    $incrementa = SolicitudFactura::where('IdTienda', $idTienda)
                            ->max('Id')+1;
    
                    $idSolicitudFactura = $incrementa . $ticket->IdEncabezado;
    
                    if(!empty($pdf)){
                        ConstanciaSituacionFiscal::insert([
                            'IdSolicitudFactura' => $idSolicitudFactura,
                            'NomConstancia' => $nomArchivo
                        ]);
            
                        for ($i=0; $i < count($constancia) ; $i++) { 
                            $campoConstancia = 'Constancia' . ($i + 1);
                            ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
                                                    ->update([
                                                        $campoConstancia => $constancia[$i]
                                                    ]);
                        }
                    }
    
                    SolicitudFactura::insert([
                        'IdSolicitudFactura' => $idSolicitudFactura,
                        'FechaSolicitud' => date('d-m-Y H:i:s'),
                        'IdEncabezado' => $idEncabezado,
                        'IdTienda' => $idTienda,
                        'IdTipoPago' => $tiposPagoFactura[$key],
                        'IdClienteCloud' => null,
                        'TipoPersona' => $request->tipoPersona,
                        'RFC' => strtoupper($request->rfcCliente),
                        'NomCliente' => strtoupper($request->nomCliente),
                        'Calle' => strtoupper($request->calle),
                        'NumExt' => strtoupper($request->numExt),
                        'NumInt' => strtoupper($request->numInt),
                        'Colonia' => strtoupper($request->colonia),
                        'Ciudad' => strtoupper($request->ciudad),
                        'Municipio' => strtoupper($request->municipio),
                        'Estado' => strtoupper($request->estado),
                        'Pais' => 'MEXICO',
                        'CodigoPostal' => $request->codigoPostal,
                        'Email' => strtolower($request->correo),
                        'Telefono' => $request->telefono,
                        'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                        'IdUsuarioCliente' => null,
                        'Bill_To' => null,
                        'UsoCFDI' => strtoupper($request->cfdi)
                    ]);
    
                    foreach ($pagoParaFactura->CortePago as $key => $pagoIdCorte) {
                        CorteTienda::where('IdCortesTienda', $pagoIdCorte->IdCortesTienda)
                                ->update([
                                    'IdSolicitudFactura' => $idSolicitudFactura
                                ]);
                    }
                }
    
                DatEncabezado::where('IdEncabezado', $ticket->IdEncabezado)
                            ->update([
                                'SolicitudFE' => 0
                            ]);
    
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }
    
            DB::commit();
            return redirect('SolicitudFactura')->with('msjAdd', 'Se Realizó la Solicitud de Factura del Ticket: '.$numTicket);
        }
    }

    public function SubirConstanciaSolicitud(Request $request, $idSolicitudFactura){
        $pdf = $request->file('cSituacionFiscal');

        $nomArchivo = $pdf->getClientOriginalName();

        $constanciaEncoded = chunk_split(base64_encode(file_get_contents($pdf)));

        $pdfPos = strlen($constanciaEncoded)/10;

        $pos = ceil($pdfPos);

        $constancia = str_split($constanciaEncoded, $pos);

        //return $constancia;

        try {
            DB::beginTransaction();

            ConstanciaSituacionFiscal::insert([
                'IdSolicitudFactura' => $idSolicitudFactura,
                'NomConstancia' => $nomArchivo
            ]);
    
            for ($i=0; $i < count($constancia) ; $i++) { 
                $campoConstancia = 'Constancia' . ($i + 1);
                ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
                                        ->update([
                                            $campoConstancia => $constancia[$i]
                                        ]);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back();
    }
}