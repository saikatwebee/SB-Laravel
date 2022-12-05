<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cus_id
 * @property int $activity
 * @property int $type
 * @property int $call_outcome
 * @property string $comments
 * @property string $date
 * @property DatabaseComplete $databaseComplete
 * @property User $user
 */
class CrmActivity extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'crm_activity';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'cus_id', 'activity', 'type', 'call_outcome', 'comments', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function databaseComplete()
    {
        return $this->belongsTo('App\Models\DatabaseComplete', 'cus_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }
}
