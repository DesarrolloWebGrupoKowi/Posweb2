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
        'Status',
        'fechaprocesarcorte',
        'usuarioprocesarcorte',
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdTienda';

    // protected $casts = [
    //     'fechaprocesarcorte' => 'datetime',
    // ];

    public function ClienteCloud()
    {
        return $this->belongsToMany(ClienteCLoud::class, ClienteCloudTienda::class, 'IdTienda', 'IdCLienteCloud', 'IdTienda', 'IdCLienteCloud')
            ->withPivot('Ship_To', 'Bill_To')
            ->as('PivotCustomer');
    }

    public function TiposPago()
    {
        return $this->belongsToMany(TipoPago::class, TipoPagoTienda::class, 'IdTienda', 'IdTipoPago', 'IdTienda');
    }

    public function EmpleadoProcesarcorte()
    {
        return $this->hasOneThrough(
            Empleado::class,   // Modelo final
            Usuario::class,    // Modelo intermedio
            'IdUsuario',       // FK en CatUsuarios
            'NumNomina',       // FK en CatEmpleados
            'usuarioprocesarcorte', // FK en CatTiendas
            'NumNomina'        // PK relacionada en CatUsuarios
        );
    }
}
