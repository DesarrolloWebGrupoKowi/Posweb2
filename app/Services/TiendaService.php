<?php

namespace App\Services;

use App\Models\Tienda;
use Illuminate\Support\Facades\Auth;

class TiendaService
{
    public function obtenerTiendasUsuario()
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
            throw new \Exception('El usuario no tiene tiendas asignadas');
        }

        $query = Tienda::select('IdTienda', 'NomTienda', 'NombreCorto');

        if (!empty($usuarioTienda->IdTienda)) {
            $query->where('IdTienda', $usuarioTienda->IdTienda);
        }

        if (!empty($usuarioTienda->IdPlaza)) {
            $query->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        return $query->orderBy('IdTienda')->get();
    }

    public function validarAccesoTienda($idTienda)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->IdTienda && $usuarioTienda->IdTienda != $idTienda) {
            throw new \Exception('No tiene acceso a esta tienda');
        }
    }

    /**
     * Obtener tiendas disponibles para el usuario autenticado
     * Compatible con los métodos obtenerTiendas() de los controladores de dashboard
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception Si el usuario no tiene tiendas asignadas
     */
    public function obtenerTiendas()
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
            throw new \Exception('El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        $tiendas = Tienda::select('IdTienda', 'NomTienda');
        // ->where('Status', 0);

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas->where('IdTienda', $usuarioTienda->IdTienda);
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        return $tiendas
            ->orderBy('IdTienda')
            ->get();
    }

    /**
     * Obtener IDs de tiendas disponibles para el usuario autenticado
     * Retorna un array vacío si el usuario no tiene tiendas (útil para filtros SQL)
     *
     * @return array Array de IDs de tiendas
     */
    public function obtenerTiendasIds()
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
            return [];
        }

        $tiendas = Tienda::select('IdTienda');

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas->where('IdTienda', $usuarioTienda->IdTienda);
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        return $tiendas
            ->orderBy('IdTienda')
            ->pluck('IdTienda')
            ->toArray();
    }

    /**
     * Obtener tiendas disponibles para el usuario autenticado
     * Retorna una colección vacía si el usuario no tiene tiendas (sin lanzar excepción)
     * Útil cuando se necesita verificar manualmente si hay tiendas
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerTiendasOpcional()
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
            return collect();
        }

        $tiendas = Tienda::select('IdTienda', 'NomTienda');
        // ->where('Status', 0);

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas->where('IdTienda', $usuarioTienda->IdTienda);
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        return $tiendas
            ->orderBy('IdTienda')
            ->get();
    }
}
