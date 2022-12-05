<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $Register_high
 * @property string $Register_medium
 * @property string $Google_ads
 * @property string $Indiamart
 * @property string $created
 */
class LeadTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'lead_tracking';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['Register_high', 'Register_medium', 'Google_ads', 'Indiamart', 'created'];
}
