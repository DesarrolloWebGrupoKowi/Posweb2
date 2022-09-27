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
    protected $primaryKey = 'IdMovimiento'; 

    public function Notificaciones(){
        return $this->belongsToMany(Tienda::class, NotificacionClienteCloud::class, 'IdMovimiento', 'IdTienda', 'IdMovimiento')
                    ->select('CatTiendas.NomTienda')
                    ->withPivot('IdDatNotificacionesClienteCloud', 'IdClienteCloud', 'NomCliente', 'RFC', 'Calle', 'NumExt', 'NumInt', 'Colonia', 'Ciudad', 'Municipio', 'Estado', 'Pais', 'CodigoPostal', 'Email', 'Telefono', 'Status')
                    ->as('PivotCliente');
    }
}
