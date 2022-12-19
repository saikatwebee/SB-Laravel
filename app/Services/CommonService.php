<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\User;

interface CommonInterface
{
    //for customers (including SS & SP)
    public static function getCidByEmail($email);
    public static function getRowByCid($customer_id);

    //for our Employes 

    public static function getUidByEmail($email);
    public static function getRowByUid($customer_id);

}
class CommonService implements CommonInterface{

    public static function getCidByEmail($email){
        $customer = Customer::where('email',$email)->first();
        return $customer->customer_id;
    }

    public static function getRowByCid($customer_id){
        $customer = Customer::where('customer_id',$customer_id)->first();
        return $customer;
    }

    public static function getUidByEmail($email){
        $user = User::where('email',$email)->first();
        return $user->user_id;
    }

    public static function getRowByUid($user_id){
        $user = User::where('user_id',$user_id)->first();
        return $user;
    }
}
 
?>