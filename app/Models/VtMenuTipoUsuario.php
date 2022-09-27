<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class VtMenuTipoUsuario extends Model
{
    use HasFactory;
    protected $table = 'VT_TIPOUSUARIO_MENUS';
    protected $primaryKey = 'IdTipoUsuario';
}
