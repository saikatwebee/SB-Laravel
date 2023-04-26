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
    public static function getprotitle($problem_id);
    public static function category_browse_sp($sub_cat);
    public static function category_browse_ss($sub_cat,$customer_id);
    public static function industry_browse_sp($industries);
    public static function industry_browse_ss($industries,$customer_id);
    public static function browse_sp($sub_cat,$industries);
    public static function browse_ss($sub_cat,$industries,$customer_id);
    public static function get_provider($pid,$cid);
    public static function get_awarded_count($cid);
}

class ProblemService implements ProblemInterface{
    public static function postProject($data){
        $id = DB::table('problem')->insertGetId($data);
        return $id;
    }

    public static function verifyPlanId($customer_id){
        if(CustomerPlane::where('customer_id',$customer_id)->exists()){
            return true;
          }
    }

    public static function getExpDate($customer_id){
        $row = CustomerPlane::select("exp_plane")->where('customer_id',$customer_id)->first();
       return $row->exp_plane;
    }

    public static function getProblemCount($customer_id){
        $row = CustomerPlane::select("problem")->where('customer_id',$customer_id)->first();
        return $row->problem;
    }

    public static function subProblemCount($customer_id){
        $problem_count = CustomerPlane::select("problem")->where('customer_id',$customer_id)->first();
        if($problem_count->problem > 0){
            $problem = $problem_count->problem - 1;
            $affectedRows = CustomerPlane::where("customer_id",$customer_id)->update(['problem'=>$problem]);
            if($affectedRows > 0)
            return true;
        }
    }

    public static function subApplyCount($customer_id){
        $data = CustomerPlane::select("apply")->where('customer_id',$customer_id)->first();
        $apply_count = $data->apply;
        if($apply_count > 0){
            $apply = $apply_count - 1;
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

    public static function getprotitle($problem_id){
        $data = DB::table('problem')
                    ->select('title')
                    ->where('id', $problem_id)
                    ->get();
                return $data->title;
    }

    public static function category_browse_sp($sub_cat){
        //example of raw query 

        // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"), array(
        //     'somevariable' => $someVariable,
        //   ));

        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));

       // DB::enableQueryLog();
        $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'sub_cat'=>$sub_cat])
                    ->whereBetween('date_added', [$from, $to])
                    //->toSql();
                    ->get();

                    //$query = DB::getQueryLog();

                    return $data;
        
    }

    public static function category_browse_ss($sub_cat,$customer_id){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));

        $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'sub_cat'=>$sub_cat,'customer_id'=>$customer_id])
                    ->whereBetween('date_added', [$from, $to])
                    //->toSql();
                    ->get();
        return $data;
    }


    public static function industry_browse_sp($industries){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));


        $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'industries'=>$industries])
                    ->whereBetween('date_added', [$from, $to])
                   // ->toSql();
                    ->get();

                    return $data;
    }

    public static function industry_browse_ss($industries,$customer_id){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));

            $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'industries'=>$industries,'customer_id'=>$customer_id])
                    ->whereBetween('date_added', [$from, $to])
                   // ->toSql();
                    ->get();
            return $data;
    }


    public static function browse_sp($sub_cat,$industries){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));


        $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'sub_cat'=>$sub_cat,'industries'=>$industries])
                    ->whereBetween('date_added', [$from, $to])
                   // ->toSql();
                    ->get();

                    return $data;
    }

    public static function browse_ss($sub_cat,$industries,$customer_id){
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime('-3 month'));


        $data = DB::table('problem')
                    ->select('*')
                    ->where(['action'=>1,'sub_cat'=>$sub_cat,'industries'=>$industries,'customer_id'=>$customer_id])
                    ->whereBetween('date_added', [$from, $to])
                   // ->toSql();
                    ->get();

                    return $data;
    }

    public static function get_provider($pid,$cid){
        $data = DB::table('problem_to_provider')
        ->select('*')
        ->where(['problem_id'=>$pid,'customer_id'=>$cid])
        ->get()
        ->first();

        return $data;
    }

    public static function get_awarded_count($cid){
        $data = DB::table('problem_to_provider')
        ->select('*')
        ->where(['action'=>2,'customer_id'=>$cid])
        ->get();

       if($data)
        return $data->count();
        else
        return 0;
       
    }
   
}

?>