<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\User;
use App\Models\Auth;
use App\Models\CustomerReq;
use Illuminate\Support\Facades\DB;

interface OnboardingInterface
{
    public static function update_req($data,$cid);
    public static function add_req($data);
    public static function otp_verification($email,$otp);
    public static function updateStep($data,$customer_id);
}

class OnboardingService implements OnboardingInterface
{

    public static function update_req($data,$cid){
        $affectedRows = CustomerReq::where(['id'=>$cid])->update($data);
        if($affectedRows > 0)
        return true;
    }

    public static function add_req($data){
        $affectedRows = CustomerReq::insert($data);
        if($affectedRows > 0)
        return true;
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




}
?>