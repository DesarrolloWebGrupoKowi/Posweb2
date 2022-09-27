<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'CatGrupos';
    protected $fillable = ['NomGrupo', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdGrupo';
}
