<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatRosticeroArticulos extends Model
{
    use HasFactory;
    protected $table = 'CatRosticeroArticulos';
    protected $fillable = [
        'CodigoMatPrima',
        'CodigoVenta',
        'PorcentajeMerma',
        'Status'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdCatRosticeroArticulos';
}
