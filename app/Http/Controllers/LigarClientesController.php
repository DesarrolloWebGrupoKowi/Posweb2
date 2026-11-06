<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudFactura;
use App\Models\CustomerCloud;
use App\Models\MovimientoClienteCloud;
use App\Models\NotificacionClienteCloud;
use App\Models\CorteTienda;
use App\Models\ConstanciaSituacionFiscal;
use App\Models\Tienda;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LigarClientesController extends Controller
{
    // Actualización de clientes desde Cloud (job XML_SQL_CUSTOMERS_IMP + Sp_Descarga_CloudTable_Customer)
    private const JOB_CLIENTES_CLOUD = 'XML_SQL_CUSTOMERS_IMP';
    private const SP_CLIENTES_CLOUD = 'Sp_Descarga_CloudTable_Customer';
    private const ESPERA_CLIENTES_CLOUD = 600; // segundos mínimos entre una ejecución y otra (10 min)
    private const TIMEOUT_JOB_CLIENTES_CLOUD = 600; // si el job tarda más de esto se marca como error
    private const CACHE_ULTIMA_EJECUCION = 'clientesCloud.ultimaEjecucion';
    private const CACHE_PROCESO = 'clientesCloud.proceso';
    private const CACHE_LOCK = 'clientesCloud.lock';

    public function ClientesNuevos(Request $request)
    {
        // $sqlSelect =" select a.*, f.IdTicket, c.NomTipoPago, e.NomBanco, d.NumTarjeta, d.Pago, h.NomCFDI, g.NomTienda, j.NomConstancia".
        //             " from SolicitudFactura as a".
        //             " left join DatCortesTienda as b on b.IdSolicitudFactura=a.IdSolicitudFactura".
        //             " left join CatTipoPago as c on c.IdTipoPago=b.IdTipoPago".
        //             " left join DatTipoPago as d on d.IdTipoPago=b.IdTipoPago".
        //             " left join CatBancos as e on e.IdBanco=d.IdBanco".
        //             " left join DatEncabezado as f on f.IdEncabezado=d.IdEncabezado".
        //             " left join CatTiendas as g on g.IdTienda=a.IdTienda".
        //             " left join CatUsoCFDI as h on h.UsoCFDI=a.UsoCFDI".
        //             " left join ConstanciaSituacionFiscal as j on j.IdSolicitudFactura=a.IdSolicitudFactura".
        //             " where a.Bill_To is null".
        //             " and a.IdClienteCloud is null".
        //             " and d.IdEncabezado=a.IdEncabezado".
        //             // " and a.Status=0".
        //             " group by a.IdSolicitudFactura, a.Id, a.RFC, a.FechaSolicitud, a.IdEncabezado,".
        //             " a.IdTienda, a.IdClienteCloud, a.TipoPersona, a.NomCliente, a.Calle, ".
        //             " a.NumExt, a.NumInt, a.Colonia, a.Ciudad, a.Municipio, a.Estado,".
        //             " a.Pais, a.CodigoPostal, a.Email, a.Telefono, a.IdUsuarioSolicitud, a.IdUsuarioCliente, a.Fecha_Cliente,".
        //             " a.Bill_To, a.UsoCFDI, a.IdCaja, a.Editar, a.Bajar, a.Status, a.MetodoPago, c.NomTipoPago, d.NumTarjeta, e.NomBanco, d.NumTarjeta, f.IdTicket, d.Pago, h.NomCFDI, g.NomTienda, j.NomConstancia, a.Subir,a.IdTipoPago";

        // $clientesPorLigar = DB::select($sqlSelect);

        // //return $clientesPorLigar;

        // $movimientos = MovimientoClienteCloud::with(['Notificaciones' => function ($notificacion) {
        //     $notificacion->where('DatNotificacionesClienteCloud.Status', 0)
        //         ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatNotificacionesClienteCloud.IdTienda');
        // }])
        //         ->where('Status', 0)
        //         ->get();

        // $notificaciones = NotificacionClienteCloud::where('Status', 0)
        //                 ->count();

        //return $movimientos;

        $usuarioTienda = Auth::user()->usuarioTienda;

        if (!$usuarioTienda) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }

        $idTienda = $request->idTienda;
        $searchQuery = $request->query('search') ? $request->query('search') : '';
        $searchQuery = $request->search ? $request->search : '';

        $ids = [];
        foreach ($tiendas as $tienda) {
            array_push($ids, $tienda->IdTienda);
        }

        $solicitudes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->where('NomCliente', 'LIKE', '%' . $searchQuery . '%')
            ->where('SolicitudFactura.Status', '0')
            ->whereNotNull('SolicitudFactura.Editar')
            ->whereIn('SolicitudFactura.IdTienda', $ids)
            ->where('SolicitudFactura.IdTienda', 'LIKE', $idTienda)
            ->get();

        $segundosEsperaCloud = $this->segundosRestantesClientesCloud();
        $procesoCloud = Cache::get(self::CACHE_PROCESO);
        $procesoCloudEnCurso = $this->procesoClientesCloudEnCurso($procesoCloud);
        $ultimoEstadoCloud = $procesoCloud['estado'] ?? null;

        return view('LigarClientes.ClientesNuevos', compact('solicitudes', 'tiendas', 'idTienda', 'segundosEsperaCloud', 'procesoCloudEnCurso', 'ultimoEstadoCloud'));
    }

    /**
     * Arranca el job de SQL Agent que descarga los clientes de Cloud.
     * El SP de descarga a CatClientes se ejecuta en EstadoClientesCloud cuando el job termina bien.
     */
    public function ActualizarClientesCloud(Request $request)
    {
        $lock = Cache::lock(self::CACHE_LOCK, 60);

        if (!$lock->get()) {
            return response()->json(['ok' => false, 'mensaje' => 'Ya se está iniciando una actualización, intente en unos segundos.'], 409);
        }

        try {
            $segundosRestantes = $this->segundosRestantesClientesCloud();
            if ($segundosRestantes > 0) {
                return response()->json([
                    'ok' => false,
                    'mensaje' => 'Debe esperar ' . ceil($segundosRestantes / 60) . ' minuto(s) para volver a actualizar.',
                    'segundosRestantes' => $segundosRestantes,
                ], 429);
            }

            $proceso = Cache::get(self::CACHE_PROCESO);
            if ($this->procesoClientesCloudEnCurso($proceso)) {
                return response()->json(['ok' => false, 'mensaje' => 'Ya hay una actualización en proceso.', 'enProceso' => true], 409);
            }

            $cloud = DB::connection('Cloud_Tables');
            $instanciaPrevia = $this->ultimaInstanciaJobClientesCloud();

            // Si el job ya está corriendo (por su horario o manual) no se vuelve a arrancar, solo se espera a que termine
            if (!$this->jobClientesCloudCorriendo()) {
                $cloud->statement('EXEC msdb.dbo.sp_start_job @job_name = ?', [self::JOB_CLIENTES_CLOUD]);
            }

            Cache::forever(self::CACHE_ULTIMA_EJECUCION, now()->timestamp);
            Cache::put(self::CACHE_PROCESO, [
                'estado' => 'job',
                'instanciaPrevia' => $instanciaPrevia,
                'inicio' => now()->timestamp,
                'usuario' => Auth::user()->name ?? Auth::id(),
                'mensaje' => 'Descargando clientes de Cloud...',
            ], now()->addDay());

            return response()->json([
                'ok' => true,
                'mensaje' => 'Descargando clientes de Cloud...',
                'segundosRestantes' => self::ESPERA_CLIENTES_CLOUD,
            ]);
        } catch (\Throwable $e) {
            Log::error('ActualizarClientesCloud: ' . $e->getMessage());
            return response()->json(['ok' => false, 'mensaje' => 'No se pudo iniciar la descarga de clientes: ' . $e->getMessage()], 500);
        } finally {
            $lock->release();
        }
    }

    /**
     * Consulta el avance. Cuando el job termina con éxito ejecuta el SP que pasa los clientes a CatClientes.
     */
    public function EstadoClientesCloud(Request $request)
    {
        $proceso = Cache::get(self::CACHE_PROCESO);

        if (!$proceso || $proceso['estado'] !== 'job') {
            return $this->respuestaEstadoClientesCloud($proceso);
        }

        try {
            if ($this->jobClientesCloudCorriendo()) {
                if (now()->timestamp - $proceso['inicio'] > self::TIMEOUT_JOB_CLIENTES_CLOUD) {
                    return $this->finalizarProcesoClientesCloud($proceso, 'error', 'El job ' . self::JOB_CLIENTES_CLOUD . ' está tardando demasiado, revíselo en SQL Server Agent.');
                }
                return $this->respuestaEstadoClientesCloud($proceso);
            }

            $resultado = DB::connection('Cloud_Tables')->selectOne(
                "SELECT TOP 1 h.run_status, h.message
                   FROM msdb.dbo.sysjobhistory h
                   JOIN msdb.dbo.sysjobs j ON j.job_id = h.job_id
                  WHERE j.name = ? AND h.step_id = 0 AND h.instance_id > ?
                  ORDER BY h.instance_id DESC",
                [self::JOB_CLIENTES_CLOUD, $proceso['instanciaPrevia']]
            );

            // El job ya no corre pero todavía no escribe su historial
            if (!$resultado) {
                if (now()->timestamp - $proceso['inicio'] > self::TIMEOUT_JOB_CLIENTES_CLOUD) {
                    return $this->finalizarProcesoClientesCloud($proceso, 'error', 'No se encontró el resultado del job ' . self::JOB_CLIENTES_CLOUD . '.');
                }
                return $this->respuestaEstadoClientesCloud($proceso);
            }

            if ((int) $resultado->run_status !== 1) {
                return $this->finalizarProcesoClientesCloud($proceso, 'error', 'La descarga desde Cloud falló, no se modificaron los clientes. ' . $this->errorPasoJobClientesCloud($proceso['instanciaPrevia']));
            }
        } catch (\Throwable $e) {
            Log::error('EstadoClientesCloud (job): ' . $e->getMessage());
            return $this->respuestaEstadoClientesCloud($proceso);
        }

        // Job terminado con éxito: ejecutar el SP una sola vez
        $lock = Cache::lock(self::CACHE_LOCK, 300);
        if (!$lock->get()) {
            return $this->respuestaEstadoClientesCloud(array_merge($proceso, ['estado' => 'sp', 'mensaje' => 'Pasando clientes al punto de venta...']));
        }

        try {
            $proceso = Cache::get(self::CACHE_PROCESO);
            if (!$proceso || $proceso['estado'] !== 'job') {
                return $this->respuestaEstadoClientesCloud($proceso);
            }

            $proceso['estado'] = 'sp';
            $proceso['mensaje'] = 'Pasando clientes al punto de venta...';
            Cache::put(self::CACHE_PROCESO, $proceso, now()->addDay());

            set_time_limit(300);

            // El SP hace DELETE de los clientes que no estén en el origen; si el origen viene vacío no se ejecuta
            $clientesOrigen = DB::connection('Cloud_Tables')->table('XXKW_CUSTOMERS')
                ->where('LOCATION_STATUS', 'A')
                ->where('CODIGO_ENVIO', 'BILL_TO')
                ->count();

            if ($clientesOrigen == 0) {
                return $this->finalizarProcesoClientesCloud($proceso, 'error', 'La tabla de clientes de Cloud está vacía, no se ejecutó la descarga para no borrar clientes.');
            }

            $erroresAntes = DB::table('CatErrorProcedimientos')->where('Catalogo', self::SP_CLIENTES_CLOUD)->count();

            DB::statement('EXEC ' . self::SP_CLIENTES_CLOUD);

            $erroresDespues = DB::table('CatErrorProcedimientos')->where('Catalogo', self::SP_CLIENTES_CLOUD)->count();

            if ($erroresDespues > $erroresAntes) {
                $error = DB::table('CatErrorProcedimientos')
                    ->where('Catalogo', self::SP_CLIENTES_CLOUD)
                    ->orderByDesc('id')
                    ->value('Description_Error');

                return $this->finalizarProcesoClientesCloud($proceso, 'error', 'Error al pasar los clientes al punto de venta: ' . $error);
            }

            return $this->finalizarProcesoClientesCloud($proceso, 'ok', 'Clientes actualizados correctamente.');
        } catch (\Throwable $e) {
            Log::error('EstadoClientesCloud (sp): ' . $e->getMessage());
            return $this->finalizarProcesoClientesCloud($proceso, 'error', 'Error al pasar los clientes al punto de venta: ' . $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    private function segundosRestantesClientesCloud()
    {
        $ultimaEjecucion = Cache::get(self::CACHE_ULTIMA_EJECUCION);

        if (!$ultimaEjecucion) {
            return 0;
        }

        return max(0, self::ESPERA_CLIENTES_CLOUD - (now()->timestamp - $ultimaEjecucion));
    }

    private function jobClientesCloudCorriendo()
    {
        $corriendo = DB::connection('Cloud_Tables')->selectOne(
            "SELECT COUNT(*) AS total
               FROM msdb.dbo.sysjobactivity a
               JOIN msdb.dbo.sysjobs j ON j.job_id = a.job_id
              WHERE j.name = ?
                AND a.session_id = (SELECT MAX(session_id) FROM msdb.dbo.syssessions)
                AND a.start_execution_date IS NOT NULL
                AND a.stop_execution_date IS NULL",
            [self::JOB_CLIENTES_CLOUD]
        );

        return $corriendo->total > 0;
    }

    private function ultimaInstanciaJobClientesCloud()
    {
        $instancia = DB::connection('Cloud_Tables')->selectOne(
            "SELECT ISNULL(MAX(h.instance_id), 0) AS instancia
               FROM msdb.dbo.sysjobhistory h
               JOIN msdb.dbo.sysjobs j ON j.job_id = h.job_id
              WHERE j.name = ?",
            [self::JOB_CLIENTES_CLOUD]
        );

        return (int) $instancia->instancia;
    }

    // Toma el detalle del error del paso que falló (el resultado general del job solo dice "The job failed")
    private function errorPasoJobClientesCloud($instanciaPrevia)
    {
        try {
            $paso = DB::connection('Cloud_Tables')->selectOne(
                "SELECT TOP 1 h.message
                   FROM msdb.dbo.sysjobhistory h
                   JOIN msdb.dbo.sysjobs j ON j.job_id = h.job_id
                  WHERE j.name = ? AND h.step_id > 0 AND h.run_status <> 1 AND h.instance_id > ?
                  ORDER BY h.instance_id DESC",
                [self::JOB_CLIENTES_CLOUD, $instanciaPrevia]
            );
        } catch (\Throwable $e) {
            return '';
        }

        if (!$paso) {
            return '';
        }

        Log::error('Job ' . self::JOB_CLIENTES_CLOUD . ': ' . $paso->message);

        // Mensajes de SSIS: "Description: <detalle>  End Error"
        if (preg_match('/Description:\s*(.+?)\s+End Error/s', $paso->message, $coincidencia)) {
            return 'Detalle: ' . trim($coincidencia[1]);
        }

        return 'Detalle: ' . mb_substr($paso->message, 0, 300);
    }

    // Un proceso "en curso" con más de 30 min se considera atorado y deja de bloquear el botón
    private function procesoClientesCloudEnCurso($proceso)
    {
        return $proceso
            && in_array($proceso['estado'], ['job', 'sp'])
            && now()->timestamp - $proceso['inicio'] < 1800;
    }

    private function finalizarProcesoClientesCloud(array $proceso, $estado, $mensaje)
    {
        $proceso['estado'] = $estado;
        $proceso['mensaje'] = $mensaje;
        $proceso['fin'] = now()->timestamp;
        Cache::put(self::CACHE_PROCESO, $proceso, now()->addDay());

        if ($estado === 'error') {
            Log::error('Actualización clientes Cloud: ' . $mensaje);
        }

        return $this->respuestaEstadoClientesCloud($proceso);
    }

    private function respuestaEstadoClientesCloud($proceso)
    {
        if ($proceso && in_array($proceso['estado'], ['job', 'sp']) && !$this->procesoClientesCloudEnCurso($proceso)) {
            $proceso['estado'] = 'error';
            $proceso['mensaje'] = 'La última actualización no terminó, puede intentar de nuevo.';
        }

        return response()->json([
            'estado' => $proceso['estado'] ?? 'sin_proceso',
            'mensaje' => $proceso['mensaje'] ?? '',
            'segundosRestantes' => $this->segundosRestantesClientesCloud(),
        ]);
    }

    public function VerConstanciaCliente(Request $request, $idSolicitudFactura)
    {
        $constancia = ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
            ->first();

        $base64Pdf = $constancia->Constancia1 .
            $constancia->Constancia2 .
            $constancia->Constancia3 .
            $constancia->Constancia4 .
            $constancia->Constancia5 .
            $constancia->Constancia6 .
            $constancia->Constancia7 .
            $constancia->Constancia8 .
            $constancia->Constancia9 .
            $constancia->Constancia10;

        $pdfDecoded = base64_decode($base64Pdf);

        $pdf2 = fopen('c:\\ConstanciasPosWeb2\\' . $constancia->NomConstancia . '', 'w');

        fwrite($pdf2, $pdfDecoded);

        fclose($pdf2);

        $path = 'c:\\ConstanciasPosWeb2\\' . $constancia->NomConstancia . '';

        return response()->file($path);
    }

    public function LigarCliente(Request $request)
    {
        // $idSolicitudFactura = $request->idSolicitudFactura;
        // $bill_To = $request->bill_To;

        // $tipoPago = CorteTienda::where('IdSolicitudFactura', $idSolicitudFactura)
        //     ->select('IdTipoPago')
        //     ->distinct('IdTipoPago')
        //     ->first();

        // $solicitudFactura = DB::table('SolicitudFactura as a')
        //     ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
        //     ->leftJoin('DatTipoPago as c', 'c.IdEncabezado', 'a.IdEncabezado')
        //     ->leftJoin('CatTipoPago as d', 'd.IdTipoPago', 'c.IdTipoPago')
        //     ->leftJoin('CatBancos as e', 'e.IdBanco', 'c.IdBanco')
        //     ->select('a.*', 'b.NomTienda', 'd.NomTipoPago', 'e.NomBanco', 'c.NumTarjeta')
        //     ->where('a.IdSolicitudFactura', $idSolicitudFactura)
        //     ->where('c.IdTipoPago', $tipoPago->IdTipoPago)
        //     ->first();

        // //return $solicitudFactura;

        // $clientesOracle = CustomerCloud::with('Correo')
        //     ->where('RFC', $solicitudFactura->RFC)
        //     ->where('CODIGO_ENVIO', 'BILL_TO')
        //     ->get();

        // $clienteAlta = $clientesOracle->count() == 0 ? 1 : 0;

        // $cOracle = CustomerCloud::where('BILL_TO', $bill_To)
        //     ->first();

        // $ligarCliente = empty($cOracle) ? 1 : 0;
        try {
            $solicitud = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco', 'DatEncabezado.IdTicket', 'rf.NomRegimenFiscal')
                ->with('ConstanciaSituacionFiscal')
                ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'SolicitudFactura.IdEncabezado')
                ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
                ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
                ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
                ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
                ->leftJoin('CatRegimenFiscal as rf', 'rf.RegimenFiscal', 'SolicitudFactura.RegimenFiscal')
                ->where('IdSolicitudFactura', $request->idSolicitudFactura)
                ->whereNotNull('Editar')
                ->first();

            $clienteSolicitud = Cliente::with([
                'CorreoCliente' => function ($query) {
                    $query->select('IdClienteCloud', 'Email');
                    $query->groupBy('IdClienteCloud', 'Email');
                }
            ])
                ->where('RFC', $solicitud->RFC)
                // ->where('Bill_To', $solicitud->Bill_To)
                ->get();

            $clientes = Cliente::where('RFC', $solicitud->RFC)
                ->get();

            return view('LigarClientes.LigarCliente', compact('solicitud', 'clienteSolicitud', 'clientes'));
        } catch (\Throwable $th) {
            return redirect('ClientesNuevos')->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        // return view('LigarClientes.LigarCliente', compact('solicitudFactura', 'clientesOracle', 'clienteAlta', 'ligarCliente', 'cOracle'));
    }

    public function GuardarLigueCliente($idSolicitudFactura, $bill_To)
    {
        $clienteOracle = CustomerCloud::where('BILL_TO', $bill_To)
            ->first();

        SolicitudFactura::where('IdSolicitudFactura', $idSolicitudFactura)
            ->update([
                'IdClienteCloud' => $clienteOracle->ID_CLIENTE,
                'BILL_TO' => $clienteOracle->BILL_TO,
                'Fecha_Cliente' => date('d-m-Y H:i:s'),
                'IdUsuarioCliente' => Auth::user()->IdUsuario
            ]);

        return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de Factura Ligada con Exito!');
    }

    public function GuardarCheckClienteEditado(Request $request)
    {

        $idsNotificacion = $request->chkIdNotificacion;

        if (empty($idsNotificacion)) {
            return back()->with('msjdelete', 'Debe Seleccionar una Casilla, de un Cliente Previamente Modificado!');
        }

        foreach ($idsNotificacion as $key => $idNotificacion) {
            NotificacionClienteCloud::where('IdDatNotificacionesClienteCloud', $idNotificacion)
                ->update([
                    'Status' => 1
                ]);
        }

        return back()->with('msjAdd', 'Listo, Vaya a Ligar Cliente(s)!');
    }

    public function Cancelar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Status' => 1,
                'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
                'FechaCancelacion' => date('d-m-Y H:i:s')
            ]);
            return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de factura cancelada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function Finalizar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Editar' => null,
            ]);

            return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de factura finalizada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
