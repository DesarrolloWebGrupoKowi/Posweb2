<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimiteCreditoEspecial extends Model
{
    use HasFactory;
    protected $table = 'CatLimiteCreditoEspecial';
    protected $fillable = [
        'NumNomina',
        'Limite',
        'TotalVentaDiaria'
    ];

    public $timestamps = false;
    protected $primaryKey = 'IdCatLimiteCreditoEspecial';
}
