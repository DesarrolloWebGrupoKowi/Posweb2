<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatFrecuentesSocios extends Model
{
    use HasFactory;
    protected $table = 'CatFrecuentesSocios';

    public function TipoCliente()
    {
        return $this->hasOne(CatTiposCliente::class, 'IdTipoCliente', 'IdTipoCliente');
    }
}
