<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;



/**
 * @property int $id
 * @property string $user_type
 * @property string $firstname
 * @property string $email
 * @property string $password
 * @property int $Status
 * @property string $created
 */
class Auth extends  Authenticatable implements JWTSubject
{
    use Notifiable,HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'auth';
    

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password',];
    protected $hidden = [
        'password',
        
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
