@props([
    'title' => 'KPI',
    'value' => '0',
    'subtitle' => null,
    'subtitleHtml' => null,
    'color' => 'primary',
    'icon' => null,
    'trend' => null,
    'trendValue' => null,
    'trendLabel' => null,
    'currency' => false,
    'format' => null,
    'colClass' => 'col-lg-3 col-sm-6',
])

@php
    $colors = [
        'primary' => ['bg' => 'rgba(102, 126, 234, 0.1)', 'text' => '#667eea'],
        'success' => ['bg' => 'rgba(56, 249, 215, 0.1)', 'text' => '#10b981'],
        'danger' => ['bg' => 'rgba(245, 87, 108, 0.1)', 'text' => '#f5576c'],
        'info' => ['bg' => 'rgba(0, 242, 254, 0.1)', 'text' => '#00bcd4'],
        'warning' => ['bg' => 'rgba(251, 188, 5, 0.1)', 'text' => '#fbbf05'],
        'purple' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'text' => '#8b5cf6'],
        'pink' => ['bg' => 'rgba(236, 72, 153, 0.1)', 'text' => '#ec4899'],
        'indigo' => ['bg' => 'rgba(99, 102, 241, 0.1)', 'text' => '#6366f1'],
        'teal' => ['bg' => 'rgba(3, 84, 63, 0.1)', 'text' => '#03543f'],
        'brown' => ['bg' => 'rgba(114, 59, 19, 0.1)', 'text' => '#723b13'],
        'blue' => ['bg' => 'rgba(9, 109, 217, 0.1)', 'text' => '#096dd9'],
    ];

    $colorStyle = $colors[$color] ?? $colors['primary'];

    $displayValue = $value;
    if ($currency) {
        $displayValue = '$' . number_format($value, 2);
    } elseif ($format === 'percentage') {
        $displayValue = $value . '%';
    } elseif ($format === 'compact') {
        if ($value >= 1000000) {
            $displayValue = '$' . number_format($value / 1000000, 1) . 'M';
        } elseif ($value >= 1000) {
            $displayValue = '$' . number_format($value / 1000, 1) . 'K';
        } else {
            $displayValue = '$' . number_format($value, 0);
        }
    }

    $trendIcon = null;
    $trendColor = 'text-muted';
    if ($trend === 'up') {
        $trendIcon = '↑';
        $trendColor = 'text-success';
    } elseif ($trend === 'down') {
        $trendIcon = '↓';
        $trendColor = 'text-danger';
    } elseif ($trend === 'stable') {
        $trendIcon = '→';
        $trendColor = 'text-warning';
    }
@endphp

<div class="{{ $colClass }}">
    <div
        class="card h-100 border-0 shadow-sm"
        style="border-radius: 10px; transition: all 0.2s;"
    >
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="card-subtitle text-muted fw-500 mb-2">{{ $title }}</h6>
                    <h3 class="card-title mb-0 text-gray-800">{{ $displayValue }}</h3>

                    @if ($subtitle)
                        <small class="d-block text-muted mt-1">{{ $subtitle }}</small>
                    @endif

                    @if ($subtitleHtml)
                        <small class="d-block text-muted mt-1">
                            {!! $subtitleHtml !!}
                        </small>
                    @endif

                    @if ($trendValue)
                        <div class="d-flex align-items-center mt-2 gap-2">
                            <span
                                class="badge {{ $trendColor }} bg-{{ str_replace('text-', '', $trendColor) }}-subtle"
                            >
                                {{ $trendIcon }} {{ $trendValue }}%
                            </span>
                            @if ($trendLabel)
                                <small class="text-muted">{{ $trendLabel }}</small>
                            @endif
                        </div>
                    @endif
                </div>

                <div
                    class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="background-color: {{ $colorStyle['bg'] }}; min-width: 44px; height: 44px;"
                >
                    <div
                        style="color: {{ $colorStyle['text'] }};"
                        class="d-flex align-items-center justify-content-center"
                    >
                        @if ($icon)
                            @include($icon)
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
