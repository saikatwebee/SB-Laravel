<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $user_type
 * @property int $parent_id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $languages
 * @property string $sales_target
 * @property string $coupon
 * @property string $phone
 * @property string $ip
 * @property boolean $status
 * @property string $date_added
 * @property string $email_user
 * @property CrmActivity[] $crmActivities
 * @property DatabaseComplete[] $databaseCompletes
 * @property DatabaseComplete[] $databaseCompletes
 * @property DatabaseComplete[] $databaseCompletes
 * @property ExecutionProposal[] $executionProposals
 * @property PotentialCustomer[] $potentialCustomers
 * @property ProblemConsultantAdding[] $problemConsultantAddings
 * @property ProblemNote[] $problemNotes
 * @property UserLogin[] $userLogins
 */
class User extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user';

    public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * @var array
     */
    protected $fillable = ['user_type', 'parent_id', 'firstname', 'lastname', 'email', 'languages', 'sales_target', 'coupon', 'phone', 'ip', 'status', 'date_added', 'email_user'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function crmActivities()
    {
        return $this->hasMany('App\Models\CrmActivity', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function previously_assigned()
    {
        return $this->hasMany('App\Models\DatabaseComplete', 'previously_assigned', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assigned_to()
    {
        return $this->hasMany('App\Models\DatabaseComplete', 'assigned_to', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function created_by()
    {
        return $this->hasMany('App\Models\DatabaseComplete', 'created_by', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionProposals()
    {
        return $this->hasMany('App\Models\ExecutionProposal', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function potentialCustomers()
    {
        return $this->hasMany('App\Models\PotentialCustomer', 'created_by', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemConsultantAddings()
    {
        return $this->hasMany('App\Models\ProblemConsultantAdding', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemNotes()
    {
        return $this->hasMany('App\Models\ProblemNote', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLogins()
    {
        return $this->hasMany('App\Models\UserLogin', 'user_id', 'user_id');
    }
}
