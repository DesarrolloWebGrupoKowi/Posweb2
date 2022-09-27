<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsoCFDI extends Model
{
    use HasFactory;
    protected $table = 'CatUsoCFDI';
    protected $fillable = [
                            'UsoCFDI',
                            'NomCFDI',
                            'Status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdCatUsoCFDI';
}
