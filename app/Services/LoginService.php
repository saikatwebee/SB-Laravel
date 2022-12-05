<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Auth;
use App\Models\Customer;
use App\Models\User;


interface LoginInterface
{
    public static function login($email, $password);
    

}

class LoginService implements LoginInterface{

    public static function login($email, $password)
    {
        
        return "login";
    }

    
}

?>