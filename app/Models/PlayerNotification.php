<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $player_id
 * @property string $device_name
 * @property string $date_added
 * @property Customer $customer
 */
class PlayerNotification extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'player_notification';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'player_id', 'device_name', 'date_added'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }
}
