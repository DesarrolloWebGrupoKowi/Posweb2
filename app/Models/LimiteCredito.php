<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimiteCredito extends Model
{
    use HasFactory;
    protected $table = 'CatLimiteCredito';
    protected $fillable = [
                            'TipoNomina',
                            'NomTipoNomina',
                            'Limite',
                            'TotalVentaDiaria'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdCatLimiteCredito';
}
