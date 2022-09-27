<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;
    protected $table = 'CatCiudades';
    protected $fillable = ['NomCiudad','IdEstado','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdCiudad';

    public static function ciudades($idEstado){
        return Ciudad::where('IdEstado','=',$idEstado)
                     ->get();
    }
}
