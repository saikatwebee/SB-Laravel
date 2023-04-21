<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cid
 * @property int $industries
 * @property int $category
 * @property int $location
 * @property string $industry_type
 * @property string $requirement
 * @property string $desc
 * @property string $budget
 * @property int $status
 * @property string $land
 * @property string $start_within
 * @property string $date_added
 * @property Customer $customer
 * @property Category $category
 * @property Industry $industry
 * @property State $state
 */
class CustomerReq extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'customer_req';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['cid', 'industries', 'category', 'location', 'industry_type', 'requirement', 'desc', 'budget', 'status', 'land', 'start_within', 'date_added'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'industries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'location', 'state_id');
    }
}
