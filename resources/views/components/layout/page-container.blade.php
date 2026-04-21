@props(['title', 'dashboardWidth' => 'width-95', 'height' => 'calc(100vh - 90px)'])

<div
    class="container-fluid {{ $dashboardWidth }} d-flex flex-column gap-4 pt-4"
    style="height: {{ $height }}"
>
    {{ $slot }}
</div>
