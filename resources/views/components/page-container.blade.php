@extends('PlantillaBase.masterbladeDashboard')
@section('title', $title ?? '')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div {{ $attributes->merge(['class' => 'container-fluid width-95 d-flex flex-column gap-4 py-4']) }}>
        {{ $slot }}
    </div>
@endsection
