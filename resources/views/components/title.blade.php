<div>
    <div class="router">
        <a class="text-secondary text-decoration-none" href="/Dashboard">Dashboard</a>
        <span class="text-secondary fw-bold">/</span>
        @isset($options)
            @foreach ($options as $option)
                <a class="text-secondary text-decoration-none" href="{{ $option['value'] }}">{{ $option['name'] }}</a>
                <span class="text-secondary fw-bold">/</span>
            @endforeach
        @endisset
        <a class="text-dark text-decoration-none" href="">@yield('title')</a>
    </div>
    <h2>{{ $titulo }}</h2>
</div>
