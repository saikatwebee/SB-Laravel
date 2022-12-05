<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $referer_id
 * @property int $customer_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $plan_amount
 * @property Customer $customer
 * @property Customer $customer
 */
class ReferalDatabase extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'referal_database';

    /**
     * @var array
     */
    protected $fillable = ['referer_id', 'customer_id', 'name', 'email', 'phone', 'plan_amount'];

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
    public function refer_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'referer_id', 'customer_id');
    }
}
