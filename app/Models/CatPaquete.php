<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatPaquete;
use App\Models\Usuario;

class CatPaquete extends Model
{
    use HasFactory;
    protected $table = 'CatPaquetes';
    protected $fillable = ['NomPaquete', 'ImportePaquete', 'FechaCreacion', 'FechaEliminacion', 'IdUsuario', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdPaquete';

    public function ArticulosPaquete(){
        return $this->hasMany(DatPaquete::class, 'IdPaquete', 'IdPaquete');
    }

    public function Usuario(){
        return $this->hasOne(Usuario::class, 'IdUsuario', 'IdUsuario');
    }
}
