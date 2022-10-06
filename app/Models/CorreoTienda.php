<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorreoTienda extends Model
{
    use HasFactory;
    protected $table = 'DatCorreosTienda';
    protected $fillable = ['IdTienda','EncargadoCorreo', 'GerenteCorreo', 
                            'SupervisorCorreo', 'AdministrativaCorreo', 
                            'AlmacenistaCorreo', 'RecepcionCorreo', 
                            'FacturistaCorreo', 'Status'
                    ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatCorreoTienda';
}
