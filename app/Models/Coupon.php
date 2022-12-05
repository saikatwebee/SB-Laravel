<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $coupon
 * @property int $silver
 * @property int $gold
 * @property int $platinum
 * @property string $date_added
 * @property int $spinwheel
 * @property Customer $customer
 */
class Coupon extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'coupon';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'coupon', 'silver', 'gold', 'platinum', 'date_added', 'spinwheel'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }
}
