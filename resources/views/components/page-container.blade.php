@extends('PlantillaBase.masterbladeDashboard')
@section('title', $title ?? '')
@section('dashboardWidth', 'width-95')
@section('bodyTheme', $theme ?? '')

{{-- @section('contenido')
    <div {{ $attributes->merge(['class' => 'container-fluid d-flex flex-column gap-4 pb-4 p-0']) }}>
        {{ $slot }}
    </div>
@endsection --}}

@section('contenido')
    <div {{ $attributes->merge(['class' => 'container-fluid width-95 d-flex flex-column gap-4 py-4']) }}>
        {{ $slot }}
    </div>
@endsection
