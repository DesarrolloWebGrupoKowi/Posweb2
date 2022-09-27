<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoPago;

class DatTipoPago extends Model
{
    use HasFactory;
    protected $table = 'DatTipoPago';
    protected $fillable = [
                            'IdEncabezado',
                            'IdTipoPago',
                            'Pago',
                            'Restante',
                            'IdBanco'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatTipoPago';

}
