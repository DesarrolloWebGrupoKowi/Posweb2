<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $table = 'CatEstados';
    protected $fillable = ['NomEstado','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdEstado';
}
