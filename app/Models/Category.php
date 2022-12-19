<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $ind_id
 * @property string $name
 * @property string $p_id
 * @property int $status
 * @property Industry $industry
 * @property CustomerCategory[] $customerCategories
 * @property CustomerReq[] $customerReqs
 * @property Problem[] $problems
 */
class Category extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'category';
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['ind_id', 'name', 'p_id', 'status'];

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
    public function customerCategories()
    {
        return $this->hasMany('App\Models\CustomerCategory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerReqs()
    {
        return $this->hasMany('App\Models\CustomerReq', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problems()
    {
        return $this->hasMany('App\Models\Problem', 'sub_cat');
    }
}
