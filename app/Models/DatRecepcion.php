<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CapRecepcion;

class DatRecepcion extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'DatRecepcion';
    protected $fillable = ['IdCapRecepcion', 'IdArticulo', 'CantEnviada', 'CantRecepcionada', 'Almacen', 'PackingList', 'IdStatusRecepcion'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatRecepcion';

    public function Recepcion()
    {
        return $this->hasOne(CapRecepcion::class, 'IdCapRecepcion', 'IdCapRecepcion');
    }
}
