@props(['icon', 'title', 'subtitle' => null, 'iconFamily' => 'bi bi-'])

<!-- Alertas Toast Flotantes -->
<div class="alerts-toast-container">
    @include('Alertas.AlertasDashboard')
</div>

<div
    class="card-section {{ $attributes->get('class') }} bg-white"
    style="{{ $attributes->get('style') }}"
    {{-- style="{{ $attributes->get('style') }} border-radius:0px" --}}
>
    <div class="card-section-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="card-section-icon">
                    <i
                        class="{{ $iconFamily . $icon }} text-white"
                        style="font-size: 1.2rem;"
                    ></i>
                </div>
                <div>
                    <h2 class="card-section-title">{{ $title }}</h2>
                    @if ($subtitle)
                        <p class="card-section-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @php $manualAyuda = app(\App\Services\AyudaService::class)->manualPorRuta(request()->route()?->getName()); @endphp
                @if ($manualAyuda)
                    <a
                        href="{{ route('ayuda.show', $manualAyuda['slug']) }}"
                        class="btn-header-ghost text-decoration-none"
                        title="¿Cómo se usa esta pantalla?"
                    >
                        <i class="bi bi-question-circle"></i> Ayuda
                    </a>
                @endif
                {{ $buttons ?? '' }}
            </div>
        </div>
    </div>
    {{ $slot }}
</div>
