<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Customer;
use App\Models\CustomerIndustries;
use App\Models\CustomerCategory;
use App\Models\CustomerSkill;
use App\Models\ReferalDatabase;

interface ProfileInterface
{
    public static function editCustomerProfile($data, $customer_id);
    public static function addCustomerIndustry($industries, $customer_id);
    public static function addCustomerIndustry_Sp($industries, $customer_id);
    public static function addCustomerCategory_Sp($cat, $customer_id);
    public static function addCustomerSkill_Sp($skill, $customer_id);
    public static function getCustomerIndustries($customer_id);
    public static function getCustomerCategory($customer_id);
    public static function getCustomerSkill($customer_id);

}

class ProfileService implements ProfileInterface
{
    public static function editCustomerProfile($data, $customer_id)
    {
        $affectedRows = Customer::where('customer_id', $customer_id)->update(
            $data
        );
        if ($affectedRows > 0) {
            return true;
        }
    }

    public static function addCustomerIndustry($industries, $customer_id)
    {
        if (CustomerIndustries::where('customer_id', $customer_id)->exists()) {
            //update
            $affectedRows = CustomerIndustries::where(
                'customer_id',
                $customer_id
            )->update(['industries_id' => $industries]);
            if ($affectedRows > 0) {
                return true;
            }
        } else {
            // insert new record

            $CustomerIndustries = new CustomerIndustries();
            $CustomerIndustries->customer_id = $customer_id;
            $CustomerIndustries->industries_id = $industries;
            $CustomerIndustries->save();
            if ($CustomerIndustries->save()) {
                return true;
            }
        }
    }

    public static function addCustomerIndustry_Sp($industries, $customer_id)
    {
        if (
            CustomerIndustries::where([
                'customer_id' => $customer_id,
                'industries_id' => $industries,
            ])->exists()
        ) {
            //update
            CustomerIndustries::where([
                'customer_id' => $customer_id,
                'industries_id' => $industries,
            ])->update(['industries_id' => $industries]);
        } else {
            $CustomerIndustries = new CustomerIndustries();
            $CustomerIndustries->customer_id = $customer_id;
            $CustomerIndustries->industries_id = $industries;
            $CustomerIndustries->save();
            if ($CustomerIndustries->save()) {
                return true;
            }
        }
    }

    public static function addCustomerCategory_Sp($cat, $customer_id)
    {
        if (
            CustomerCategory::where([
                'customer_id' => $customer_id,
                'category_id' => $cat,
            ])->exists()
        ) {
            //update
            CustomerCategory::where([
                'customer_id' => $customer_id,
                'category_id' => $cat,
            ])->update(['category_id' => $cat]);
        } else {
            $CustomerCategory = new CustomerCategory();
            $CustomerCategory->customer_id = $customer_id;
            $CustomerCategory->category_id = $cat;
            $CustomerCategory->save();
            if ($CustomerCategory->save()) {
                return true;
            }
        }
    }

    public static function addCustomerSkill_Sp($skill, $customer_id)
    {
        if (
            CustomerSkill::where([
                'customer_id' => $customer_id,
                'skill_id' => $skill,
            ])->exists()
        ) {
            //update
            CustomerSkill::where([
                'customer_id' => $customer_id,
                'skill_id' => $skill,
            ])->update(['skill_id' => $skill]);
        } else {
            $CustomerSkill = new CustomerSkill();
            $CustomerSkill->customer_id = $customer_id;
            $CustomerSkill->skill_id = $skill;
            $CustomerSkill->save();
            if ($CustomerSkill->save()) {
                return true;
            }
        }
    }

    public static function AddRefferal($data){
        $affectedRows = ReferalDatabase::insert($data);
        if($affectedRows > 0)
        return true;
    }

    
}

?>
