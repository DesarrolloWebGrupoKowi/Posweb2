<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;

class CreditoEmpleado extends Model
{
    use HasFactory;
    protected $table = 'DatCreditos';
    protected $fillable = [
                            'IdTienda',
                            'IdEncabezado',
                            'FechaVenta',
                            'NumNomina',
                            'ImporteCredito',
                            'StatusCredito'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatCreditos';

    public function Empleado(){
        return $this->hasOne(Empleado::class, 'NumNomina', 'NumNomina');
    }
}
