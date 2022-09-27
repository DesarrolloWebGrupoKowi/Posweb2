<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClienteEmail;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'CatClientes';
    protected $fillable = ['IdClienteClod',
                        'TipoPersona',
                        'RFC',
                        'NomCliente',
                        'Ship_To',
                        'Bill_To',
                        'Codigo_Envio',
                        'Locacion',
                        'Calle',
                        'NumExt',
                        'NumInt',
                        'Colonia',
                        'Ciudad',
                        'Municipio',
                        'Estado',
                        'Pais',
                        'CodigoPostal',
                        'Email',
                        'Telefono',
                        'Location_Status'
                    ];
    public $timestamps = false;
    protected $primaryKey = 'IdCatCliente';

    public function CorreoCliente(){
        return $this->hasMany(ClienteEmail::class, 'IdClienteCloud', 'IdClienteCloud');
    }
}
