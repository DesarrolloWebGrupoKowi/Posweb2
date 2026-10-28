<x-page-container title="Centro de ayuda">
    <x-card-gradient-header
        icon="life-preserver"
        title="Centro de ayuda"
        subtitle="Manuales y novedades de Posweb"
    >
        <x-slot:buttons>
            <a
                href="{{ route('ayuda.versiones') }}"
                class="btn-header-ghost text-decoration-none"
            >
                <i class="bi bi-stars"></i> Novedades
            </a>
            <x-header.buttons.home-button />
        </x-slot:buttons>

        <div class="p-4">
            @if ($ultimaVersion)
                <a
                    href="{{ route('ayuda.versiones') }}"
                    class="ayuda-version d-flex align-items-center text-decoration-none mb-4 gap-3"
                >
                    <span class="ayuda-version-icono"><i class="bi bi-stars"></i></span>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="ayuda-version-badge">Nuevo</span>
                            <span class="ayuda-version-numero">v{{ $ultimaVersion['version'] }}</span>
                            @if ($ultimaVersion['fecha'])
                                <small>{{ \Carbon\Carbon::parse($ultimaVersion['fecha'])->locale('es')->isoFormat('D [de] MMMM') }}</small>
                            @endif
                        </div>
                        <div class="ayuda-version-titulo">{{ $ultimaVersion['titulo'] }}</div>
                    </div>
                    <span class="ayuda-version-ver">Ver novedades <i class="bi bi-arrow-right"></i></span>
                </a>
            @endif

            @forelse ($categorias as $categoria => $manuales)
                <div class="ayuda-categoria mb-4">
                    <h6 class="ayuda-categoria-titulo">{{ $categoria }}</h6>
                    <div class="row g-3">
                        @foreach ($manuales as $manual)
                            <div class="ayuda-item col-xl-3 col-lg-4 col-md-6">
                                <a
                                    href="{{ route('ayuda.show', $manual['slug']) }}"
                                    class="ayuda-card d-flex text-decoration-none h-100 gap-3"
                                >
                                    <div class="icon-circle-blue ayuda-card-icono flex-shrink-0">
                                        <i class="bi bi-{{ $manual['icono'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="ayuda-card-titulo">{{ $manual['titulo'] }}</div>
                                        <small class="ayuda-card-descripcion">{{ $manual['descripcion'] }}</small>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-muted py-4 text-center">Todavía no hay manuales disponibles.</p>
            @endforelse
        </div>
    </x-card-gradient-header>

    @include('Ayuda.estilos')
</x-page-container>
