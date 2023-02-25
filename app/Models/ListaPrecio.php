<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Precio;
use App\Models\DatDetalle;

class ListaPrecio extends Model
{
    use HasFactory;
    protected $table = 'CatListasPrecio';
    protected $fillable = ['NomListaPrecio',
                            'PesoMinimo',
                            'PesoMaximo',
                            'PorcentajeIva',
                            'Status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdListaPrecio';

    public function PreciosArticulo(){
        return $this->hasMany(Precio::class, 'IdListaPrecio');
    }

    public function Ventas(){
        return $this->hasMany(DatDetalle::class, 'IdListaPrecio', 'IdListaPrecio')
            ->selectRaw('IdListaPrecio, SUM(DatDetalle.ImporteArticulo) as ImporteTotal')
            ->groupBy('IdListaPrecio');
    }
}
