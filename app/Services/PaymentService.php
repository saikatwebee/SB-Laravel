<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Invoice;
use App\Models\Subscriberplane;
use App\Models\Customer;
use App\Models\CustomerPlane;
use Illuminate\Support\Facades\DB;

interface PaymentInterface
{
     public static function getProblemPosting($id);
     public static function getProduct($id);
     public static function getApply($id);
     public static function getExeApply($id);
     public static function getPlanCost($id);
     public static function getTax($id);
     public static function getTxnId($customer_id,$txn_id);
     public static function updateCustomerPlane($data,$customer_id);

}

class PaymentService implements PaymentInterface
{
    public static function getProblemPosting($id){
       $data =  DB::table('subscriberplane')->select('problem_posting')
            ->where('id',$id)
            ->get()
            ->first();

            return $data->problem_posting;
    }

    public static function getProduct($id){
        $data =  DB::table('subscriberplane')->select('product_display_details')
             ->where('id',$id)
             ->get()
             ->first();
 
             return $data->pproduct_display_details;
     }

     public static function getApply($id){
        $data =  DB::table('subscriberplane')->select('apply')
             ->where('id',$id)
             ->get()
             ->first();
 
             return $data->apply;
     }

     public static function getExeApply($id){
        $data =  DB::table('subscriberplane')->select('exe_apply')
             ->where('id',$id)
             ->get()
             ->first();
 
             return $data->exe_apply;
     }

     public static function getPlanCost($id){
        $data =  DB::table('subscriberplane')->select('plancost')
             ->where('id',$id)
             ->get()
             ->first();
 
             return $data->plancost;
     }

     public static function getTax($id){
        $data =  DB::table('subscriberplane')->select('tax')
             ->where('id',$id)
             ->get()
             ->first();
 
             return $data->tax;
     }

     public static function getTxnId($customer_id,$txn_id){
        $data =  DB::table('invoice')->select('*')
             ->where(['customer_id'=>$customer_id,'txn_id'=>$txn_id])
             ->get();

             return $data;
     }

     public static function updateCustomerPlane($option,$customer_id){
        if (CustomerPlane::where(['customer_id' => $customer_id,])->exists()) {
            //update
            $results = DB::select(DB::raw("update customer_plane set subscriberplane_id = ".$option['subscriberplane_id']." , exp_plane = '".$option['exp_plane']."' ,date_updated = '".$option['date_updated']."' , problem = (problem + ".$option['problem']."), apply = (apply + ".$option['apply'].") WHERE customer_id = '".$customer_id."' "));
        }
        else{
            //insert
            $affectedRows=CustomerPlane::insert($data);
            if($affectedRows > 0)
            return true;
        }
     }


   
}
?>