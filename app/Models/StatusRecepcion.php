<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusRecepcion extends Model
{
    use HasFactory;
    protected $table = 'CatStatusRecepcion';
    protected $fillable = ['NomStatusRecepcion', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdStatusRecepcion';
}
