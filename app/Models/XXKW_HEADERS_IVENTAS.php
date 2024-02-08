<?php

namespace App\Models;

use App\Models\Articulo;
use App\Models\ClienteCloud;
use App\Models\CorteTienda;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class XXKW_HEADERS_IVENTAS extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Interface';
    protected $table = 'XXKW_HEADERS_IVENTAS';
}
