<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $cid
 * @property string $query
 * @property string $channel
 * @property string $campaign
 * @property string $sourcemedium
 * @property string $keyword
 * @property string $destinationurl
 * @property string $device
 * @property string $state
 * @property string $city
 * @property string $landingpage
 * @property string $date_added
 * @property Customer $customer
 */
class Ga extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ga';

    /**
     * @var array
     */
    protected $fillable = ['cid', 'query', 'channel', 'campaign', 'sourcemedium', 'keyword', 'destinationurl', 'device', 'state', 'city', 'landingpage', 'date_added'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'cid', 'customer_id');
    }
}
