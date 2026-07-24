<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatTicketHabilitadoFacturacion extends Model
{
    protected $table = 'DatTicketHabilitadoFacturacion';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'IdEncabezado',
        'IdUsuario',
        'IdTienda',
        'Fecha',
        'Activo',
        'Observaciones'
    ];

    protected $casts = [
        'Fecha' => 'datetime',
        'Activo' => 'boolean'
    ];

    public function encabezado()
    {
        return $this->belongsTo(DatEncabezado::class, 'IdEncabezado', 'IdEncabezado');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdUsuario', 'IdUsuario');
    }
}
