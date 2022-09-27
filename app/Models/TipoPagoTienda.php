<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoPago;

class TipoPagoTienda extends Model
{
    use HasFactory;
    protected $table = 'DatTipoPagoTienda';
    protected $fillable = ['IdDatTipoPagoTienda','IdTipoPago', 'IdTienda', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatTipoPagoTienda';

    public function TiposPago(){
        return $this->hasMany(TipoPago::class, 'IdTipoPago', 'IdTipoPago');
    }

}
