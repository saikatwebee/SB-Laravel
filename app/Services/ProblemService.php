<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\CustomerPlane;
use App\Models\Proposal;
use App\Models\Problem;
use App\Models\ProblemReferral;
use App\Models\ProblemToProvider;
use Illuminate\Support\Facades\DB;

interface ProblemInterface
{
    public static function postProject($data);
    public static function verifyPlanId($customer_id);
    public static function getExpDate($customer_id);
    public static function getProblemCount($customer_id);
    public static function subProblemCount($customer_id);
    public static function subApplyCount($customer_id);
    public static function updateProblem($data,$problem_id);
    public static function updateProblemToProvider($data,$customer_id,$problem_id);
    public static function checkProblemForwarded($customer_id,$problem_id);
    public static function addProblemToProvider($data);
    public static function addReferProject($data);
    public static function getProject($id);
    public static function getProfile($id);
    public static function getCategoryDependent($ind);
    public static function getSkillDependent($ind);
    public static function proposal_insert($data1);
   
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

    public static function subApplyCount($customer_id){
        $apply_count = CustomerPlane::select("apply")->where('customer_id',$customer_id)->first();
        if($apply_count->apply > 0){
            $apply = $apply_count->apply - 1;
            $affectedRows = CustomerPlane::where("customer_id",$customer_id)->update(['apply'=>$apply]);
            if($affectedRows > 0)
            return true;
        }
    }

    public static function updateProblem($data,$problem_id)
    {
        $affectedRows = Problem::where("id",$problem_id)->update($data);
        if($affectedRows > 0)
        return true;
    }

    public static function updateProblemToProvider($data,$customer_id,$problem_id)
    {
        $affectedRows = ProblemToProvider::where(["customer_id"=>$customer_id,"problem_id"=>$problem_id])->update($data);
        if($affectedRows > 0)
        return true;
    }

    public static function checkProblemForwarded($customer_id,$problem_id){
        if (ProblemToProvider::where(["customer_id"=>$customer_id,"problem_id"=>$problem_id])->exists())
        return true;
    }
    public static function addProblemToProvider($data){
        $affectedRows = ProblemToProvider::insert($data);
        if($affectedRows > 0)
        return true;
    }
    public static function addReferProject($data){
        $affectedRows = ProblemReferral::insert($data);
        if($affectedRows > 0)
        return true;
    }
    public static function getProject($id){
        $inv = DB::table('problem')
            ->leftJoin('industries', 'industries.id', '=', 'problem.industries')
            ->leftJoin('category', 'category.id', '=', 'problem.sub_cat')
            ->select(
                'problem.*',
                'industries.name as industry',
                'category.name as cat'
            )
            ->where('problem.id',$id)
            ->get()

            ->first();
           
        return $inv;
        
    }
    public static function getProfile($id){
        $pro = DB::table('customer')
            ->select(
                'customer.*'
            )
            ->where('customer_id',$id)
            ->get()
            ->first();
        return $pro;
        
    }

    public static function getCategoryDependent($ind){
        $data = DB::table('category')
                ->select('id','name','ind_id')
                ->where(['ind_id'=>$ind,'status'=>1])
                ->orderBy('id', 'asc')
                ->get();

                if($data->count() > 0){
                    return $data;
                }
                else{
                    $default_data = DB::table('category')
                                ->select('id','name')
                                ->where(['status'=>1])
                                ->orderBy('id', 'asc')
                                ->limit(72)
                                ->get();
                        return $default_data;
                }
    }

    public static function getSkillDependent($ind){
        $data = DB::table('skill')
            ->select('id','name')
            ->where(['ind_id'=>$ind,'status'=>1])
            ->orderBy('id', 'asc')
            ->get();

            if($data->count() > 0){
                return $data;
            }
            else{
                $default_data = DB::table('skill')
                            ->select('id','name')
                            ->where(['status'=>1])
                            ->orderBy('id', 'asc')
                            ->limit(33)
                            ->get();
                    return $default_data;
            }


    }
    
    public static function proposal_insert($data1){
        $affected_rows=Proposal::insert($data1);
        if($affected_rows > 0)
            return true;
    }
   
}

?>