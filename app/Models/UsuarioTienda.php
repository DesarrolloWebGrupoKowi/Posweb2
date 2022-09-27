<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioTienda extends Model
{
    use HasFactory;
    protected $table = 'CatUsuariosTienda';
    protected $fillable = ['IdUsuario','IdTienda','IdPlaza','Todas','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdUsuarioTienda';
}
