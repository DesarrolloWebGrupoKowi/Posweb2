@props(['name', 'label', 'checked' => false, 'helperText' => null, 'width' => '150px', 'compact' => false])

<div class="col-12 col-md-6 col-xl-3 relative">
    <div
        class="input-group d-flex {{ $compact ? 'input-group-sm' : '' }}"
        style="width: 100%;"
    >
        <span
            class="input-group-text border-gray-300 bg-gray-100"
            {{-- style="width: {{ $width }}" --}}
        >
            {{ $label }}
        </span>
        <div class="input-group-text bg-white">
            <input
                type="checkbox"
                class="form-check-input m-0"
                name="{{ $name }}"
                id="{{ $name }}"
                {{ $checked ? 'checked' : '' }}
                {{ $attributes }}
            >
        </div>
        @if ($helperText)
            <span class="input-group-text border-start-0 flex-grow-1 bg-gray-100">
                <small class="text-muted">{{ $helperText }}</small>
            </span>
        @endif
    </div>
</div>
