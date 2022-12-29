<?php

namespace App\Services;
use App\Models\Auth;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

interface AuthInterface
{
   public static function check_email($email);
    public static function auth_insert($firstname,$email,$password,$user_type,$status);
    public static function customer_insert($firstname,$email,$phone,$user_type,$howsb,$reg_url);
    public static function user_insert($firstname,$email,$phone,$user_type,$status);
    public static function customer_auth($email);
    public static function user_auth($email);
    public static function get_state_list();
    public static function get_industry_list();
    public static function get_category_list();
    public static function get_skill_list();
  
  }

class AuthService implements AuthInterface{

    public static function check_email($email){
        if(Auth::where('email',$email)->exists()){
          return true;
        }
        
    }

    public static function auth_insert($firstname,$email,$password,$role,$status)
    {
        $auth = new Auth;
        $auth->name = $firstname;
        $auth->email = $email;
        $auth->password=bcrypt($password);
        $auth->role = $role;
        $auth->Status = $status;
        $auth->save();
        if($auth->save()){
            $data=["auth_id"=>$auth->id,"role"=>$auth->role];
            return $data;
        }
    }

    public static function customer_insert($firstname,$email,$phone,$customer_type,$howsb,$reg_url){
        $step ='1';
        $ip = getenv("REMOTE_ADDR");
        $customer = new Customer;
        $customer->firstname = $firstname;
        $customer->email = $email;
        $customer->phone = $phone;
        $customer->customer_type = $customer_type;
        $customer->howsb = $howsb;
        $customer->reg_url = $reg_url;
        $customer->ip=$ip;
        $customer->step = $step;
        $customer->date_added =  date('Y-m-d h:i:s');
        if($customer->save())
         return true;
    }
    public static function user_insert($firstname,$email,$phone,$user_type,$status){
        $ip = getenv("REMOTE_ADDR");
        $user = new User;
        $user->firstname = $firstname;
        $user->email = $email;
        $user->phone = $phone;
        $user->user_type = $user_type;
        $user->status = $status;
        $user->ip = $ip;
        $user->date_added =  date('Y-m-d h:i:s');
        $user->parent_id = 1;
        if($user->save())
        return true;
    }
    
    public static function user_auth($email){
        $user = User::where('email',$email)->first();
        return  $user;
        
    }
    public static function customer_auth($email){
        $customer = Customer::where('email',$email)->first();
        return $customer;
        
    }

    public static function get_state_list()
    {
       $states =  DB::table('states')
            ->select('state_id','name')->get();
            return $states;
    }


    public static function get_industry_list()
    {
       $states =  DB::table('industries')
            ->select('id','name')
            ->get();
            return $states;
    }

    public static function get_category_list()
    {
       $states =  DB::table('category')
            ->select('id','name','p_id')
            ->limit(72)
            ->get();
            return $states;
    }

    public static function get_skill_list()
    {
       $states =  DB::table('skill')
            ->select('id','name')
            ->limit(33)
            ->get();
            return $states;
    }


  

}

?>