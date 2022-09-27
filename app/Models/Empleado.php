<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LimiteCredito;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\MovimientoMonederoElectronico;

class Empleado extends Model
{
    use HasFactory;
    protected $table = 'CatEmpleados';
    protected $fillable = [
                            'TipoNomina',
                            'NumNomina',
                            'Nombre',
                            'Apellidos',
                            'Fecha_Ingreso',
                            'Empresa',
                            'Status'
                        ];
    public $timestamps = false;

    public function LimiteCredito(){
        return $this->hasOne(LimiteCredito::class, 'TipoNomina', 'TipoNomina');
    }

    public function Adeudos(){
        return $this->hasMany(CreditoEmpleado::class, 'NumNomina', 'NumNomina');
    }

    public function Ventas(){
        return $this->hasMany(CorteTienda::class, 'NumNomina', 'NumNomina');
    }

    public function Monedero(){
        return $this->hasMany(MovimientoMonederoElectronico::class, 'NumNomina', 'NumNomina');
    }
}
