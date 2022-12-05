<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $state_id
 * @property string $name
 * @property int $status
 * @property CustomerReq[] $customerReqs
 */
class States extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'state_id';

    /**
     * @var array
     */
    protected $fillable = ['name', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerReqs()
    {
        return $this->hasMany('App\Models\CustomerReq', 'location', 'state_id');
    }
}
