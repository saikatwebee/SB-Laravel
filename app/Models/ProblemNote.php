<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property int $user_id
 * @property int $activity
 * @property int $type
 * @property int $call_outcome
 * @property int $stage
 * @property string $end_date
 * @property string $comments
 * @property string $meeting_link
 * @property string $meeting_id
 * @property string $meeting_room
 * @property string $date
 * @property string $stage_desc
 * @property int $stage_duration
 * @property int $gantt_stage
 * @property int $stage_amount
 * @property int $stage_status
 * @property string $completion_date
 * @property User $user
 * @property Problem $problem
 */
class ProblemNote extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'problem_note';

    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'user_id', 'activity', 'type', 'call_outcome', 'stage', 'end_date', 'comments', 'meeting_link', 'meeting_id', 'meeting_room', 'date', 'stage_desc', 'stage_duration', 'gantt_stage', 'stage_amount', 'stage_status', 'completion_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
