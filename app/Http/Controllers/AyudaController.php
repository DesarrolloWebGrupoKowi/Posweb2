<?php

namespace App\Http\Controllers;

use App\Services\AyudaService;

class AyudaController extends Controller
{
    public function __construct(protected AyudaService $ayudaService)
    {
    }

    public function index()
    {
        $categorias = $this->ayudaService->manuales()->where('indice', true)->groupBy('categoria');
        $ultimaVersion = $this->ayudaService->versiones()->first();

        return view('Ayuda.index', compact('categorias', 'ultimaVersion'));
    }

    public function show(string $slug)
    {
        $manual = $this->ayudaService->manual($slug);
        abort_if(!$manual, 404);

        $html = $this->ayudaService->html($manual);

        return view('Ayuda.show', compact('manual', 'html'));
    }

    public function versiones()
    {
        $versiones = $this->ayudaService->versiones()->map(fn($version) => [
            ...$version,
            'html' => $this->ayudaService->html($version),
        ]);

        return view('Ayuda.versiones', compact('versiones'));
    }
}
