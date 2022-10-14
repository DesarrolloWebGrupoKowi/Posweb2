<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnHandTiendaCloudTable extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Tables';
    protected $table = "XXKW_ONHAND_TIENDAS";
}
