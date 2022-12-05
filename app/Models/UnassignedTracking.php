<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $unassigned
 * @property int $old_employee
 * @property string $created
 */
class UnassignedTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'unassigned_tracking';

    /**
     * @var array
     */
    protected $fillable = ['unassigned', 'old_employee', 'created'];
}
