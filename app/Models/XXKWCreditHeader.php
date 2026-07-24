<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XXKWCreditHeader extends Model
{
    protected $table = 'XXKW_CREDIT_HEADERS';

    protected $connection = 'Cloud_Interface';

    public $timestamps = false; // Si no tiene created_at/updated_at

    protected $fillable = [
        'Source_Transaction_Identifier',
        'Source_Transaction_System',
        'Source_Transaction_Number',
        'Buying_Party_Identifier',
        'Buying_Party_Number',
        'Buying_Party_Name',
        'Transactional_Currency_Code',
        'Transaction_On',
        'Requesting_Business_Id',
        'Requesting_Business_Unit',
        'RequestedFulfillmentOrganizationId',
        'RequestedFulfillmentOrganizationCode',
        'RequestedFulfillmentOrganizationName',
        'Batch_Name',
        'Submit_Flag',
        'Freeze_Pricing_Flag',
        'Freeze_Shipping_Charge_Flag',
        'Freeze_Tax_Flag',
        'FORMA_PAGO',
        'METODO_PAGO',
        'UCFDI',
        'STATUS',
        'ORDER_TYPE',
        'Party_Site_Identifier',
        'Customer_Account_Id',
        'Site_Use_Id',
        'MENSAJE_ERROR',
        'ReceiptNumber',
        'ReceiptHeaderId',
        'OrdenVenta',
        'Created_at',
        'Created_by',
    ];

    public function lineas()
    {
        return $this->hasMany(XXKWCreditLine::class, 'Source_Transaction_Identifier', 'Source_Transaction_Identifier');
    }
}
