@props([
    'title' => 'No hay datos para mostrar',
    'message' => null,
    'icon' => 'box',
    'suggestion' => null,
    'colspan' => null,
    'action' => null,
    'actionText' => null,
    'actionUrl' => null,
])

@php
    $icons = [
        'box' =>
            'M20 7L4 7M12 3L12 21M3 7L3 17C3 18.1046 3.89543 19 5 19L19 19C20.1046 19 21 18.1046 21 17L21 7M7 12L10 15L17 8',
        'search' =>
            'M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z',
        'calendar' =>
            'M8 2L8 6M16 2L16 6M3 10L21 10M5 4L19 4C20.1046 4 21 4.89543 21 6L21 20C21 21.1046 20.1046 22 19 22L5 22C3.89543 22 3 21.1046 3 20L3 6C3 4.89543 3.89543 4 5 4Z',
        'filter' => 'M3 4L21 4M6 9L18 9M10 14L14 14M12 19L12 22',
        'ticket' =>
            'M4 4L20 4C21.1046 4 22 4.89543 22 6L22 18C22 19.1046 21.1046 20 20 20L4 20C2.89543 20 2 19.1046 2 18L2 6C2 4.89543 2.89543 4 4 4ZM8 8L16 8M8 12L12 12',
        'user' =>
            'M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12ZM19 21C19 17.6863 16.3137 15 13 15H11C7.68629 15 5 17.6863 5 21M12 3C9.23858 3 7 5.23858 7 8C7 10.7614 9.23858 13 12 13C14.7614 13 17 10.7614 17 8C17 5.23858 14.7614 3 12 3Z',
        'store' => 'M3 9L5 5H19L21 9M3 9H21M3 9V20H21V9M8 9V13H16V9M7 5V3M17 5V3',
    ];

    $iconPath = $icons[$icon] ?? $icons['box'];
@endphp

@if ($colspan)
    <td
        colspan="{{ $colspan }}"
        class="py-5 text-center"
    >
@endif
<div class="d-flex flex-column align-items-center justify-content-center">
    <!-- Icono -->
    <div
        class="rounded-circle d-flex align-items-center justify-content-center mb-3 p-3"
        style="background-color: rgba(30, 41, 59, 0.05); width: 80px; height: 80px;"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="32"
            height="32"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            style="color: #94a3b8;"
        >
            <path d="{{ $iconPath }}"></path>
        </svg>
    </div>

    <!-- Título -->
    <h6
        class="fw-semibold mb-2"
        style="color: #1e293b;"
    >{{ $title }}</h6>

    <!-- Mensaje -->
    @if ($message)
        <p
            class="text-muted small mb-2 text-center"
            style="max-width: 500px;"
        >
            {{ $message }}
        </p>
    @endif

    <!-- Sugerencia -->
    @if ($suggestion)
        <div
            class="mt-2 p-2"
            style="background-color: #f8f9fa; border-radius: 6px;"
        >
            <span class="small text-muted d-flex align-items-center gap-2">
                @include('components.icons.lightbulb', ['width' => 14, 'height' => 14])
                {{ $suggestion }}
            </span>
        </div>
    @endif

    <!-- Acción opcional -->
    @if ($action && $actionUrl)
        <div class="mt-3">
            <a
                href="{{ $actionUrl }}"
                class="btn btn-sm btn-outline-primary"
            >
                {{ $actionText ?? $action }}
            </a>
        </div>
    @endif
</div>
@if ($colspan)
    </td>
@endif
