<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $call_ph
 * @property string $whatsapp
 * @property string $management
 * @property string $others
 * @property string $created
 */
class OfflineTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'offline_tracking';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['email', 'call_ph', 'whatsapp', 'management', 'others', 'created'];
}
