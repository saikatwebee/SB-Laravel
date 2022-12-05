<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $problem_id
 * @property int $send_customer_id
 * @property int $receive_customer_id
 * @property string $message
 * @property int $status
 * @property string $date_added
 * @property string $files
 * @property Customer $customer
 * @property Problem $problem
 * @property Customer $customer
 */
class Message extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'message';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['problem_id', 'send_customer_id', 'receive_customer_id', 'message', 'status', 'date_added', 'files'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receive_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'receive_customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function send_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'send_customer_id', 'customer_id');
    }
}
