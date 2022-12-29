<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $subscriberplane_id
 * @property string $exp_plane
 * @property int $problem
 * @property int $apply
 * @property string $date_updated
 * @property Customer $customer
 * @property Subscriberplane $subscriberplane
 */
class CustomerPlane extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'customer_plane';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'subscriberplane_id', 'exp_plane', 'problem', 'apply', 'date_updated'];

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
    public function subscriberplane()
    {
        return $this->belongsTo('App\Models\Subscriberplane');
    }
}
