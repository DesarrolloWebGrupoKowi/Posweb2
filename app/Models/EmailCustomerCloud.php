<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCustomerCloud extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Tables';
    protected $table = 'XXKW_CUSTOMER_MAIL';
}
