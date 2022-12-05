<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pid
 * @property int $cid
 * @property int $ftype
 * @property string $fpath
 * @property string $fname
 * @property string $date_created
 * @property Customer $customer
 * @property Problem $problem
 */
class ProblemFiles extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['pid', 'cid', 'ftype', 'fpath', 'fname', 'date_created'];

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
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'pid');
    }
}
