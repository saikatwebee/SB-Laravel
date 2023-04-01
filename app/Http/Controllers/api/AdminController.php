<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Invoice;
use App\Services\CommonService;
use App\Services\AdminService;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


class AdminController extends Controller
{
    
    public function dashboard_section1(){
        //SS data overall            
        $AdminObject = collect(['ss_registered' => AdminService::registered_bytype('SS')]);
        $AdminObject->put('ss_plantaken',AdminService::plantaken_bytype('SS'));
        $AdminObject->put('ss_sales',AdminService::sales_bytype('SS'));

        //SP data overall
        $AdminObject->put('sp_registered',AdminService::registered_bytype('SP'));
        $AdminObject->put('sp_plantaken',AdminService::plantaken_bytype('SP'));
        $AdminObject->put('sp_sales',AdminService::sales_bytype('SP'));

        //SS+SP Combined data
        $AdminObject->put('all_registered',AdminService::all_registered());
        $AdminObject->put('all_plantaken',AdminService::all_plantaken());
        $AdminObject->put('all_sales',AdminService::all_sales());

        //Projects Data
        $AdminObject->put('all_projects',AdminService::all_projects());
        $AdminObject->put('all_execution',AdminService::all_execution());
        $AdminObject->put('all_project_sales',AdminService::all_project_sales());

        //Today Data
        $today = Carbon::today()->format('Y-m-d');
        $AdminObject->put('today_registered',AdminService::registered($today));
        $AdminObject->put('today_plantaken',AdminService::plantaken($today));
        $AdminObject->put('today_sales',AdminService::sales($today));
        $AdminObject->put('today_project_sales',AdminService::project_sales($today));

        //Yesterday Data
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $AdminObject->put('yesterday_registered',AdminService::registered($yesterday));
        $AdminObject->put('yesterday_plantaken',AdminService::plantaken($yesterday));
        $AdminObject->put('yesterday_sales',AdminService::sales($yesterday));
        $AdminObject->put('yesterday_project_sales',AdminService::project_sales($yesterday));

        //This Month Data
        $thismonth = Carbon::today()->format('Y-m');
        $AdminObject->put('thismonth_registered',AdminService::registered($thismonth));
        $AdminObject->put('thismonth_plantaken',AdminService::plantaken($thismonth));
        $AdminObject->put('thismonth_sales',AdminService::sales($thismonth));
        $AdminObject->put('thismonth_project_sales',AdminService::project_sales($thismonth));

        //Last Month Data
        $lastmonth = Carbon::today()->subMonth()->format('Y-m');
        $AdminObject->put('lastmonth_registered',AdminService::registered($lastmonth));
        $AdminObject->put('lastmonth_plantaken',AdminService::plantaken($lastmonth));
        $AdminObject->put('lastmonth_sales',AdminService::sales($lastmonth));
        $AdminObject->put('lastmonth_project_sales',AdminService::project_sales($lastmonth));

        return $AdminObject;
    }

    public function sales_dashboard(){
        $AdminObject = collect(['sales_count' => AdminService::sales_count()]);

        //This month sales
        $thismonth = Carbon::today()->format('Y-m');
        $AdminObject->put('thismonth_sales',AdminService::sales_by_person($thismonth));

        //Last month sales
        $lastmonth = Carbon::today()->subMonth()->format('Y-m');
        $AdminObject->put('lastmonth_sales',AdminService::sales_by_person($lastmonth));

        //2 months back sales
        $last_2_month = Carbon::today()->subMonths(2)->format('Y-m');
        $AdminObject->put('last_2_month_sales',AdminService::sales_by_person($last_2_month));

        return $AdminObject;

    }

    

   
    
}
