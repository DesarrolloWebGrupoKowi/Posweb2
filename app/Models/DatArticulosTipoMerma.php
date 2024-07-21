<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatArticulosTipoMerma extends Model
{
    use HasFactory;
    protected $table = 'DatArticulosTipoMerma';
    public $timestamps = false;

    public function Detalle()
    {
        return $this->hasMany(CatTiposMerma::class, 'IdTipoMerma', 'IdTipoMerma')
            ->leftjoin('CatSubTiposMerma', 'CatSubTiposMerma.IdTipoMerma', 'CatTiposMerma.IdTipoMerma ');
    }
}
