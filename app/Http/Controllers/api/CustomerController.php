<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;
use App\Services\ProfileService;


class CustomerController extends Controller
{
   
  
    public function editNew(Request $request){
        try{
            // $firstname = trim($request->input('firstname'));
            // $lastname = trim($request->input('firstname'));
            // $email = trim($request->input('email'));
            // $phone = trim($request->input('phone'));
            // $companyname = trim($request->input('companyname'));
            // $establishment = trim($request->input('establishment'));
            // $brief_bio = trim($request->input('brief_bio'));
            // $city = trim($request->input('city'));
            // $status = trim($request->input('status'));
            // $date_updated = date('Y-m-d H:i:s')


            $rules = [
                "firstname" => "required|min:3",
                "lastname" => "required|min:3",
                // "email" => "required|email|unique:customer",
                "phone"=>"required|numeric|min:10",
                "industries"=>"required|numeric",
                "companyname"=>"required",
                "establishment"=>"required",
                "brief_bio"=>"required",
                "city"=>"required",
                "state"=>"required",
                
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(),400);
            }
            else{
                //validation successfull
                $customer_id = ProfileService::getCidByEmail(auth()->user()->email);
                $res = ProfileService::editNewProfile($request->all(),$customer_id);
                if($res > 0){
                    ProfileService::addCustomerIndustry($request->input('industries'),$customer_id);
                    return response()->json(['success' => true,'message' => 'Update Successfull','status'=>'200'], Response::HTTP_OK);
                }
            }
            
        }
        catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()],404);
        }
    }
}
