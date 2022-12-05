<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $comments
 * @property string $source
 * @property string $date_added
 * @property User $user
 * @property Problem $problem
 */
class ProblemConsultantAdding extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'problem_consultant_adding';

    /**
     * @var array
     */
    protected $fillable = ['project_id', 'user_id', 'name', 'email', 'phone', 'comments', 'source', 'date_added'];

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
        return $this->belongsTo('App\Models\Problem', 'project_id');
    }
}
