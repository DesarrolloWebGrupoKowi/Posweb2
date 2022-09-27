<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Caja;

class DatCaja extends Model
{
    use HasFactory;
    protected $table = 'DatCajas';
    protected $fillable = ['IdTienda', 'IdCaja', 'Activa', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatCajas';

    public function Caja(){
        return $this->hasMany(Caja::class, 'IdCaja', 'IdCaja');
    }
}
