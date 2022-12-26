<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $problem_id
 * @property int $action
 * @property string $date_added
 * @property string $offer
 * @property int $shortlist
 * @property int $exe_doc
 * @property string $comment
 * @property Customer $customer
 * @property Problem $problem
 */
class ProblemToProvider extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'problem_to_provider';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'problem_id', 'action', 'date_added', 'offer', 'shortlist', 'exe_doc', 'comment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
