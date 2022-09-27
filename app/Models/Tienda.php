<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClienteCloudTienda;
use App\Models\ClienteCloud;
use App\Models\TipoPago;
use App\Models\TipoPagoTienda;

class Tienda extends Model
{
    use HasFactory;
    protected $table = 'CatTiendas';
    protected $fillable = [
                            'NomTienda',
                            'Correo',
                            'Direccion',
                            'Colonia',
                            'Telefono',
                            'RFC',
                            'IdListaPrecios',
                            'TiendaActiva',
                            'Inventario',
                            'CentroCosto',
                            'Almacen',
                            'Organization_Name',
                            'Subinventory_Code',
                            'Order_Type_Cloud',
                            'ServicioaDomicilio',
                            'CostoaDomicilio',
                            'Comentario',
                            'Status'
                           ];
    public $timestamps = false;
    protected $primaryKey = 'IdTienda';

    public function ClienteCloud(){
        return $this->belongsToMany(ClienteCLoud::class, ClienteCloudTienda::class, 'IdTienda', 'IdCLienteCloud', 'IdTienda', 'IdCLienteCloud')
                    ->withPivot('Ship_To', 'Bill_To')
                    ->as('PivotCustomer');
    }

    public function TiposPago(){
        return $this->belongsToMany(TipoPago::class, TipoPagoTienda::class, 'IdTienda', 'IdTipoPago', 'IdTienda');
    }
}
