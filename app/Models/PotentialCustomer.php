<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $company_id
 * @property int $created_by
 * @property string $user_name
 * @property int $nextweek_no
 * @property string $comments
 * @property string $created
 * @property DatabaseComplete $databaseComplete
 * @property User $user
 */
class PotentialCustomer extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'potential_customer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['company_id', 'created_by', 'user_name', 'nextweek_no', 'comments', 'created'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function databaseComplete()
    {
        return $this->belongsTo('App\Models\DatabaseComplete', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'user_id');
    }
}
