<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XXKWCreditLine extends Model
{
    protected $table = 'XXKW_CREDIT_LINES';

    protected $connection = 'Cloud_Interface';

    public $timestamps = false;

    protected $fillable = [
        'Source_Transaction_Identifier',
        'Source_Transaction_Line_Id',
        'Source_Transaction_Line_Number',
        'Source_Transaction_Schedule_Id',
        'Source_Transaction_Schedule_Number',
        'Product_Number',
        'Ordered_Quantity',
        'Ordered_UOM',
        'TransactionCategoryCode',
        'TransactionLineTypeCode',
        'OriginalSourceOrderNumber',
        'OriginalSourceLineNumber',
        'OriginalFulfillLineId',
        'AdjustmentType',
        'AdjustmentAmount',
        'Reason',
        'SourceManualPriceAdjustmentId',
        'ChargeDefinitionCode',
        'BillingTransactionTypeId',
        'Almacen',
        'FulfillmentSplitReferenceId',
    ];

    public function header()
    {
        return $this->belongsTo(XXKWCreditHeader::class, 'Source_Transaction_Identifier', 'Source_Transaction_Identifier');
    }
}
