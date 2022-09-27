<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailCustomerCloud;

class CustomerCloud extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Tables';
    protected $table = 'XXKW_CUSTOMERS';

    public function Correo(){
        return $this->hasMany(EmailCustomerCloud::class, 'IDCLIENTE', 'ID_CLIENTE');
    }
}
