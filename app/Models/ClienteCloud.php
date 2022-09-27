<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClienteCloudTienda;

class ClienteCloud extends Model
{
    use HasFactory;
    protected $table = 'CatClientesCloud';
    protected $fillable = ['IdClienteCloud',
                        'NomClienteCloud',
                        'TipoCliente',
                        'Ship_To',
                        'Bill_To',
                        'Codigo_Envio',
                        'Direccion',
                        'Locacion',
                        'Pais',
                        'Ciudad',
                        'Codigo_Postal'];
    public $timestamps = false;
    //protected $primaryKey = 'IdClienteCloud';
}
