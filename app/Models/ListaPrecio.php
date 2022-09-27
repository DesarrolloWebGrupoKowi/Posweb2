<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Precio;

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

    public function PreciosArticulo()
    {
        return $this->hasMany(Precio::class, 'IdListaPrecio');
    }
}
