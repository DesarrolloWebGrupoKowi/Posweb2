<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioTmp extends Model
{
    use HasFactory;
    protected $table = 'DatPreciosTmp';
    protected $primaryKey = 'IdListaPrecio';
    public $timestamps = false;
}
