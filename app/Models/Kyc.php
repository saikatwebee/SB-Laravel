<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $industry
 * @property string $skills
 * @property string $others
 * @property string $location_preference
 * @property string $state
 * @property string $city
 * @property string $language
 * @property string $bio
 * @property string $freelance
 * @property string $latest_project
 * @property string $cv
 * @property string $linkedin_url
 * @property string $certification
 * @property string $feedback
 * @property string $date_updated
 * @property Customer $customer
 */
class Kyc extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'kyc';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'industry', 'skills', 'others', 'location_preference', 'state', 'city', 'language', 'bio', 'freelance', 'latest_project', 'cv', 'linkedin_url', 'certification', 'feedback', 'date_updated'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }
}
