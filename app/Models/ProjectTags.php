<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property string $problem_tag
 * @property Problem $problem
 */
class ProjectTags extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'problem_tag'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
