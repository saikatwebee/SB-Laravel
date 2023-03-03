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
                    
        $AdminObject = collect(['ss_registered' => AdminService::ss_registered()]);
        $AdminObject->put('ss_plantaken',AdminService::ss_plantaken());
        $AdminObject->put('ss_sales',AdminService::ss_sales());
        return $AdminObject;
    }

    

   
    
}
