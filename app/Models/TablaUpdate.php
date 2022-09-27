<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaUpdate extends Model
{
    use HasFactory;
    protected $table = 'CatTablasUpdate';
    protected $fillable = ['IdTienda', 'NombreTabla', 'Descargar'];
    public $timestamps = false;
    protected $primaryKey = 'IdCatTablasUpdate';
}
