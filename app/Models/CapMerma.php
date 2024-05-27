<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;
use App\Models\TipoMerma;
use App\Models\ItemCloudTable;
use App\Models\OnHanTiendaCloudTable;

class CapMerma extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'CapMermas';
    protected $fillable = [
        'IdTienda', 'FechaCaptura', 'CodArticulo',
        'CantArticulo', 'IdTipoMerma', 'IdSubTipoMerma',
        'Comentario', 'IdUsuarioCaptura', 'IdUsuarioInterfaz', 'FechaInterfaz'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdMerma';

    public function Articulos(){
        return $this->hasMany(Articulo::class, 'CodArticulo', 'CodArticulo');
    }

    public function TiposMerma(){
        return $this->hasMany(TipoMerma::class, 'IdTipoMerma', 'IdTipoMerma');
    }

    public function Lotes(){
        return $this->hasMany(ItemCloudTable::class, 'ITEM_NUMBER', 'CodArticulo');
    }
}
