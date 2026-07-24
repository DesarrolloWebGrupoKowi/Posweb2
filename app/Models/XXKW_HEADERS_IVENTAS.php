<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XXKW_HEADERS_IVENTAS extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Interface';
    protected $table = 'XXKW_HEADERS_IVENTAS';

    // Relación con las líneas
    public function lineas()
    {
        return $this->hasMany(
            XXKW_LINEAS_IVENTAS::class,
            'Source_Transaction_Identifier',
            'Source_Transaction_Number'
        );
    }
}
