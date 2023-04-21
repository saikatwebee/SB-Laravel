<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\User;
use App\Models\Auth;
use App\Models\CustomerReq;
use App\Models\DatabaseComplete;
use App\Models\CrmActivity;
use Illuminate\Support\Facades\DB;

interface OnboardingInterface
{
    public static function update_req($data,$cid);
    public static function add_req($data);
    public static function addLog($data);
    public static function otp_verification($email,$otp);
    public static function updateStep($data,$customer_id);
    public static function getReq($cid);
    public static function getLatestLead();
    public static function companyDetails($email);
    public static function updateCompany($data,$email);
    public static function getIndustriesName($indId);
    public static function getStatesName($stateId);
    public static function addCompany($data);
    
}

class OnboardingService implements OnboardingInterface
{

    public static function update_req($data,$cid){
        //  DB::enableQueryLog();
        $affectedRows = CustomerReq::where(['cid'=>$cid])->update($data);
        // return $query = DB::getQueryLog();
        if($affectedRows > 0)
        return true;
    }

    public static function add_req($data){
        $affectedRows = CustomerReq::insert($data);
        if($affectedRows > 0)
        return true;
    }

    public static function addLog($data){
        $affectedRows = CrmActivity::insert($data);
        if($affectedRows > 0)
        return true;
    }

    public static function addCompany($data){
        $id = DB::table('database_complete')->insertGetId($data);
        return $id;
    }


    
    public static function otp_verification($email,$otp){
      // $data = Auth::where(['email'=>$email,'otp'=>$otp])->first();
      $data =  DB::table('auth')
                ->where(['email'=>$email,'otp'=>$otp])
                ->get()
                ->first();

           
        return $data;
    }

    public static function updateStep($data,$customer_id){
        $affectedRows = Customer::where(['customer_id'=>$customer_id])->update($data);
        if($affectedRows > 0)
        return true;
    }

    public static function getReq($cid){
        $data =  DB::table('customer_req')
        ->where('cid',$cid)
        ->get()
        ->first();
        
        return $data;
    }

    public static function getLatestLead(){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-1 day'));
        $data = DB::table('customer')
                        ->select('*')
                       ->whereBetween('date_added', [$from, $to])
                       ->get();

        return $data;
    }

    public static function companyDetails($email){
        $data = DB::table('database_complete')
        ->select('*')
       ->where('email',$email)
       ->get()
       ->first();
        
       return $data;
    }

    public static function updateCompany($data,$email){
        $affectedRows = DatabaseComplete::where(['email'=>$email])->update($data);
        if($affectedRows > 0)
        return true;
    }

    public static function getIndustriesName($indId){
        $data = DB::table('industries')
        ->select('*')
       ->where('id',$indId)
       ->get()
       ->first();
        
       return $data->name;
    }


    public static function getStatesName($stateId){
        $data = DB::table('states')
        ->select('*')
       ->where('state_id',$stateId)
       ->get()
       ->first();
        
       return $data->name;

    }



}
?>