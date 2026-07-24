<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XXKW_LINEAS_IVENTAS extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Interface';
    protected $table = 'XXKW_LINEAS_IVENTAS';

    // Relación inversa con el header
    public function header()
    {
        return $this->belongsTo(
            XXKW_HEADERS_IVENTAS::class,
            'Source_Transaction_Identifier',
            'Source_Transaction_Number'
        );
    }
}
