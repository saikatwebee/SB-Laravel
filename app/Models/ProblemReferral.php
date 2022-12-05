<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $problem_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $date
 * @property Customer $customer
 * @property Problem $problem
 */
class ProblemReferral extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'problem_referral';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'problem_id', 'name', 'phone', 'email', 'date'];

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
