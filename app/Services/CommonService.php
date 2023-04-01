<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\User;
use App\Models\Auth;
use Illuminate\Support\Facades\DB;

interface CommonInterface
{
    //for customers (including SS & SP)
    public static function getCidByEmail($email);
    public static function getCphByEmail($email);
    public static function getRoleByEmail($email);
    public static function getRowByCid($customer_id);
    public static function getAuthIdByEmail($email);

    //for our Employes 

    public static function getUidByEmail($email);
    public static function getRowByUid($customer_id);


    public static function getIndById($industries);
    public static function getCatById($sub_cat);
    public static function getSkillById($sub_cat);
}

class CommonService implements CommonInterface{

    public static function getCidByEmail($email){
        $customer = Customer::where('email',$email)->first();
        return $customer->customer_id;
    }

    public static function getCphByEmail($email){
        $customer = Customer::where('email',$email)->first();
        return $customer->phone;
    }

    public static function getRoleByEmail($email){
        $auth = Auth::where('email',$email)->first();
        return $auth->role;
    }

    public static function getRowByCid($customer_id){
        $customer = Customer::where('customer_id',$customer_id)->first();
        return $customer;
    }

    public static function getAuthIdByEmail($email){
        $auth = Auth::where('email',$email)->first();
        return $auth->id;
    }

    public static function getUidByEmail($email){
        $user = User::where('email',$email)->first();
        return $user->user_id;
    }

    public static function getRowByUid($user_id){
        $user = User::where('user_id',$user_id)->first();
        return $user;
    }

    public static function getIndById($industries){
        $data = DB::table('industries')
                    ->select('name')
                    ->where('id',$industries)
                    ->get()
                    ->first();
                    return $data->name;
    }

    public static function getCatById($sub_cat){
        $data = DB::table('category')
        ->select('name')
        ->where('id',$sub_cat)
        ->get()
        ->first();
        return $data->name;
    }

    public static function getSkillById($skill){

        $data = DB::table('skill')
        ->select('name')
        ->where('id',$skill)
        ->get()
        ->first();
        return $data->name;
    }

}
 
?>