<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property int $expert_cid
 * @property int $user_id
 * @property string $sb_proposal
 * @property string $expert_proposal
 * @property string $expert_gst
 * @property string $industry_file
 * @property string $date
 * @property Customer $customer
 * @property Problem $problem
 * @property User $user
 */
class ExecutionProposal extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'execution_proposal';

    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'expert_cid', 'user_id', 'sb_proposal', 'expert_proposal', 'expert_gst', 'industry_file', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'expert_cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }
}
