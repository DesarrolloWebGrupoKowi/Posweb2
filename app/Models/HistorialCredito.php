<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialCredito extends Model
{
    use HasFactory;
    protected $table = 'HistorialCreditos';
    protected $fillable = ['
        FechaDesde', 
        'FechaHasta', 
        'FechaInterfaz', 
        'TipoNomina', 
        'IdUsuario', 
        'NumNomina'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdHistorialCredito';
}
