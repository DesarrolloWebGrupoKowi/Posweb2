<div>
    <div class="router d-flex align-items-center gap-2 mb-3">
        <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
            </path>
        </svg>
        <a class="text-secondary text-decoration-none gap-2" style="font-weight: 500" href="/Dashboard">
            Dashboard</a>
        <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
        </svg>
        @isset($options)
            @foreach ($options as $option)
                <a class="first-upper text-lowercase text-secondary text-decoration-none" style="font-weight: 500"
                    href="{{ $option['value'] }}">{{ $option['name'] }}</a>
                <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            @endforeach
        @endisset
        <a class="first-upper text-lowercase text-dark text-decoration-none"style="font-weight: 500"
            href="">@yield('title')</a>
    </div>
    <h2>{{ $titulo }}</h2>
</div>
