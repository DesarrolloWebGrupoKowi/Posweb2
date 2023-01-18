<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrecuenteSocio extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'CatFrecuentesSocios';
}
