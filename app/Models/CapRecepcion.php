<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatRecepcion;
use App\Models\StatusRecepcion;

class CapRecepcion extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'CapRecepcion';
    protected $fillable = [
        'FechaRecepcion', 
        'PackingList', 
        'IdTiendaOrigen', 
        'Almacen', 
        'Organization_Id', 
        'MotivoCancelacion', 
        'FechaCancelacion', 
        'IdStatusRecepcion'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdCapRecepcion';

    public function DetalleRecepcion(){
        return $this->hasMany(DatRecepcion::class, 'IdCapRecepcion', 'IdCapRecepcion');
    }

    public function StatusRecepcion(){
        return $this->hasOne(StatusRecepcion::class, 'IdStatusRecepcion', 'IdStatusRecepcion');
    }
}
