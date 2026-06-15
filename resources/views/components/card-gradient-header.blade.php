@props(['icon', 'title', 'subtitle' => null, 'iconFamily' => 'bi bi-'])

<!-- Alertas Toast Flotantes -->
<div class="alerts-toast-container">
    @include('Alertas.AlertasDashboard')
</div>

<div class="card-section bg-white">
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
            <div class="d-flex gap-2">
                {{ $buttons ?? '' }}
            </div>
        </div>
    </div>
    {{ $slot }}
</div>
