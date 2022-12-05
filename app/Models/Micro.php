<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cid
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $topic
 * @property string $selected_date
 * @property string $slot
 * @property int $payment_status
 * @property string $date
 * @property Customer $customer
 */
class Micro extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'micro';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['cid', 'name', 'email', 'phone', 'topic', 'selected_date', 'slot', 'payment_status', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'cid', 'customer_id');
    }
}
