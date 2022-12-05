<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $industries_id
 * @property string $other
 * @property Customer $customer
 * @property Industry $industry
 */
class CustomerIndustries extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'industries_id', 'other'];

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
    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'industries_id');
    }
}
