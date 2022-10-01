<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionTienda extends Model
{
    use HasFactory;
    protected $table = 'DatTransaccionesTienda';
    protected $fillable = ['IdTienda', 'IdTiendaDestino', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatTransaccionTienda';
}
