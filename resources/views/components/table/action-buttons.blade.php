@props([
    'id',
    'editModal' => null,
    'deleteModal' => null,
    'passwordModal' => null,
    'activateModal' => null,
    'showEdit' => true,
    'showDelete' => true,
    'showPassword' => true,
    'showActivate' => false,
    'editLabel' => 'Ver',
    'deleteLabel' => null,
    'passwordLabel' => null,
])

<div class="d-flex gap-2">
    @if ($activateModal)
        <button
            class="btn-table-action btn-table-activate"
            data-bs-toggle="modal"
            data-bs-target="#{{ $activateModal }}{{ $id }}"
            title="Activar usuario"
        >
            <i class="bi bi-toggle-on"></i>
        </button>
    @endif
    @if ($editModal && $showEdit)
        <button
            class="btn-table-action btn-table-edit"
            data-bs-toggle="modal"
            data-bs-target="#{{ $editModal }}{{ $id }}"
            title="Modificar usuario"
        >
            <i class="bi bi-pencil"></i> {{ $editLabel }}
        </button>
    @endif
    @if ($deleteModal && $showDelete)
        <button
            class="btn-table-action btn-table-delete"
            data-bs-toggle="modal"
            data-bs-target="#{{ $deleteModal }}{{ $id }}"
            title="Desactivar usuario"
        >
            <i class="bi bi-trash"></i> {{ $deleteLabel }}
        </button>
    @endif
    @if ($passwordModal && $showPassword)
        <button
            class="btn-table-action btn-table-password"
            data-bs-toggle="modal"
            data-bs-target="#{{ $passwordModal }}{{ $id }}"
            title="Cambiar contraseña"
        >
            <i class="bi bi-key"></i> {{ $passwordLabel }}
        </button>
    @endif
</div>
