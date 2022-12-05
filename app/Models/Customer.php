<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $customer_id
 * @property string $customer_type
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $address
 * @property string $city
 * @property string $state
 * @property int $country
 * @property string $phone
 * @property int $mytotalnoexp
 * @property string $myqualificaton
 * @property string $mylastposition
 * @property string $mycurrentposition
 * @property string $dob
 * @property string $companyname
 * @property string $companylogo
 * @property string $establishment
 * @property string $ip
 * @property string $status
 * @property string $date_added
 * @property string $date_updated
 * @property string $last_login
 * @property string $resume
 * @property string $brief_bio
 * @property string $archievements
 * @property string $certification
 * @property string $certification_file
 * @property string $linkedin
 * @property string $tinno
 * @property string $howsb
 * @property string $lead_mapping
 * @property string $mc_hour
 * @property string $mc_location
 * @property string $mc_cost
 * @property int $mc_status
 * @property int $whatsapp
 * @property string $step
 * @property int $pre_qualified
 * @property string $reg_url
 * @property int $unsubscribe
 * @property ActivityLog[] $activityLogs
 * @property AnalyticsTracking[] $analyticsTrackings
 * @property Coupon[] $coupons
 * @property CustomerAvoidProject[] $customerAvoidProjects
 * @property CustomerCategory[] $customerCategories
 * @property CustomerIndustry[] $customerIndustries
 * @property CustomerPlane[] $customerPlanes
 * @property CustomerReq[] $customerReqs
 * @property CustomerSkill[] $customerSkills
 * @property DatabaseComplete[] $databaseCompletes
 * @property ExecutionForward[] $executionForwards
 * @property ExecutionProposal[] $executionProposals
 * @property Ga $ga
 * @property Invoice[] $invoices
 * @property Kyc[] $kycs
 * @property Message[] $messages
 * @property Message[] $messages
 * @property Micro[] $micros
 * @property PaymentLink[] $paymentLinks
 * @property PaymentRequest[] $paymentRequests
 * @property PlayerNotification[] $playerNotifications
 * @property Problem[] $problems
 * @property ProblemFile[] $problemFiles
 * @property ProblemReferral[] $problemReferrals
 * @property ProblemToProvider[] $problemToProviders
 * @property Proposal[] $proposals
 * @property ReferalDatabase[] $referalDatabases
 * @property ReferalDatabase[] $referalDatabases
 * @property Webinar[] $webinars
 */
class Customer extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'customer';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_type', 'firstname', 'lastname', 'email', 'address', 'city', 'state', 'country', 'phone', 'mytotalnoexp', 'myqualificaton', 'mylastposition', 'mycurrentposition', 'dob', 'companyname', 'companylogo', 'establishment', 'ip', 'status', 'date_added', 'date_updated', 'last_login', 'resume', 'brief_bio', 'archievements', 'certification', 'certification_file', 'linkedin', 'tinno', 'howsb', 'lead_mapping', 'mc_hour', 'mc_location', 'mc_cost', 'mc_status', 'whatsapp', 'step', 'pre_qualified', 'reg_url', 'unsubscribe'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLogs()
    {
        return $this->hasMany('App\Models\ActivityLog', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function analyticsTrackings()
    {
        return $this->hasMany('App\Models\AnalyticsTracking', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerAvoidProjects()
    {
        return $this->hasMany('App\Models\CustomerAvoidProject', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerCategories()
    {
        return $this->hasMany('App\Models\CustomerCategory', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerIndustries()
    {
        return $this->hasMany('App\Models\CustomerIndustry', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerPlanes()
    {
        return $this->hasMany('App\Models\CustomerPlane', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerReqs()
    {
        return $this->hasMany('App\Models\CustomerReq', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerSkills()
    {
        return $this->hasMany('App\Models\CustomerSkill', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function databaseCompletes()
    {
        return $this->hasMany('App\Models\DatabaseComplete', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionForwards()
    {
        return $this->hasMany('App\Models\ExecutionForward', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionProposals()
    {
        return $this->hasMany('App\Models\ExecutionProposal', 'expert_cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ga()
    {
        return $this->hasOne('App\Models\Ga', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kycs()
    {
        return $this->hasMany('App\Models\Kyc', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function snd_messages()
    {
        return $this->hasMany('App\Models\Message', 'send_customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rcv_messages()
    {
        return $this->hasMany('App\Models\Message', 'receive_customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function micros()
    {
        return $this->hasMany('App\Models\Micro', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentLinks()
    {
        return $this->hasMany('App\Models\PaymentLink', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentRequests()
    {
        return $this->hasMany('App\Models\PaymentRequest', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playerNotifications()
    {
        return $this->hasMany('App\Models\PlayerNotification', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problems()
    {
        return $this->hasMany('App\Models\Problem', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemFiles()
    {
        return $this->hasMany('App\Models\ProblemFile', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemReferrals()
    {
        return $this->hasMany('App\Models\ProblemReferral', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemToProviders()
    {
        return $this->hasMany('App\Models\ProblemToProvider', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany('App\Models\Proposal', 'cid', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referFrom()
    {
        return $this->hasMany('App\Models\ReferalDatabase', 'customer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referTo()
    {
        return $this->hasMany('App\Models\ReferalDatabase', 'referer_id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webinars()
    {
        return $this->hasMany('App\Models\Webinar', 'customer_id', 'customer_id');
    }
}
