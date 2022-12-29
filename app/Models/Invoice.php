<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $plan_id
 * @property int $project_id
 * @property string $date
 * @property string $txn_id
 * @property string $description
 * @property string $comments
 * @property string $export
 * @property int $export_amount
 * @property string $source
 * @property int $plancost
 * @property int $totalcost
 * @property string $tax
 * @property string $company
 * @property string $address
 * @property string $gst
 * @property int $state
 * @property Customer $customer
 * @property Problem $problem
 * @property Subscriberplane $subscriberplane
 * @property ExecutionPayment[] $executionPayments
 */
class Invoice extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'invoice';
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'plan_id', 'project_id', 'date', 'txn_id', 'description', 'comments', 'export', 'export_amount', 'source', 'plancost', 'totalcost', 'tax', 'company', 'address', 'gst', 'state'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriberplane()
    {
        return $this->belongsTo('App\Models\Subscriberplane', 'plan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionPayments()
    {
        return $this->hasMany('App\Models\ExecutionPayment');
    }
}
