<x-page-container title="Novedades - Ayuda">
    <x-card-gradient-header
        icon="stars"
        title="Novedades"
        subtitle="Versión actual {{ config('app.version') }}"
    >
        <x-slot:buttons>
            <a
                href="{{ route('ayuda') }}"
                class="btn-header-ghost text-decoration-none"
            >
                <i class="bi bi-arrow-left"></i> Centro de ayuda
            </a>
            <x-header.buttons.home-button />
        </x-slot:buttons>

        <div class="p-4">
            @forelse ($versiones as $version)
                <section class="version-item">
                    <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                        <span class="{{ $loop->first ? 'tags-green' : 'tags-blue' }}">v{{ $version['version'] }}</span>
                        <h5 class="version-titulo mb-0">{{ $version['titulo'] }}</h5>
                        @if ($version['fecha'])
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($version['fecha'])->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                            </small>
                        @endif
                    </div>
                    <div class="doc-content">
                        {!! $version['html'] !!}
                    </div>
                </section>
            @empty
                <p class="text-muted py-4 text-center">Todavía no hay notas de versión.</p>
            @endforelse
        </div>
    </x-card-gradient-header>

    @include('Ayuda.estilos')
</x-page-container>
