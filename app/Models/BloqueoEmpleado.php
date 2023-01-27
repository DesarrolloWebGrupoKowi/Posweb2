<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;
use App\Models\Usuario;

class BloqueoEmpleado extends Model
{
    use HasFactory;
    protected $table = 'DatBloqueoEmpleado';
    protected $fillable = [
        'NumNomina',
        'FechaBloqueo',
        'MotivoBloqueo',
        'IdUsuario',
        'FechaDesbloqueo',
        'Status'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdBloqueo';

    public function Empleado(){
        return $this->hasOne(Empleado::class, 'NumNomina', 'NumNomina');
    }

    public function Usuario(){
        return $this->hasOne(Usuario::class, 'IdUsuario', 'IdUsuario');
    }
}
