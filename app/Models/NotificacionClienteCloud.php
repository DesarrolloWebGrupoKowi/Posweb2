<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionClienteCloud extends Model
{
    use HasFactory;
    protected $table = 'DatNotificacionesClienteCloud';
    protected $fillable = ['IdTienda', 
    'IdClienteCloud', 
    'IdMovimiento', 
    'Calle', 
    'NumExt', 
    'NumInt', 
    'Colonia', 
    'Ciudad', 
    'Municipio', 
    'Estado', 
    'Pais', 
    'CodigoPostal', 
    'Email', 
    'Telefono',
    'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatNotificacionesClienteCloud'; 
}
