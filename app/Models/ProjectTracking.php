<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $counting
 * @property string $date
 */
class ProjectTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'project_tracking';

    /**
     * @var array
     */
    protected $fillable = ['counting', 'date'];
}
