<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    protected $table = 'CatCajas';
    protected $fillable = ['IdCaja', 'NumCaja', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdCaja';
}
