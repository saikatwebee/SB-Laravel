<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $mac
 * @property string $name
 * @property int $user_id
 * @property string $date_added
 * @property User $user
 */
class Mac extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mac';

    /**
     * @var array
     */
    protected $fillable = ['id', 'mac', 'name', 'user_id', 'date_added'];

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}


