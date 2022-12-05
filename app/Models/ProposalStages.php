<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $industry
 * @property string $consultant
 * @property string $date
 * @property Problem $problem
 */
class ProposalStages extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['pid', 'industry', 'consultant', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'pid');
    }
}
