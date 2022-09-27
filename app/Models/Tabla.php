<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabla extends Model
{
    use HasFactory;
    protected $table = 'CatTablas';
    protected $fillable = ['NomTabla', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdCatTablas';
}
