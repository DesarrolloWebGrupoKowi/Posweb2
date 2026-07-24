<!-- Modal Relacionar Cliente -->
<div
    class="modal fade"
    id="ModalRelacionarCliente{{ $cliente->IdCatCliente }}"
    tabindex="-1"
    aria-labelledby="ModalRelacionarCliente{{ $cliente->IdCatCliente }}Label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: #eff6ff;"
                >
                    <i
                        class="bi bi-link-45deg"
                        style="font-size: 1.5rem; color: #3b82f6;"
                    ></i>
                </div>

                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >Ligar Cliente</h5>

                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 16px;">
                    ¿Está seguro de ligar la solicitud de factura con este cliente?
                </p>

                <div
                    class="rounded-3 mb-4 p-3 text-start"
                    style="background: #f8fafc;"
                >
                    <div
                        class="mb-2"
                        style="font-size: 0.85rem;"
                    >
                        <span style="color: #94a3b8; font-weight: 500;">SITIO:</span>
                        <span style="color: #0f172a; font-weight: 600;">{{ $cliente->Sitio }}</span>
                    </div>
                    <div
                        class="mb-2"
                        style="font-size: 0.85rem;"
                    >
                        <span style="color: #94a3b8; font-weight: 500;">CLIENTE:</span>
                        <span style="color: #0f172a; font-weight: 600;">{{ $cliente->IdClienteCloud }}</span>
                    </div>
                    <div
                        class="mb-2"
                        style="font-size: 0.85rem;"
                    >
                        <span style="color: #94a3b8; font-weight: 500;">SHIP TO:</span>
                        <span style="color: #0f172a; font-weight: 600;">{{ $cliente->Ship_To }}</span>
                    </div>
                    <div style="font-size: 0.85rem;">
                        <span style="color: #94a3b8; font-weight: 500;">BILL TO:</span>
                        <span style="color: #0f172a; font-weight: 600;">{{ $cliente->Bill_To }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <a
                        href="{{ '/SolicitudesFactura/Relacionar/' . $solicitud->Id . '/' . $cliente->Bill_To }}"
                        class="btn flex-grow-1 d-flex align-items-center justify-content-center"
                        style="background: #3b82f6; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; text-decoration: none;"
                    >
                        <i class="bi bi-link-45deg me-1"></i> Ligar Cliente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
