<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;


class CustomerController extends Controller
{
    // public function __construct() {
    //    $this->middleware('auth:api',['except' => ['check_ch']]);
    // }

    public function getCustomerDemo(){
       //$res= DB::table('customer')->get();
        //var_dump($res);
      //Customer::where("email","hzra2482@gmail.com")->get();
   //  $res= Customer::select("*")->get();
   //$res= Customer::with('problems')->get();
   //  return response()->json($res);

        $res= Customer::select("*")->get();
        return response()->json($res);
    }
}
