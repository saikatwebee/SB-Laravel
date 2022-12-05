<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $din
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $mobile1
 * @property string $email1
 */
class Din extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'din';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'din';

    /**
     * @var array
     */
    protected $fillable = ['name', 'mobile', 'email', 'mobile1', 'email1'];
}
