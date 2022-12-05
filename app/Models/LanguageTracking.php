<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $Hindi
 * @property int $Kannada
 * @property int $Tamil
 * @property int $Telugu
 * @property int $Malayalam
 * @property string $created
 */
class LanguageTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'language_tracking';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['Hindi', 'Kannada', 'Tamil', 'Telugu', 'Malayalam', 'created'];
}
