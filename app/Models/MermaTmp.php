<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MermaTmp extends Model
{
    use HasFactory;
    protected $table = 'MermasTmp';
    protected $fillable = [
        'IdTienda', 'CodArticulo', 'CantArticulo', 
        'IdTipoMerma', 'IdSubTipoMerma', 'Comentario'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdTmpMerma';
}
