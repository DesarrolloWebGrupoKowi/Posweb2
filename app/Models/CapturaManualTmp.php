<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapturaManualTmp extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'CapRecepcionManualTmp';
    protected $fillable = ['IdTienda', 'CodArticulo', 'CantArticulo', 'Referencia', 'IdMovimiento'];
    public $timestamps = false;
    protected $primaryKey = 'IdCapRecepcionManual';
}
