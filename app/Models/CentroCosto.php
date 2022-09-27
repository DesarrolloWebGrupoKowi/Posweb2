<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Tables';
    protected $table = 'XXKW_CENTRO_COSTO';
}
