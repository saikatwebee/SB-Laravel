<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cid
 * @property int $pid
 * @property string $amount
 * @property string $amount_paid
 * @property int $is_gst
 * @property string $gst
 * @property string $doc_type
 * @property string $reference
 * @property string $payment_doc
 * @property string $account_nm
 * @property string $account_no
 * @property string $ifsc
 * @property int $status
 * @property string $tds
 * @property string $gst_amount
 * @property string $comment
 * @property string $transaction_no
 * @property string $date_added
 * @property Customer $customer
 * @property Problem $problem
 */
class PaymentRequest extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'payment_request';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['cid', 'pid', 'amount', 'amount_paid', 'is_gst', 'gst', 'doc_type', 'reference', 'payment_doc', 'account_nm', 'account_no', 'ifsc', 'status', 'tds', 'gst_amount', 'comment', 'transaction_no', 'date_added'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'pid');
    }
}
