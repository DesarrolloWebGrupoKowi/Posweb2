<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Precio;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioTienda;
use App\Models\InventarioTienda;

class Articulo extends Model
{
    use HasFactory;
    protected $table = 'CatArticulos';
    protected $fillable = ['CodArticulo',
            'NomArticulo',
            'Amece',
            'UOM',
            'UOM2',
            'Peso',
            'Tercero',
            'CodEtiqueta',
            'PrecioRecorte',
            'Factor',
            'Inventory_Item_Id',
            'IdTipoArticulo',
            'IdFamilia',
            'IdGrupo',
            'Iva',
            'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdArticulo';

    public function PrecioArticulo(){
        return $this->belongsToMany(ListaPrecio::class, Precio::class, 'CodArticulo', 'IdListaPrecio', 'CodArticulo')
                ->withPivot('PrecioArticulo')
                ->as('PivotPrecio');
    }

    public function Stock(){
            return $this->hasOne(InventarioTienda::class, 'CodArticulo', 'CodArticulo');
    }
}


