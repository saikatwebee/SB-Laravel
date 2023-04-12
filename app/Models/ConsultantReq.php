<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cid
 * @property string $freelance
 * @property string $location
 * @property string $state
 * @property string $duration
 * @property string $organizations
 * @property string $relocate
 * @property string $startProject
 * @property Customer $customer
 */
class ConsultantReq extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'consultant_req';

    /**
     * @var array
     */
    protected $fillable = ['cid', 'freelance', 'location', 'state', 'duration', 'organizations', 'relocate', 'startProject'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'cid', 'customer_id');
    }
}
