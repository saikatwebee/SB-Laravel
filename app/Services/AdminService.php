<?php

namespace App\Services;
use App\Models\Customer;
use App\Models\Problem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

interface AdminInterface
{
   public static function registered_bytype($type);
   public static function plantaken_bytype($type);
   public static function sales_bytype($type);
   public static function all_registered();
   public static function all_plantaken();
   public static function all_sales();
   public static function all_projects();
   public static function all_project_sales();
   public static function all_execution();
   public static function registered($date);
   public static function plantaken($date);
   public static function sales($date);
   public static function project_sales($date);
   public static function sales_count();
   public static function sales_by_person($date);
  
  }

class AdminService implements AdminInterface{

    public static function registered_bytype($type){
        $count = Customer::where('customer_type',$type)->count();
        return $count;
        
    }

    public static function plantaken_bytype($type){
        $count = DB::table('customer_plane')
        ->leftJoin('customer', 'customer.customer_id', '=', 'customer_plane.customer_id')
        ->select(
            'customer_plane.*'
        )
        ->where('customer.customer_type',$type)
        ->count();
        return $count;
    }

    public static function sales_bytype($type){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('customer.customer_type',$type)
        ->where('invoice.plan_id','!=',30)
        ->sum('invoice.totalcost');
        return $count/1.18;
    }

    public static function all_registered(){
        $count = Customer::where('customer_id','>',0)->count();
        return $count;
        
    }

    public static function all_plantaken(){
        $count = DB::table('customer_plane')
        ->leftJoin('customer', 'customer.customer_id', '=', 'customer_plane.customer_id')
        ->select(
            'customer_plane.*'
        )
        ->count();
        return $count;
    }

    public static function all_sales(){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('invoice.plan_id','!=',30)
        ->sum('invoice.totalcost');
        return $count/1.18;
    }

    public static function all_projects(){
        $count = Problem::where('id','>',0)->count();
        return $count;
        
    }

    public static function all_execution(){
        $count = Problem::where('execution','=',2)->count();
        return $count;
        
    }
    public static function all_project_sales(){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('invoice.plan_id','=',30)
        ->sum('invoice.totalcost');
        return $count/1.18;
    }

    public static function registered($date){
        $count = Customer::where('date_added','LIKE','%'.$date.'%')->count();
        return $count;
        
    }

    public static function plantaken($date){
        $count = DB::table('customer_plane')
        ->leftJoin('customer', 'customer.customer_id', '=', 'customer_plane.customer_id')
        ->select(
            'customer_plane.*'
        )
        ->where('date_added','LIKE','%'.$date.'%')
        ->count();
        return $count;
    }

    public static function sales($date){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('customer.date_added','LIKE','%'.$date.'%')
        ->where('invoice.plan_id','!=',30)
        ->sum('invoice.totalcost');
        return $count/1.18;
    }

    public static function project_sales($date){
        $count = DB::table('invoice')
        ->leftJoin('customer', 'invoice.customer_id', '=','customer.customer_id' )
        ->where('invoice.plan_id','=',30)
        ->where('customer.date_added','LIKE','%'.$date.'%')
        ->sum('invoice.totalcost');
        return $count/1.18;
    }

    public static function sales_count(){
        $thismonth = Carbon::today()->format('Y-m');
        $query = DB::table('user')
        ->leftJoin('database_complete', 'database_complete.assigned_to', '=','user.user_id' )
        ->leftJoin('customer_plane', 'customer_plane.customer_id', '=','database_complete.customer_id' )
        ->select('user.user_id','user.firstname', DB::raw('count(customer_plane.id) as sales_count'))
        ->where('customer_plane.date_updated','LIKE','%'.$thismonth.'%')
        ->where('user.status','=',1)
        ->groupBy('user.user_id')
        ->get();
        return $query;
    }

    public static function sales_by_person($date){
        $query = DB::table('user')
        ->leftJoin('database_complete', 'database_complete.assigned_to', '=','user.user_id' )
        ->leftJoin('invoice', 'invoice.customer_id', '=','database_complete.customer_id' )
        ->select('user.user_id','user.firstname', DB::raw('sum(invoice.totalcost)/1.18 as sales'))
        ->where('invoice.date','LIKE','%'.$date.'%')
        ->where('invoice.plan_id','!=',30)
        ->where('user.status','=',1)
        ->groupBy('user.user_id')
        ->get();
        return $query;
    }

    



  

}

?>