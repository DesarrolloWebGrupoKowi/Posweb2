<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NotificacionClienteCloud;
use App\Models\Tienda;

class MovimientoClienteCloud extends Model
{
    use HasFactory;
    protected $table = 'CatMovimientosClienteCloud';
    protected $fillable = ['NomMovimiento', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdMovimientoCliente'; 

    public function Notificaciones(){
        return $this->hasMany(NotificacionClienteCloud::class, 'IdMovimiento', 'IdMovimientoCliente');
    }
}
