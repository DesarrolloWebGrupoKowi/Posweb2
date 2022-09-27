<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaPrecioTienda extends Model
{
    use HasFactory;
    protected $table = 'DatListaPrecioTienda';
    protected $fillable = ['IdTienda',
                            'IdListaPrecio',
                            'Status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdListaPrecioTienda';
}
