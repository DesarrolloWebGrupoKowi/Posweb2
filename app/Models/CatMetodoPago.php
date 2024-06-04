<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatMetodoPago extends Model
{
    use HasFactory;
    protected $table = 'CatMetodoPago';
    protected $fillable = [
                            'MetPago',
                            'Descripcion',
                            'status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'Id';
}
