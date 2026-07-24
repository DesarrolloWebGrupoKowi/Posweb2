@props([
    'corteTienda' => null,
    'corteTiendaSolicitudes' => null,
])

@if (request()->get('fecha_fin', date('Y-m-d')) != date('Y-m-d') &&
        !(count($corteTienda) == 0 && count($corteTiendaSolicitudes) == 0))
    <div
        class="btn-group"
        style="gap: 0;"
    >
        <a
            href="/procesarclientescontado/{{ request()->get('fecha_fin', date('Y-m-d')) }}/{{ request()->get('tienda_id') }}/-1"
            class="btn btn-sm d-flex align-items-center gap-1"
            id="rotateButton"
            style="background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; border-radius: 8px 0 0 8px; padding: 8px 12px; font-size: 0.8rem; font-weight: 500; transition: all 0.3s ease;"
            onmouseover="this.style.background='#dbeafe'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.background='#eff6ff'; this.style.transform='translateY(0)'"
        >
            <span id="buttonIcon">
                <i class="bi bi-cloud-upload"></i>
            </span>
            Procesar contado
        </a>
        <a
            href="/procesarclientesfacturas/{{ request()->get('fecha_fin', date('Y-m-d')) }}/{{ request()->get('tienda_id') }}/-1"
            class="btn btn-sm d-flex align-items-center gap-1"
            id="rotateButtonFac"
            style="background: #f0fdf4; color: #10b981; border: 1px solid #bbf7d0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.8rem; font-weight: 500; transition: all 0.3s ease;"
            onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
        >
            <span id="buttonIconFac">
                <i class="bi bi-cloud-upload"></i>
            </span>
            Procesar facturas
        </a>
    </div>
@endif
