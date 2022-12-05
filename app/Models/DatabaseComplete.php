<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $created_by
 * @property int $customer_id
 * @property int $assigned_to
 * @property int $previously_assigned
 * @property string $company
 * @property string $name
 * @property string $designation
 * @property string $address1
 * @property string $city
 * @property string $mobile
 * @property string $email
 * @property string $turnover
 * @property string $last_contacted
 * @property string $assigned_date
 * @property string $lead_type
 * @property string $priority
 * @property string $sales_priority
 * @property string $skills
 * @property string $create_date
 * @property string $grade
 * @property string $list_name
 * @property string $keyword
 * @property int $auto_lead
 * @property Customer $customer
 * @property User $user
 * @property User $user
 * @property User $user
 * @property CrmActivity[] $crmActivities
 * @property PotentialCustomer[] $potentialCustomers
 */
class DatabaseComplete extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'database_complete';

    /**
     * @var array
     */
    protected $fillable = ['created_by', 'customer_id', 'assigned_to', 'previously_assigned', 'company', 'name', 'designation', 'address1', 'city', 'mobile', 'email', 'turnover', 'last_contacted', 'assigned_date', 'lead_type', 'priority', 'sales_priority', 'skills', 'create_date', 'grade', 'list_name', 'keyword', 'auto_lead'];

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
    public function get_created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_assigned_to()
    {
        return $this->belongsTo('App\Models\User', 'assigned_to', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_previously_assigned()
    {
        return $this->belongsTo('App\Models\User', 'previously_assigned', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function crmActivities()
    {
        return $this->hasMany('App\Models\CrmActivity', 'cus_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function potentialCustomers()
    {
        return $this->hasMany('App\Models\PotentialCustomer', 'company_id');
    }
}
