<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMermaArticulo extends Model
{
    use HasFactory;
    protected $table = 'DatArticulosTipoMerma';
    protected $fillable = ['IdTipoMerma', 'CodArticulo', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatArticuloTipoMerma';
}
