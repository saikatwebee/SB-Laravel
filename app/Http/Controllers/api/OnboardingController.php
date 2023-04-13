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
use App\Services\AuthService;
use App\Services\OnboardingService;
use App\Services\ProfileService;
use App\Mail\BugReport;


class OnboardingController extends Controller
{

public function sentOtp(){
    try{
        $email = auth()->user()->email;
        $customer_id = CommonService::getCidByEmail($email);
        $phone = CommonService::getCphByEmail($email);
        $auth_id = auth()->user()->id;
        $ph="+91".$phone;
        $otp = mt_rand(1111,9999);
        
        //Account Activation
        $data=['status'=>1];
        $res = ProfileService::editCustomerProfile($data, $customer_id);

        //check Account Activation
        $check = ProfileService::CheckActivation($email);
        if($check){
             //sending otp sms
                        //otp sms

						$ch = curl_init();

						curl_setopt($ch, CURLOPT_URL, 'https://api.kaleyra.io/v1/HXIN1700258037IN/messages');
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, "to=".$ph."&type=OTP&sender=SOLBUG&body= ".$otp." is your OTP for login to SolutionBuggy Portal. Please do not share it with anyone.&template_id=1007163645755201460");

						$headers = array();
						$headers[] = 'Content-Type: application/x-www-form-urlencoded';
						$headers[] = 'Api-Key: Aaa25fd00f22308bba995277ea7baea2b';
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

						$result = curl_exec($ch);
						$error = curl_error($ch);

						curl_close($ch);

                        //sending email otp
                        //$email_data['otp'] = $otp;
                        //Mail::to($email)->send(new otpSent($email_data));

                         //wati sms for otp

                         $body = [
                            "parameters" => [
                                  [
                                     "name" => "otp", 
                                     "value" => $otp
                                  ],
                                  
                               ], 
                            "template_name" => 'registration_otp', 
                            "broadcast_name" => "sb-otp" 
                         ]; 
                          
                        
                        $msg = json_encode($body);
                        
                        $ch2 = curl_init("https://live-server-6804.wati.io/api/v1/sendTemplateMessage?whatsappNumber=".$ph);
                            
                        $authorization = "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2NDczODQzNy0zMDVjLTQ5NDctOGI1MC0zMzllMWRhNjIxNGIiLCJ1bmlxdWVfbmFtZSI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwibmFtZWlkIjoiYWRtaW5Ac29sdXRpb25idWdneS5jb20iLCJlbWFpbCI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwiYXV0aF90aW1lIjoiMDEvMTcvMjAyMiAxMDoyMTo1OCIsImRiX25hbWUiOiI2ODA0IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Y_KsRhEnu_NKsxOf0U5HfHRILpnENXShJsgjjTbL5Ss"; // Prepare the authorisation token
                            
                            curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                            curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
                            curl_setopt($ch2, CURLOPT_POSTFIELDS, $msg);
                            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                            $result2 = curl_exec($ch2);
                            curl_close($ch2);

                        //update otp

                        $otpData = ['otp'=>$otp];
                        $res = AuthService::auth_update($otpData,$auth_id);
                        return response()->json($res);

        }
        else{
             //Account not activated 
             return response()->json(['message' => 'Account not activated. Please check your registered email inbox and activate your account. If you face any difficulty contact - 080-42171111'],210);

        }
       
    }
    catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 404);
    }
}

    public function otpVerification(Request $request){
        try{
            $email = auth()->user()->email;
            $customer_id = CommonService::getCidByEmail($email);
            $otp = trim($request->input('otp'));

            $rules = ["otp"=>"required|numeric",];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['info' => $validator->errors()->toJson(),'message' => 'Oops! Invalid data request.','status'=>'220'], Response::HTTP_OK);
            }
            else{
               $res =  OnboardingService::otp_verification($email,$otp);
                if($res){
                    //step update as 2
                    $stepData['step'] = 2;
                    OnboardingService::updateStep($stepData,$customer_id);
                    return response()->json(['message'=>'OTP verified'],Response::HTTP_OK);
                }
                else
                    return response()->json(['message'=>'Invalid OTP!'],210);
                
            }

        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function req_sub(Request $request){
        try{

            $email = auth()->user()->email;
            $cid = CommonService::getCidByEmail($email);

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



public function getOnboardingstep(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);
    $step=ProfileService::getStep($customer_id);
    return response()->json($step);
}

public function OnboardingPreference(Request $request){
    try{
        $customer_id = CommonService::getCidByEmail(
            auth()->user()->email
        );
        $data['freelance'] = trim($request->input('freelance'));
        $data['location'] = trim($request->input('location'));
        $st = implode(',', $request->input('state'));
        $data['state'] = trim($st);
        $data['duration'] = trim($request->input('duration'));
        $org = implode(',', $request->input('organizations'));
        $data['organizations'] = trim($org);
        $data['cid']=$customer_id;
        //$data['relocate_location'] = trim($request->input('relocate_location'));
        $data['relocate'] = trim($request->input('relocate'));
        $data['startProject'] = trim($request->input('startProject'));

        $rules = [
            'freelance' => 'required',
            'location' => 'required',
            'state' => 'required',
            'duration' => 'required',
            'organizations' => 'required',
            'relocate' => 'required',
            'startProject' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        } else {
            //validation successfull
           $res = ProfileService::insertOnboardingPreference($data);
           if($res){
            //update step as 3
            $stepData['step']=3;
            OnboardingService::updateStep($stepData,$customer_id);

           }
           return response()->json($res);
            
        }
    }
    catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 404);
    }
}

public function updateOnboardingExperience(Request $request){
    try{
        $data['mycurrentposition'] = trim($request->input('employmentStatus'));
        $data['country']= trim($request->input('countries'));
        $data['linkedin'] = trim($request->input('linkedIn'));
        $data['phone'] = trim($request->input('phone'));
        $data['city'] = trim($request->input('city'));
        $data['languages'] = $request->input('languages');
        $data['date_updated'] = date('Y-m-d H:i:s');
        $data['step']=4;

        $ind_str = $request->input('industries');
        $ind_arr = explode (",", $ind_str); 

        $skill_str = $request->input('skills');
        $skill_arr = explode (",", $skill_str); 

        $rules = [
            'employmentStatus' => 'required',
            'countries' => 'required',
            'linkedIn' => 'required',
            'phone' => 'required',
            'city' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        } else {
            //validation successfull

            $customer_id = CommonService::getCidByEmail(
                auth()->user()->email
            );

            if(CustomerIndustries::where('customer_id',$customer_id)->exists()){
                //delete 
                CustomerIndustries::where('customer_id',$customer_id)->delete();
            }
            if(CustomerSkill::where('customer_id',$customer_id)->exists()){
                //delete
                CustomerSkill::where('customer_id',$customer_id)->delete();
             }
            
            //insert code of customer industries
                for($i=0; $i<count($ind_arr); $i++){
                    $ind['industries_id']=$ind_arr[$i];
                    $ind['customer_id']=$customer_id;
                    CustomerIndustries::insert($ind);
                }
            //insert code of customer skills
                for($j=0; $j<count($skill_arr); $j++){
                    $sk['skill_id']=$skill_arr[$j];
                     $sk['customer_id'] = $customer_id;         
                    CustomerSkill::insert($sk);
                }

            //update customer
                $res=ProfileService::editCustomerProfile($data,$customer_id);
            

            return response()->json($res);
        }
    }
    catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 404);
    }
}



}
?>
