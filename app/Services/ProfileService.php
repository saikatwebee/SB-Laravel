<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\CustomerIndustries;



interface ProfileInterface
{
    public static function editNewProfile($data,$customer_id);
    public static function getCidByEmail($email);
    public static function addCustomerIndustry($industries,$customer_id);
    

}

class ProfileService implements ProfileInterface{

    public static function editNewProfile($data,$customer_id)
    {
        $affectedRows = Customer::where("customer_id",$customer_id)->update($data);
        return $affectedRows;
    }

    public static function getCidByEmail($email){
        $customer = Customer::where('email',$email)->first();
        return $customer->customer_id;
    }

    public static function addCustomerIndustry($industries,$customer_id){
        if(CustomerIndustries::where('customer_id',$customer_id)->exists()){
            //update
            $affectedRows = CustomerIndustries::where("customer_id",$customer_id)->update(['industries_id'=>$industries]);
            if($affectedRows > 0)
            return true;
        }
        else{
           // insert new record

           $CustomerIndustries = new CustomerIndustries;
           $CustomerIndustries->customer_id = $customer_id;
           $CustomerIndustries->industries_id = $industries;
           $CustomerIndustries->save();
           if($CustomerIndustries->save())
            return true;
        }
    }
    
}

?>