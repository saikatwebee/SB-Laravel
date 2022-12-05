<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $ind_id
 * @property string $name
 * @property int $status
 * @property Industry $industry
 * @property CustomerSkill[] $customerSkills
 * @property Problem[] $problems
 */
class Skill extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'skill';

    /**
     * @var array
     */
    protected $fillable = ['ind_id', 'name', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'ind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerSkills()
    {
        return $this->hasMany('App\Models\CustomerSkill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problems()
    {
        return $this->hasMany('App\Models\Problem', 'skills');
    }
}
