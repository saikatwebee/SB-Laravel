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
use Illuminate\Support\Facades\DB;

interface ReferalInterface
{

    public static function referal_insert($data1);


}

class ReferalService implements ReferalInterface
{

    public static function referal_insert($data1){
        $affectedRows = ReferalDatabase::insert($data1);
        if($affectedRows > 0)
        return true;
    }

}
?>