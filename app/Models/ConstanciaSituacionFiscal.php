<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstanciaSituacionFiscal extends Model
{
    use HasFactory;
    protected $table = 'ConstanciaSituacionFiscal';
    protected $fillable = ['Constancia1','NomConstancia','Constancia2','Constancia3', 'Constancia4', 'Constancia5', 'Constancia6', 'Constancia7', 'Constancia8', 'Constancia9', 'Constancia10'];
    public $timestamps = false;
    protected $primaryKey = 'IdSolicitudFactura';
}
