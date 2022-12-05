<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $token
 * @property string $created_at
 */
class PasswordResets extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['email', 'token', 'created_at'];
}
