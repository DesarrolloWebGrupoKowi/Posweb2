<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;
    protected $table = 'DatPrecios';
    protected $primaryKey = 'IdListaPrecio';
    public $timestamps = false;
}
