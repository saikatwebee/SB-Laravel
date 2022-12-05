<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $industries
 * @property int $sub_cat
 * @property int $skills
 * @property int $assigned_to
 * @property int $assigned_to_1
 * @property string $title
 * @property string $describe
 * @property int $time_limit
 * @property string $files
 * @property int $status
 * @property string $industries_other
 * @property string $category_other
 * @property int $typeofproject
 * @property string $budget
 * @property string $location
 * @property string $state
 * @property int $action
 * @property string $date_added
 * @property string $awarded_date
 * @property string $dropped_date
 * @property string $live_date
 * @property string $assigned_date
 * @property int $execution
 * @property string $slug
 * @property int $potential
 * @property int $ceo_potential
 * @property int $auto_project
 * @property Category $category
 * @property Skill $skill
 * @property User $user
 * @property Customer $customer
 * @property Industry $industry
 * @property User $user
 * @property ActivityLog[] $activityLogs
 * @property ExecutionForward[] $executionForwards
 * @property ExecutionPayment[] $executionPayments
 * @property ExecutionProposal[] $executionProposals
 * @property Invoice[] $invoices
 * @property Message[] $messages
 * @property PaymentRequest[] $paymentRequests
 * @property ProblemConsultantAdding[] $problemConsultantAddings
 * @property ProblemFile[] $problemFiles
 * @property ProblemNote[] $problemNotes
 * @property ProblemReferral[] $problemReferrals
 * @property ProblemToProvider[] $problemToProviders
 * @property ProjectFile[] $projectFiles
 * @property ProjectQuestion[] $projectQuestions
 * @property ProjectTag[] $projectTags
 * @property Proposal[] $proposals
 * @property ProposalStage[] $proposalStages
 */
class Problem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'problem';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'industries', 'sub_cat', 'skills', 'assigned_to', 'assigned_to_1', 'title', 'describe', 'time_limit', 'files', 'status', 'industries_other', 'category_other', 'typeofproject', 'budget', 'location', 'state', 'action', 'date_added', 'awarded_date', 'dropped_date', 'live_date', 'assigned_date', 'execution', 'slug', 'potential', 'ceo_potential', 'auto_project'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'sub_cat','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo('App\Models\Skill', 'skills','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_assigned_to_1()
    {
        return $this->belongsTo('App\Models\User', 'assigned_to_1', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'industries','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_assigned_to()
    {
        return $this->belongsTo('App\Models\User', 'assigned_to', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLogs()
    {
        return $this->hasMany('App\Models\ActivityLog','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionForwards()
    {
        return $this->hasMany('App\Models\ExecutionForward','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionPayments()
    {
        return $this->hasMany('App\Models\ExecutionPayment','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function executionProposals()
    {
        return $this->hasMany('App\Models\ExecutionProposal','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'project_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentRequests()
    {
        return $this->hasMany('App\Models\PaymentRequest','pid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemConsultantAddings()
    {
        return $this->hasMany('App\Models\ProblemConsultantAdding', 'project_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemFiles()
    {
        return $this->hasMany('App\Models\ProblemFile','pid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemNotes()
    {
        return $this->hasMany('App\Models\ProblemNote','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemReferrals()
    {
        return $this->hasMany('App\Models\ProblemReferral','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemToProviders()
    {
        return $this->hasMany('App\Models\ProblemToProvider','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectFiles()
    {
        return $this->hasMany('App\Models\ProjectFile', 'pid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectQuestions()
    {
        return $this->hasMany('App\Models\ProjectQuestion', 'project_no','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectTags()
    {
        return $this->hasMany('App\Models\ProjectTag','problem_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany('App\Models\Proposal', 'pid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposalStages()
    {
        return $this->hasMany('App\Models\ProposalStage', 'pid','id');
    }
}
