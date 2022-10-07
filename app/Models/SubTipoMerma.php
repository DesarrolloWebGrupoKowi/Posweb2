<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTipoMerma extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'CatSubTiposMerma';
    protected $fillable = ['IdTipoMerma', 'NomSubTipoMerma', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdSubTipoMerma';
}
