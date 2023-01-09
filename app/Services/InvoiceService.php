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

interface InvoiceInterface
{
    public static function updateInvoiceTable($data,$customer_id);
    public static function getInvoice($id);
    public static function AddInvoice($data);
    public static function getPlan($customer_id);
    public static function verifyCId($customer_id);
    public static function getPlandetails($plan_id);
    public static function updatePlandetails($data,$customer_id);

}

class InvoiceService implements InvoiceInterface
{
    public static function updateInvoiceTable($data,$inv_id)
    {
        $affectedRows = Invoice::where('id', $inv_id)->update(
            $data
        );
        if ($affectedRows > 0) {
            return true;
        }
    }

    public static function getInvoice($id){
        $inv = DB::table('invoice')
            ->leftJoin('subscriberplane', 'subscriberplane.id', '=', 'invoice.plan_id')
            ->select(
                'invoice.*',
                'subscriberplane.title'
            )
            ->where('invoice.id',$id)
            ->get();
        return $inv;
        
    }
    public static function AddInvoice($data){
        $affectedRows = Invoice::insert($data);
        if($affectedRows > 0)
        return true;
    }
    public static function getPlan($customer_id){
        $Plan = CustomerPlane::where('customer_id',$customer_id)->first();
        return $Plan;
        
    }
    public static function verifyCId($customer_id){
        if(CustomerPlane::where('customer_id',$customer_id)->exists()){
            return true;
        }
    }
    public static function getPlandetails($plan_id){
        $Plan = Subscriberplane::where('id',$plan_id)->first();
        return $Plan;
        
    }
    public static function updatePlandetails($data,$customer_id)
    {
        $affectedRows = CustomerPlane::where('customer_id', $customer_id)->update($data);
        if ($affectedRows > 0)
        return true;
        
    }
    public static function AddPlandetails($data){
        $affectedRows = CustomerPlane::insert($data);
        if($affectedRows > 0)
        return true;
    }
     
}

?>
