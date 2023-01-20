<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCreditoEmpleado extends Model
{
    use HasFactory;
    protected $table = 'DatConcenVenta';
    public $timestamps = false;
}
