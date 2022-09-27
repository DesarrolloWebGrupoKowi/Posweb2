<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalPos extends Model
{
    use HasFactory;
    protected $table = 'TemporalPos';
    protected $fillable = ['NumNomina','IdEncabezado', 'MonederoDescuento'];
    public $timestamps = false;
}
