<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $p_id
 * @property int $status
 * @property Category[] $categories
 * @property CustomerIndustry[] $customerIndustries
 * @property CustomerReq[] $customerReqs
 * @property Problem[] $problems
 * @property Skill[] $skills
 */
class Industries extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'p_id', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'ind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerIndustries()
    {
        return $this->hasMany('App\Models\CustomerIndustry', 'industries_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerReqs()
    {
        return $this->hasMany('App\Models\CustomerReq', 'industries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problems()
    {
        return $this->hasMany('App\Models\Problem', 'industries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany('App\Models\Skill', 'ind_id');
    }
}
