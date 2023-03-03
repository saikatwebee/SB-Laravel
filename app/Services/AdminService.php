<?php

namespace App\Services;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

interface AdminInterface
{
   public static function ss_registered();
   public static function ss_plantaken();
   public static function ss_sales();
  
  }

class AdminService implements AdminInterface{

    public static function ss_registered(){
        $count = Customer::where('customer_type','SS')->count();
        return $count;
        
    }

    public static function ss_plantaken(){
        $count = DB::table('customer_plane')
        ->leftJoin('customer', 'customer.customer_id', '=', 'customer_plane.customer_id')
        ->select(
            'customer_plane.*'
        )
        ->where('customer.customer_type','SS')
        ->count();
        return $count;
    }

    public static function ss_sales(){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('customer.customer_type','SS')
        ->toSql();
        var_dump($count);
        exit;
        return $count;
    }

    



  

}

?>