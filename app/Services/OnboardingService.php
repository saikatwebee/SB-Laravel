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

}
?>