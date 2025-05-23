<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;
    protected $table = 'CatBancos';
    protected $fillable = ['NomBanco', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdBanco';
}
