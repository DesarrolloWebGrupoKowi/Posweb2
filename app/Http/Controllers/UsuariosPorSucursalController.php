<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuariosPorSucursalController extends Controller
{
    public function index(Request $request)
    {
        $txtFiltro = trim($request->get('txtFiltro', ''));
        $idTipoUsuario = $request->get('IdTipoUsuario', '');
        $estatus = $request->get('estatus', '');

        // Primero obtener los usuarios con sus datos básicos
        $usuariosQuery = DB::table('CatUsuarios')
            ->leftJoin('CatTipoUsuarios', 'CatUsuarios.IdTipoUsuario', '=', 'CatTipoUsuarios.IdTipoUsuario')
            ->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', '=', 'CatUsuarios.NumNomina')
            ->select(
                'CatUsuarios.IdUsuario',
                'CatUsuarios.NomUsuario',
                'CatUsuarios.NumNomina',
                'CatUsuarios.Correo',
                'CatUsuarios.Status',
                'CatTipoUsuarios.NomTipoUsuario',
                'CatEmpleados.Nombre',
                'CatEmpleados.Apellidos'
            )
            ->where(function ($query) use ($txtFiltro) {
                $query->where('CatUsuarios.NomUsuario', 'like', '%' . $txtFiltro . '%')
                    ->orWhere('CatUsuarios.NumNomina', 'like', '%' . $txtFiltro . '%')
                    ->orWhere('CatUsuarios.Correo', 'like', '%' . $txtFiltro . '%')
                    ->orWhere('CatEmpleados.Nombre', 'like', '%' . $txtFiltro . '%')
                    ->orWhere('CatEmpleados.Apellidos', 'like', '%' . $txtFiltro . '%')
                    ->orWhere(DB::raw("CONCAT(CatEmpleados.Nombre, ' ', CatEmpleados.Apellidos)"), 'like', '%' . $txtFiltro . '%');
            })
            ->when(!empty($idTipoUsuario), function ($query) use ($idTipoUsuario) {
                return $query->where('CatUsuarios.IdTipoUsuario', $idTipoUsuario);
            })
            ->where('CatUsuarios.IdTipoUsuario', '<>', 2)
            ->orderBy('CatTipoUsuarios.NomTipoUsuario')
            ->orderBy('CatUsuarios.NomUsuario');

        // Paginar
        $usuarios = $usuariosQuery->paginate(10)->appends(request()->query());

        // Obtener sucursales por usuario usando SQL puro (con comillas simples)
        $sucursalesPorUsuario = DB::select("
            SELECT
                IdUsuario,
                COUNT(IdSucursal) as TotalSucursales,
                STRING_AGG(CAST(IdSucursal AS VARCHAR), ', ') as SucursalesIds
            FROM CatUsuariosSucursales
            GROUP BY IdUsuario
        ");

        // Convertir a array keyed por IdUsuario
        $sucursalesMap = [];
        foreach ($sucursalesPorUsuario as $item) {
            $sucursalesMap[$item->IdUsuario] = $item;
        }

        foreach ($usuarios as $usuario) {
            $usuario->TotalSucursales = $sucursalesMap[$usuario->IdUsuario]->TotalSucursales ?? 0;
            $usuario->SucursalesAsignadas = $sucursalesMap[$usuario->IdUsuario]->SucursalesIds ?? '';
        }

        $tipoUsuarios = DB::table('CatTipoUsuarios')
            ->where('Status', '=', '0')
            ->get();

        return view('UsuariosPorSucursal.index', compact('usuarios', 'tipoUsuarios', 'idTipoUsuario', 'estatus', 'txtFiltro'));
    }

    public function getSucursalesUsuario(int $idUsuario)
    {
        try {
            // Sucursales asignadas al usuario
            $sucursalesAsignadas = DB::table('CatUsuariosSucursales')
                ->where('IdUsuario', $idUsuario)
                ->pluck('IdSucursal')
                ->toArray();

            // Todas las sucursales
            $todasLasSucursales = DB::connection('CORTE')->select("EXEC SP_Sucursales");

            return response()->json([
                'asignadas' => $sucursalesAsignadas,
                'todas' => $todasLasSucursales
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'asignadas' => [],
                'todas' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Guardar asignación de sucursales
    public function asignarSucursales(Request $request)
    {
        try {
            $request->validate([
                'IdUsuario' => 'required|exists:CatUsuarios,IdUsuario',
                'sucursales' => 'array',
                'sucursales.*' => 'integer'
            ]);

            $idUsuario = $request->IdUsuario;
            $sucursales = $request->sucursales ?? [];

            // Iniciar transacción
            DB::beginTransaction();

            // Eliminar asignaciones existentes
            DB::table('CatUsuariosSucursales')
                ->where('IdUsuario', $idUsuario)
                ->delete();

            // Insertar nuevas asignaciones
            foreach ($sucursales as $idSucursal) {
                DB::table('CatUsuariosSucursales')->insert([
                    'IdUsuario' => $idUsuario,
                    'IdSucursal' => $idSucursal,
                    'FechaAsignacion' => now(),
                    'AsignadoPor' => Auth::user()->IdUsuario ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sucursales asignadas correctamente',
                'total' => count($sucursales)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar sucursales: ' . $e->getMessage()
            ], 500);
        }
    }

    // Obtener todas las sucursales (para el modal)
    public function getSucursales()
    {
        $sucursales = DB::select("EXEC SP_Sucursales");
        return response()->json($sucursales);
    }
}
