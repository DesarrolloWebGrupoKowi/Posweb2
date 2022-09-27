<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaza extends Model
{
    use HasFactory;
    protected $table = 'CatPlazas';
    protected $fillable = ['NomPlaza','IdCiudad','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdPlaza';
}
