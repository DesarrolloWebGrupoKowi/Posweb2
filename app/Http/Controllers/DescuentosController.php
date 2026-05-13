<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CatTipoDescuento;
use App\Models\DatDetDescuentos;
use App\Models\DatEncDescuentos;
use App\Models\ListaPrecio;
use App\Models\Plaza;
use App\Models\Precio;
use App\Models\Tienda;
use App\Services\TiendaService;
use Illuminate\Support\Facades\Auth;

class DescuentosController extends Controller
{
    protected $tiendaService;
    protected $tiendasIds;
    protected $tiendas;
    protected $plazas;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;

        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });

        $this->plazas = DB::table('CatPlazas')
            ->select('IdPlaza', 'NomPlaza')
            ->orderBy('NomPlaza')
            ->get();
    }

    public function VerDescuentos(Request $request)
    {
        // Si viene con detallado, redirigir a la vista detallada
        if ($request->detallado == 'on') {
            return redirect()->route('VerDescuentosDetallado', $request->query());
        }

        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda;
        $idPlaza = $request->idPlaza;
        $nomDescuento = $request->nomDescuento;
        $activos = $request->activos;
        $filtrosAvanzadosActivos = $request->filled('codigo') ||
            $request->filled('detallado');

        $tiendas = $this->tiendas;
        $plazas = $this->plazas;

        $descuentos = DatEncDescuentos::with(['ArticulosDescuento' => function ($articulos) {
            $articulos->select('DatDetDescuentos.*', 'CatArticulos.NomArticulo', 'CatArticulos.CodArticulo', 'CatListasPrecio.NomListaPrecio');
            $articulos->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetDescuentos.IdArticulo');
            $articulos->leftJoin('CatListasPrecio', 'CatListasPrecio.IdListaPrecio', 'DatDetDescuentos.IdListaPrecio');
        }])
            ->select(
                'DatEncDescuentos.*',
                'CatTipoDescuento.NomTipoDescuento',
                'CatTiendas.NomTienda',
                'CatPlazas.NomPlaza'
            )
            ->leftjoin('CatTipoDescuento', 'CatTipoDescuento.IdTipoDescuento', 'DatEncDescuentos.TipoDescuento')
            ->leftjoin('CatPlazas', 'CatPlazas.IdPlaza', 'DatEncDescuentos.IdPlaza')
            ->leftjoin('CatTiendas', 'CatTiendas.IdTienda', 'DatEncDescuentos.IdTienda')
            ->where('NomDescuento', 'like', '%' . $nomDescuento . '%')

            // Filtro por tienda
            ->when($activos, function ($query) {
                $query->where('DatEncDescuentos.Status', 0)
                    ->whereDate('DatEncDescuentos.FechaFin', '>', now()->toDateString());
            })


            // Filtro por tienda
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('DatEncDescuentos.IdTienda', $idTienda);
            })

            // Filtro por plaza
            ->when($idPlaza, function ($query) use ($idPlaza) {
                $query->where('DatEncDescuentos.IdPlaza', $idPlaza);
            })

            // ->where('DatEncDescuentos.Status', 0)
            ->orderBy('DatEncDescuentos.FechaCreacion', 'DESC')
            ->paginate(10)
            ->appends(request()->query());

        // $descuentosActivos = DatEncDescuentos::where('Status', 0)
        //     ->count();

        // return $descuentos;

        return view('Descuentos.VerDescuentos', compact(
            'descuentos',
            'nomDescuento',
            'filtrosAvanzadosActivos',
            'tiendas',
            'plazas'
        ));
    }

    public function VerDescuentosDetallado(Request $request)
    {
        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda;
        $idPlaza = $request->idPlaza;
        $fecha = $request->fecha;
        $activos = $request->activos;
        $codigo = $request->codigo;
        $detallado = $request->detallado;

        if (!$detallado) {
            return redirect()->route('VerDescuentos');
        }

        // Construir la consulta base (basada en el SQL proporcionado)
        $query = DatDetDescuentos::from('DatDetDescuentos as dd')->select(
            'de.NomDescuento',
            'de.IdEncDescuento',
            'de.FechaInicio',
            'de.FechaFin',
            'de.FechaCreacion',
            'de.FechaDesactivar',
            'de.Status as StatusDescuento',
            'td.NomTipoDescuento',
            'ct.NomTienda',
            'cp.NomPlaza',
            'ca.CodArticulo',
            'ca.NomArticulo',
            'dd.PrecioDescuento',
            'dd.CantArticulo',
            'dd.Status as StatusDetalle'
        )
            ->leftJoin('DatEncDescuentos as de', 'de.IdEncDescuento', '=', 'dd.IdEncDescuento')

            ->leftJoin('CatArticulos as ca', 'ca.IdArticulo', '=', 'dd.IdArticulo')
            ->leftJoin('CatTipoDescuento as td', 'td.IdTipoDescuento', '=', 'de.TipoDescuento')
            ->leftJoin('CatTiendas as ct', 'ct.IdTienda', '=', 'de.IdTienda')
            ->leftJoin('CatPlazas as cp', 'cp.IdPlaza', '=', 'de.IdPlaza');

        // Aplicar filtros de encabezado
        if ($idTienda) {
            $query->where('de.IdTienda', $idTienda);
        }

        if ($idPlaza) {
            $query->where('de.IdPlaza', $idPlaza);
        }

        if ($fecha) {
            $query->whereDate('de.FechaInicio', '<=', $fecha)
                ->whereDate('de.FechaFin', '>=', $fecha);
        }

        if ($activos == 'on') {
            $query->where('de.Status', 0);
            $query->where('dd.Status', 0);
            // Solo incluir descuentos cuya FechaFin es hoy o en el futuro
            $query->whereDate('de.FechaFin', '>=', \Carbon\Carbon::now()->toDateString());
        }

        // Filtro por código de artículo
        if ($codigo) {
            $query->where('ca.CodArticulo', 'LIKE', '%' . $codigo . '%');
        }

        // Ordenar
        $descuentosDetallados = $query->orderBy('de.IdEncDescuento', 'DESC')
            ->orderBy('ca.CodArticulo', 'ASC')
            ->take(100)
            ->get();

        // Datos para los selects
        $tiendas = Tienda::orderBy('NomTienda')->get();
        $plazas = Plaza::orderBy('NomPlaza')->get();

        // Determinar si hay filtros avanzados activos
        $filtrosAvanzadosActivos = $request->filled('codigo') || $request->filled('detallado');

        // return $descuentosDetallados;
        // Retornar la vista con los datos
        return view('Descuentos.DescuentosDetallado', compact(
            'descuentosDetallados',
            'tiendas',
            'plazas',
            'filtrosAvanzadosActivos',
        ));
    }

    public function CatDescuentos(Request $request)
    {
        $nomDescuento = $request->nomDescuento;

        $tiposdescuentos = CatTipoDescuento::where('status', 0)->get();
        $tiendas = Tienda::where('status', 0)->get();
        $plazas = Plaza::where('status', 0)->get();

        return view('Descuentos.CatDescuentos', compact('nomDescuento', 'tiposdescuentos', 'tiendas', 'plazas'));
    }

    public function BuscarCodArticuloPaquqete(Request $request)
    {
        $codArticulo = $request->codArticulo;

        $articulo =  DB::table('CatArticulos as a')
            ->select('a.NomArticulo', 'b.PrecioArticulo')
            ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.CodArticulo', $codArticulo)
            ->first();

        $pArticulo = empty($articulo) ? '' : $articulo->NomArticulo . ' - $' . $articulo->PrecioArticulo;

        return $pArticulo;
    }

    public function GuardarDescuento(Request $request)
    {
        // return $request;
        $request->validate([
            'nomDescuento' => 'required|string|max:100',
            'tipoDescuento' => 'required|in:1,2,3',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'idTienda' => 'nullable|required_if:tipoDescuento,2|exists:CatTiendas,IdTienda',
            'idPlaza' => 'nullable|required_if:tipoDescuento,3|exists:CatPlazas,IdPlaza',
        ], [
            'nomDescuento.required' => 'El nombre del descuento es obligatorio',
            'nomDescuento.max' => 'El nombre no puede tener más de 100 caracteres',
            'tipoDescuento.required' => 'Debe seleccionar un tipo de descuento',
            'tipoDescuento.in' => 'Tipo de descuento inválido',
            'fechaInicio.required' => 'La fecha de inicio es obligatoria',
            'fechaFin.required' => 'La fecha de fin es obligatoria',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio',
            'idTienda.required_if' => 'Debe seleccionar una tienda para este tipo de descuento',
            'idTienda.exists' => 'La tienda seleccionada no existe',
            'idPlaza.required_if' => 'Debe seleccionar una plaza para este tipo de descuento',
            'idPlaza.exists' => 'La plaza seleccionada no existe',
        ]);

        $IdEncDescuento = $request->IdEncDescuento;
        $nomDescuento = $request->nomDescuento;
        try {
            if ($request->fechaInicio > $request->fechaFin) {
                return back()->with('msjdelete', 'La fecha de inicio no puede ser mayor a la fecha de fin.');
            }
            DB::beginTransaction();
            // Comprobamos si trae id de descuento, para saber si es actualizacion o insercion
            if ($IdEncDescuento != null) {
                // DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                //     ->update([
                //         'NomDescuento' => strtoupper($nomDescuento),
                //         'TipoDescuento' => $request->tipoDescuento,
                //         'FechaInicio' => $request->fechaInicio,
                //         'FechaFin' => $request->fechaFin,
                //         'FechaCreacion' => date('d-m-Y H:i:s'),
                //         'IdTienda' => $request->idTienda,
                //         'IdPlaza' => $request->idPlaza,
                //         'Status' => 0,
                //         'ModificadoPor' => Auth::id(),
                //         'FechaModificacion' => date('d-m-Y H:i:s')
                //     ]);
                $updateData = [
                    'NomDescuento' => strtoupper($nomDescuento),
                    'TipoDescuento' => $request->tipoDescuento,
                    'FechaInicio' => $request->fechaInicio,
                    'FechaFin' => $request->fechaFin,
                    'Status' => 0,
                    'ModificadoPor' => Auth::id(),
                    'FechaModificacion' => date('d-m-Y H:i:s')
                ];

                // Agregar tienda o plaza según el tipo
                if ($request->tipoDescuento == 2) {
                    $updateData['IdTienda'] = $request->idTienda;
                    $updateData['IdPlaza'] = null;
                } elseif ($request->tipoDescuento == 3) {
                    $updateData['IdTienda'] = null;
                    $updateData['IdPlaza'] = $request->idPlaza;
                } else {
                    $updateData['IdTienda'] = null;
                    $updateData['IdPlaza'] = null;
                }

                DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)->update($updateData);
            } else {
                // $catDescuento = new DatEncDescuentos();
                // $catDescuento->NomDescuento = strtoupper($nomDescuento);
                // $catDescuento->TipoDescuento = $request->tipoDescuento;
                // $catDescuento->FechaInicio = $request->fechaInicio;
                // $catDescuento->FechaFin = $request->fechaFin;
                // $catDescuento->IdTienda = $request->idTienda;
                // $catDescuento->IdPlaza = $request->idPlaza;
                // $catDescuento->Status = 0;
                // // $catDescuento->FechaCreacion = date('d-m-Y H:i:s');
                // $catDescuento->CreadoPor = Auth::id();
                // $catDescuento->FechaCreacion = date('d-m-Y H:i:s');
                // $catDescuento->ModificadoPor = Auth::id();
                // $catDescuento->FechaModificacion = date('d-m-Y H:i:s');
                // $catDescuento->save();
                $catDescuento = new DatEncDescuentos();
                $catDescuento->NomDescuento = strtoupper($nomDescuento);
                $catDescuento->TipoDescuento = $request->tipoDescuento;
                $catDescuento->FechaInicio = $request->fechaInicio;
                $catDescuento->FechaFin = $request->fechaFin;
                $catDescuento->Status = 0;
                $catDescuento->CreadoPor = Auth::id();
                $catDescuento->FechaCreacion = date('d-m-Y H:i:s');
                $catDescuento->ModificadoPor = Auth::id();
                $catDescuento->FechaModificacion = date('d-m-Y H:i:s');

                // Asignar tienda o plaza según el tipo
                if ($request->tipoDescuento == 2) {
                    $catDescuento->IdTienda = $request->idTienda;
                    $catDescuento->IdPlaza = null;
                } elseif ($request->tipoDescuento == 3) {
                    $catDescuento->IdTienda = null;
                    $catDescuento->IdPlaza = $request->idPlaza;
                } else {
                    $catDescuento->IdTienda = null;
                    $catDescuento->IdPlaza = null;
                }

                $catDescuento->save();
            }
            DB::commit();

            $IdEncDescuento = $IdEncDescuento != null ? $IdEncDescuento : $catDescuento->IdEncDescuento;
            $msg = $IdEncDescuento != null ? 'El descuento quedo actualizado correctamente.' : 'Se Agrego el Descuento: ' . $catDescuento->nomDescuento;

            return redirect('EditarDescuento/' . $IdEncDescuento)->with('msjAdd', $msg);
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error : ' . $th->getMessage());
            DB::rollback();
        }
    }

    public function EditarDescuento(Request $request, $IdEncDescuento)
    {
        $descuento = DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
            // ->where('status', 0)
            // ->whereNull('FechaDesactivar')
            ->first();

        if ($descuento == null) {
            return back()->with('msjdelete', 'No Existe el Descuento con el Id: ' . $IdEncDescuento);
        }

        $detalle = DatDetDescuentos::select('DatDetDescuentos.*', 'CatArticulos.NomArticulo', 'CatArticulos.CodArticulo')
            ->leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetDescuentos.IdArticulo')
            ->leftjoin('CatListasPrecio', 'CatListasPrecio.IdListaPrecio', 'DatDetDescuentos.IdListaPrecio')
            ->where('IdEncDescuento', $IdEncDescuento)
            ->get();

        $tiposdescuentos = CatTipoDescuento::where('status', 0)->get();
        $tiendas = Tienda::where('status', 0)->get();
        $plazas = Plaza::where('status', 0)->get();
        $ListaPrecio = ListaPrecio::get();

        $ListaPrecio = ListaPrecio::get();

        $articulos = Precio::select('DatPrecios.CodArticulo', 'ca.NomArticulo')
            ->leftJoin('CatArticulos as ca', 'DatPrecios.CodArticulo', '=', 'ca.CodArticulo')
            ->where('DatPrecios.IdListaPrecio', 1)
            ->where('DatPrecios.PrecioArticulo', '>', 0)
            ->where('ca.Status', 0)
            ->groupBy('DatPrecios.CodArticulo', 'ca.NomArticulo')
            ->get();

        return view('Descuentos.EditarDescuento', compact(
            'descuento',
            'detalle',
            'tiposdescuentos',
            'tiendas',
            'plazas',
            'ListaPrecio',
            'articulos'
        ));
    }

    public function EditarDescuentoExistente(Request $request, $idDescuento)
    {
        $codsArticulo = $request->CodArticulo ?? [];
        $listaPrecios = $request->listaPrecios;
        $preciosArticulo = $request->PrecioArticulo;
        $statuses = $request->Status ?? [];

        try {
            DB::beginTransaction();

            // Obtenemos las fechas del descuento a actualizar
            $descuento = DatEncDescuentos::where('IdEncDescuento', $idDescuento)->first();
            $tipoDescuento = $descuento->TipoDescuento;
            $fechaInicio = $descuento->FechaInicio;
            $fechaFin = $descuento->FechaFin;
            $idTienda = $descuento->IdTienda;
            $idPlaza = $descuento->IdPlaza;

            // Obtener todos los IDs de artículos de una sola vez
            $codigos = collect($codsArticulo);
            $articulosIds = DB::table('CatArticulos')
                ->whereIn('CodArticulo', $codigos)
                ->pluck('IdArticulo', 'CodArticulo')
                ->toArray();

            // Validar conflictos SOLO para artículos que vienen activos (Status = 0)
            $activosAValidar = [];
            foreach ($codsArticulo as $key => $codigo) {
                if ($statuses[$key] == 0) {
                    $activosAValidar[] = $articulosIds[$codigo] ?? null;
                }
            }

            if (!empty($activosAValidar)) {
                $existencias = DatEncDescuentos::select('DatEncDescuentos.*', 'DatDetDescuentos.IdArticulo')
                    ->leftJoin('DatDetDescuentos', 'DatDetDescuentos.IdEncDescuento', 'DatEncDescuentos.IdEncDescuento')
                    ->where('TipoDescuento', $tipoDescuento)
                    ->where('DatEncDescuentos.IdEncDescuento', '<>', $idDescuento)
                    ->where('DatDetDescuentos.IdEncDescuento', '<>', null)
                    ->where('DatEncDescuentos.FechaInicio', '<=', $fechaInicio)
                    ->where('DatEncDescuentos.FechaFin', '>=', $fechaFin)
                    ->where('DatEncDescuentos.IdTienda', $idTienda)
                    ->where('DatEncDescuentos.IdPlaza', $idPlaza)
                    ->whereIn('DatDetDescuentos.IdArticulo', $activosAValidar)
                    ->where('DatEncDescuentos.Status', 0)
                    ->where('DatDetDescuentos.Status', 0)
                    ->exists();

                if ($existencias) {
                    DB::rollback();
                    return back()->with('msjdelete', 'Uno o más productos ya cuentan con ese tipo de descuento activo en esas fechas.');
                }
            }

            // Recorrer todos los artículos y actualizar
            foreach ($codsArticulo as $key => $codArticulo) {
                $articuloId = $articulosIds[$codArticulo] ?? null;

                if (!$articuloId) {
                    DB::rollback();
                    return back()->with('msjdelete', 'No se encontró el artículo con código: ' . $codArticulo);
                }

                // Verificar si ya existe el registro
                $existe = DatDetDescuentos::where('IdEncDescuento', $idDescuento)
                    ->where('IdArticulo', $articuloId)
                    ->first();

                if ($existe) {
                    $existe->PrecioDescuento = $preciosArticulo[$key];
                    $existe->IdListaPrecio = $listaPrecios[$key];
                    $existe->Status = $statuses[$key] ?? 0;
                    $existe->ModificadoPor = Auth::id();
                    $existe->FechaModificacion = date('d-m-Y H:i:s');
                    $existe->save();
                } else {
                    $nuevo = new DatDetDescuentos();
                    $nuevo->IdEncDescuento = $idDescuento;
                    $nuevo->IdArticulo = $articuloId;
                    $nuevo->PrecioDescuento = $preciosArticulo[$key];
                    $nuevo->IdListaPrecio = $listaPrecios[$key];
                    $nuevo->Status =    0;
                    $nuevo->CreadoPor = Auth::id();
                    $nuevo->FechaCreacion = date('d-m-Y H:i:s');
                    $nuevo->ModificadoPor = Auth::id();
                    $nuevo->FechaModificacion = date('d-m-Y H:i:s');
                    $nuevo->save();
                }
            }

            DB::commit();
            return redirect('/EditarDescuento/' . $idDescuento)->with('msjupdate', 'Promoción actualizada correctamente!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function EliminarDescuento($IdEncDescuento)
    {
        try {
            $NomDescuento = DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->value('NomDescuento');

            DB::beginTransaction();
            DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->update([
                    'FechaDesactivar' => date('d-m-Y H:i:s'),
                    'ModificadoPor' => Auth::id(),
                    'Status' => 1
                ]);
            DB::commit();

            return back()->with('msjdelete', 'Se Elimino: ' . $NomDescuento);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function DesactivarArticuloPromocion(Request $request)
    {
        try {
            DB::beginTransaction();

            $IdEncDescuento = $request->IdEncDescuento;
            $CodArticulo = $request->CodArticulo;

            $idArticulo = Articulo::where('CodArticulo', $CodArticulo)->value('IdArticulo');

            // Buscar el detalle del descuento
            $detalle = DatDetDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->where('IdArticulo', $idArticulo)
                ->first();

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el artículo en la promoción'
                ]);
            }

            // Desactivar el artículo (Status = 1)
            $detalle->Status = 1;
            $detalle->ModificadoPor = Auth::id();
            $detalle->FechaModificacion = date('d-m-Y H:i:s');
            $detalle->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo desactivado correctamente'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $th->getMessage()
            ]);
        }
    }
}
