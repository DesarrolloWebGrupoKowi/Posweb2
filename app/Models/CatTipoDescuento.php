<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoDescuento extends Model
{
    use HasFactory;
    protected $table = 'CatTipoDescuento';
    protected $fillable = [
        'NomTipoDescuento',
        'Status',
        'Prioridad_aplicacion'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdTipoDescuento';
}
