<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $comments
 * @property string $bot_name
 * @property string $creted
 * @property Customer $customer
 */
class BotTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'bot_tracking';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'name', 'phone', 'email', 'comments', 'bot_name', 'creted'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }
}
