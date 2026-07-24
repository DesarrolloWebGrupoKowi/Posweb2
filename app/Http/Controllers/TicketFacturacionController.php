<?php

namespace App\Http\Controllers;

use App\Models\DatTicketHabilitadoFacturacion;
use App\Models\DatEncabezado;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TicketFacturacionController extends Controller
{
    protected Collection $tiendas;
    protected array $tiendasIds;

    public function __construct(protected TiendaService $tiendaService)
    {
        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // return $this->tiendasIds;
        $idEncabezado = $request->idEncabezado;
        $ticket = null;
        if ($idEncabezado) {
            $ticket = DatEncabezado::with(['Tienda', 'Detalle' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->select('DatDetalle.*', 'CatArticulos.CodArticulo', 'CatArticulos.NomArticulo');
            }])
                ->where('IdEncabezado', $idEncabezado)
                ->first();
        }

        // Historial de tickets habilitados
        $ticketsHabilitados = DatTicketHabilitadoFacturacion::with(['encabezado.Tienda', 'usuario' => function ($query) {
            $query->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatUsuarios.NumNomina')
                ->select('CatUsuarios.*', 'CatEmpleados.Nombre', 'CatEmpleados.Apellidos');
        }])
            ->orderBy('Fecha', 'DESC')
            ->whereIn('IdTienda', $this->tiendasIds)
            ->paginate(10);

        return view('TicketFacturacion.Index', compact('idEncabezado', 'ticket', 'ticketsHabilitados'));
    }

    public function habilitar(Request $request)
    {
        $request->validate([
            'idEncabezado' => 'required|exists:DatEncabezado,IdEncabezado',
            'observaciones' => 'nullable|string|max:250'
        ]);

        $idEncabezado = $request->idEncabezado;

        $ticketHabilitado = DatTicketHabilitadoFacturacion::where('IdEncabezado', $idEncabezado)
            ->first();

        $hoy = Carbon::now()->format('Y-m-d');

        if ($ticketHabilitado) {
            if ($ticketHabilitado->Activo == 1 && Carbon::parse($ticketHabilitado->Fecha)->format('Y-m-d') == $hoy) {
                return redirect()->back()->with('error', 'El ticket ya está habilitado para facturación el día de hoy.');
            }
            DatTicketHabilitadoFacturacion::where('IdEncabezado', $idEncabezado)
                ->update([
                    'Activo' => 1,
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'Fecha' => date('d-m-Y H:i:s'),
                    'Observaciones' => $request->observaciones
                ]);

            return redirect()->back()->with('success', 'Ticket rehabilitado para facturación exitosamente.');
        }

        $encabezado = DatEncabezado::select('IdTienda')
            ->where('IdEncabezado', $idEncabezado)
            ->firstOrFail();

        DatTicketHabilitadoFacturacion::create([
            'IdEncabezado' => $idEncabezado,
            'IdTienda' => $encabezado->IdTienda,
            'IdUsuario' => Auth::user()->IdUsuario,
            'Activo' => 1,
            'Observaciones' => $request->observaciones
        ]);

        return redirect()->back()->with('success', 'Ticket habilitado para facturación exitosamente.');
    }

    public function desactivar(int $id)
    {
        $ticketHabilitado = DatTicketHabilitadoFacturacion::findOrFail($id);
        $ticketHabilitado->Activo = 0;
        $ticketHabilitado->save();

        return redirect()->back()->with('success', 'Ticket desactivado exitosamente.');
    }
}
