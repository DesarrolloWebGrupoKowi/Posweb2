<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArticulo extends Model
{
    use HasFactory;
    protected $table = 'CatTipoArticulos';
    protected $fillable = ['IdTipoArticulo', 'NomTipoArticulo', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdCatTipoArticulo';
}
