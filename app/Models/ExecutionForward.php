<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property int $customer_id
 * @property string $date
 * @property Customer $customer
 * @property Problem $problem
 */
class ExecutionForward extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'execution_forward';

    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'customer_id', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
