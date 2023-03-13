<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\CustomerReq;
use App\Services\CommonService;
use App\Services\OnboardingService;
use App\Services\ProfileService;
use App\Mail\BugReport;


class OnboardingController extends Controller
{
    public function req_sub(Request $request){
        try{
            $cid=trim($request->input('cid'));
            if(CustomerReq::where('cid',$cid)->exists()){
                //when cid found update data
                $data['industry_type']=trim($request->input('industry_type'));
                $data['industries']=trim($request->input('industries'));
           
                 if($request->input('other_cat')!=""){
                     $data['category']=trim($request->input('other_cat'));
                }
                else{
					
                    if($data['industries']=='9' || $data['industries']=='18'){
                        $data['category']=trim($request->input('sub_cat'));
                    }
                    else{
                        $data['category']='';
                    }
                }
                if($request->input('other_req')!=""){
                    $data['requirement']=trim($request->input('other_req'));
                }
                else{
                    $data['requirement']=trim($request->input('req'));
                }

                $data['desc']=trim($request->input('desc'));
                $data['budget']=trim($request->input('budget'));
                $data['location']=trim($request->input('location'));

                if($request->input('land')){
                    $data['land']=trim($request->input('land'));
                }
                else{
                    $data['land']='';
                }

                $data['start_within']=trim($request->input('start_within'));

                $res=OnboardingService::update_req($data, $cid);
                if($res){
                    return response()->json(
                    [
                    'success' => true,
                    'message' => 'Update Successfull',
                    'status' => '200',
                    ],
                    Response::HTTP_OK);
                }
            }
        else{
            //when cid not found then insert data
            $data['cid']=$cid;
            $data['industry_type']=trim($request->input('industry_type'));
            $data['industries']=trim($request->input('industries'));
           
            if($request->input('other_cat')!=""){
                $data['category']=trim($request->input('other_cat'));
            }
            else{
				if($data['industries']=='9' || $data['industries']=='18'){
                $data['category']=trim($request->input('sub_cat'));
                }
                else{
                $data['category']='';
                }
            }
            if($request->input('other_req')!=""){
                $data['requirement']=trim($request->input('other_req'));
            }
            else{
                $data['requirement']=trim($request->input('req'));
            }

            $data['desc']=trim($request->input('desc'));
            $data['budget']=trim($request->input('budget'));
            $data['location']=trim($request->input('location'));

            if($request->input('land')){
                $data['land']=trim($request->input('land'));
            }
            else{
                $data['land']='';
            }

            $data['start_within']=trim($request->input('start_within'));
            // var_dump($data);
            // die;
            $res=OnboardingService::add_req($data);
                if($res){
                    return response()->json(
                    [
                    'success' => true,
                    'message' => 'Update Successfull',
                    'status' => '200',
                    ],
                    Response::HTTP_OK);
                }

        }
    }
    catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 404);
    }
}
}
?>
