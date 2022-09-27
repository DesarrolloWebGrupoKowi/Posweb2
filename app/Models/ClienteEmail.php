<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEmail extends Model
{
    use HasFactory;
    protected $table = 'CatClienteEmail';
    public $timestamps = false;
}
