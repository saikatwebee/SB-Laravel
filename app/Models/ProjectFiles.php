<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $file_name
 * @property string $date
 * @property Problem $problem
 */
class ProjectFiles extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['pid', 'file_name', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'pid');
    }
}
