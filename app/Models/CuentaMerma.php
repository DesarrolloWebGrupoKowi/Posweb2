<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaMerma extends Model
{
    use HasFactory;
    protected $table = 'CatCuentasMerma';
    protected $fillable = ['IdTipoMerma', 'Libro', 'Cuenta', 
                            'SubCuenta', 'InterCosto', 'Futuro', 'Status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdCatCuentaMerma';
}
