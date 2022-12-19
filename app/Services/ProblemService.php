<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\CustomerPlane;
use App\Models\Problem;

interface ProblemInterface
{
    public static function postProject($data);
    public static function verifyPlanId($customer_id);
    public static function getExpDate($customer_id);
    public static function getProblemCount($customer_id);
    public static function subProblemCount($customer_id);
   
}

class ProblemService implements ProblemInterface{
    public static function postProject($data){
        $affectedRows = Problem::insert($data);
        if($affectedRows > 0)
        return true;
    }

    public static function verifyPlanId($customer_id){
        if(CustomerPlane::where('customer_id',$customer_id)->exists()){
            return true;
          }
    }

    public static function getExpDate($customer_id){
        $row = CustomerPlane::select("exp_plane")->where('customer_id',$customer_id)->first();
        //return $row->exp_plane;
        return $row;
    }

    public static function getProblemCount($customer_id){
        $row = CustomerPlane::select("problem")->where('customer_id',$customer_id)->get();
        return $row->problem;
    }

    public static function subProblemCount($customer_id){
        $problem_count = CustomerPlane::select("problem")->where('customer_id',$customer_id)->get();
        if($problem_count->problem > 0){
            $problem = $problem_count->problem - 1;
            $affectedRows = CustomerPlane::where("customer_id",$customer_id)->update(['problem'=>$problem]);
            if($affectedRows > 0)
            return true;
        }
    }

    
}

?>