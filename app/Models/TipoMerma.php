<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMerma extends Model
{
    use HasFactory;
    protected $table = 'CatTiposMerma';
    protected $fillable = ['NomTipoMerma', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdTipoMerma';

    public function Mermas(){
        return $this->hasMany(CapMerma::class, 'IdTipoMerma', 'IdTipoMerma');
    }
}
