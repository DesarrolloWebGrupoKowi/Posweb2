<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatRegimenFiscal extends Model
{
    use HasFactory;

    protected $table = 'CatRegimenFiscal';

    protected $fillable = [
        'RegimenFiscal',
        'NomRegimenFiscal',
        'Status'
    ];

    public $timestamps = false;

    protected $primaryKey = 'IdCatRegimenFiscal';
}
