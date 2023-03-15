<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoCliente;

class FrecuenteSocio extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'CatFrecuentesSocios';

    public function TipoCliente(){
        return $this->hasOne(TipoCliente::class, 'IdTipoCliente', 'IdTipoCliente');
    }
}
