<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @property int $id
 * @property int $cid
 * @property int $pid
 * @property int $ammount
 * @property int $is_gst
 * @property string $gst
 * @property string $pan
 * @property string $proposal_doc
 * @property string $created
 * @property Customer $customer
 * @property Problem $problem
 */
class Proposal extends Model
{
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'proposal';

    /**
     * @var array
     */
    protected $fillable = ['cid', 'pid', 'ammount', 'is_gst', 'gst', 'pan', 'proposal_doc', 'created'];

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
