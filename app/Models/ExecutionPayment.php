<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property int $invoice_id
 * @property string $industry
 * @property string $industry_without_gst
 * @property int $tds
 * @property string $reference
 * @property string $industry_file
 * @property int $status
 * @property string $date
 * @property Invoice $invoice
 * @property Problem $problem
 */
class ExecutionPayment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'execution_payment';

    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'invoice_id', 'industry', 'industry_without_gst', 'tds', 'reference', 'industry_file', 'status', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
