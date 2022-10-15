<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCloudInterface extends Model
{
    use HasFactory;
    protected $connection = 'Cloud_Interface';
    protected $table = 'XXKW_TRANSACTION_INVENTORY';
}
