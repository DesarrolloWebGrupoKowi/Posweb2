<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoProducto extends Model
{
    use HasFactory;
    protected $table = 'CatMovimientosProducto';
    protected $fillable = ['NomMovimiento', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdMovimiento'; 
}
