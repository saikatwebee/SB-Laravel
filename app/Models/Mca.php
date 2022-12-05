<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cin
 * @property string $company
 * @property string $date_reg
 * @property string $state
 * @property int $activity_code
 * @property string $activity
 * @property string $address
 * @property string $email
 */
class Mca extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mca';

    /**
     * @var array
     */
    protected $fillable = ['cin', 'company', 'date_reg', 'state', 'activity_code', 'activity', 'address', 'email'];
}
