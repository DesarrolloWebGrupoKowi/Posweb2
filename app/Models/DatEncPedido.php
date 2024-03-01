<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\DatDetPedido;
use App\Models\Articulo;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;


class DatEncPedido extends Model
{
    use HasFactory;
    protected $table = 'DatEncPedido';
    protected $fillable = ['IdPedido', 'IdTienda', 'Cliente', 'Telefono', 'FechaPedido', 'FechaRecoger', 'ImportePedido', 'IdUsuario', 'Status'];
    public $timestamps = false;

    public function ArticuloDetalle()
    {
        return $this->belongsToMany(Articulo::class, DatDetPedido::class, 'IdPedido', 'IdArticulo', 'IdPedido')
            ->withPivot('IdArticulo', 'CantArticulo', 'SubTotalArticulo', 'IvaArticulo', 'ImporteArticulo', 'PrecioArticulo')
            ->as('DetallePedido');
    }

    public function Venta()
    {
        return $this->hasOne(DatDetalle::class, 'IdPedido', 'IdPedido')
            ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'DatDetalle.IdEncabezado');
    }
}
