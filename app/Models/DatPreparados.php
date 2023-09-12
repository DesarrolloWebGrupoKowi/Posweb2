<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPreparados extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'DatPreparados';
    public $timestamps = false;
    protected $primaryKey = 'IdDatPreparado';
}
