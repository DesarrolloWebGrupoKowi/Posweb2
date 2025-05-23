<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionSinInternet extends Model
{
    use HasFactory;
    protected $table = 'CapRecepcionManualTmp';
    protected $fillable = ['IdTienda', 'CodArticulo', 'CantArticulo', 'Referencia', 'IdMovimiento'];
    public $timestamps = false;
    protected $primaryKey = 'IdCapRecepcionManual';
}
