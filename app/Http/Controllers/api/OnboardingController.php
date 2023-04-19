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
use App\Models\DatabaseComplete;
use App\Models\CustomerIndustries;
use App\Models\CustomerSkill;
use App\Services\CommonService;
use App\Services\AuthService;
use App\Services\OnboardingService;
use App\Services\ProfileService;
use App\Mail\BugReport;
use App\Mail\OtpSent;
use App\Mail\PrequalificationMail;

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
                         $email_data['otp'] = $otp;
                         $email_data['fullname'] = ProfileService::getFullName($customer_id);
                         Mail::to($email)->send(new OtpSent($email_data));

                         //wati sms for otp

                         $body = [
                            "parameters" => [
                                  [
                                     "name" => "otp", 
                                     "value" => $otp
                                  ],
                                  
                               ], 
                            "template_name" => 'otp_verification', 
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
                $data['requirement']=trim($request->input('req'));
                $data['desc']=trim($request->input('desc'));
                $data['start_within']=trim($request->input('start_within'));


                if($request->input('sub_cat')!=""){
                    $data['category']=trim($request->input('sub_cat'));
                }
                if($request->input('budget')!=""){
                    $data['budget']=trim($request->input('budget'));
                }
                if($request->input('state') !=""){
                    $data['location']=trim($request->input('state'));
                }
                if($request->input('land')!=""){
                    $data['land']=trim($request->input('land'));
                }
                
                $res=OnboardingService::update_req($data, $cid);
            }
        else{
            //when cid not found then insert data
            $data['cid']=$cid;
            $data['industry_type']=trim($request->input('industry_type'));
            $data['industries']=trim($request->input('industries'));
            $data['requirement']=trim($request->input('req'));
            $data['desc']=trim($request->input('desc'));
            $data['start_within']=trim($request->input('start_within'));
            
            if($request->input('sub_cat')!=""){
                $data['category']=trim($request->input('sub_cat'));
            }
            if($request->input('budget')!=""){
                $data['budget']=trim($request->input('budget'));
            }
            if($request->input('state') !=""){
                $data['location']=trim($request->input('state'));
            }
            if($request->input('land')!=""){
                $data['land']=trim($request->input('land'));
            }

            $res=OnboardingService::add_req($data);
        }

        if($res){
            //update as prequlified  and step update as 3
            $cdata['pre_qualified'] = 1;
            $cdata['step']=3;
            ProfileService::editCustomerProfile($cdata, $cid);

            //slack notification for requirement submission

            //slack notification for Get Consultant page visitor

		$slack_cid = $cid;
		$slack_name = ProfileService::getFullName($slack_cid);
		$slack_email= $email;
		$slack_phone = ProfileService::getPhone($slack_cid);
		$req_data= OnboardingService::getReq($slack_cid);
        $brief = $req_data->requirement;

		$option= array (
		  'blocks' => 
		  array (
			0 => 
			array (
			  'type' => 'section',
			  'text' => 
			  array (
				'type' => 'mrkdwn',
				'text' => '*Customer Onboarding - Prequalified Notification: *',
			  ),
			),
			1 => 
			array (
			  'type' => 'divider',
			),
			2 => 
			array (
			  'type' => 'section',
			  'fields' => 
			  array (
				0 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => '*Customer ID:*
'.$slack_cid,
				),
				1 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => '*Name:*
'.$slack_name,
				),
				2 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => '*Email ID:*
'.$slack_email,
				),
				3 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => '*Phone:*
'.$slack_phone,
				),
				4 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => '*Brief Details:*
Required Consultant for '.$brief,
				),
			  ),
			),
			3 => 
			array (
			  'type' => 'context',
			  'elements' => 
			  array (
				0 => 
				array (
				  'type' => 'image',
				  'image_url' => 'https://www.solutionbuggy.com/assets/img/alert.jpg',
				  'alt_text' => 'cute cat',
				),
				1 => 
				array (
				  'type' => 'mrkdwn',
				  'text' => 'Action required...',
				),
			  ),
			),
		  ),
		);
	

	$message = array('payload' => json_encode($option));

	$ch = curl_init("https://hooks.slack.com/services/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");


curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);



        //create contact 
        $msg =  $this->createContact($email,$cid);
          
        return response()->json($msg,Response::HTTP_OK);
    }
    }
    catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 404);
    }
}

public function sentPrequalificationNotification(){
     //prequalification mail to user
        $email = auth()->user()->email;
        $cid = CommonService::getCidByEmail($email);
        $fullname = ProfileService::getFullName($cid);
        $phone = ProfileService::getPhone($cid);

        $req=OnboardingService::getReq($cid);



        $requirement = $req->requirement;
        $ind_id = $req->industries;
        $industry=OnboardingService::getIndustriesName($ind_id);


        $email_data['fullname'] = $fullname;
        Mail::to($email)->send(new PrequalificationMail($email_data));

        //wati sms 

        //thank you sms after registration complete

        $body = [
            'parameters' => [
                [
                    'name' => 'fullname',
                    'value' => $fullname
                ],
                [
                    'name' => 'industry',
                    'value' => $industry
                ],
                [
                    'name' => 'requirement',
                    'value' =>  $requirement 
                ],


            ],
            'template_name' => 'prequalification_done',
            'broadcast_name' => 'sb-prequalification'
        ];

        $msg = json_encode( $body );

        $ch = curl_init( 'https://live-server-6804.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$phone );

        $authorization = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2NDczODQzNy0zMDVjLTQ5NDctOGI1MC0zMzllMWRhNjIxNGIiLCJ1bmlxdWVfbmFtZSI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwibmFtZWlkIjoiYWRtaW5Ac29sdXRpb25idWdneS5jb20iLCJlbWFpbCI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwiYXV0aF90aW1lIjoiMDEvMTcvMjAyMiAxMDoyMTo1OCIsImRiX25hbWUiOiI2ODA0IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Y_KsRhEnu_NKsxOf0U5HfHRILpnENXShJsgjjTbL5Ss';
        // Prepare the authorisation token

        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', $authorization ) );
        // Inject the token into the header
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $msg );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        $result = curl_exec($ch);
        curl_close($ch);

}

public function createContact($email,$cid){

            $pre_data=OnboardingService::getReq($cid);
            
            if(DatabaseComplete::where('email',$email)->exists()){
                //existing lead

                $companyDetails = OnboardingService::companyDetails($email);
                if($companyDetails->customer_id == null || $companyDetails->customer_id < 1){
					$cdata['customer_id']=$cid;
                    $res=OnboardingService::updateCompany($cdata,$email); 

                } 
                    
                       
                        //creating note for pre-qualified
                        $cus_id=$companyDetails->id;
						
						if($pre_data){
                            $pre_ind_id = $pre_data->industries;
							$pre_loc_id = $pre_data->location;

							$ind_nm=OnboardingService::getIndustriesName($pre_ind_id);
							$location=OnboardingService::getStatesName($pre_loc_id);

							if($pre_data->requirement !=''){ $req = $pre_data->requirement;}else{$req ='NA';}

							if($pre_data->industry_type !=''){ $industry_type = $pre_data->industry_type;}else{$industry_type ='NA';}

							if($pre_data->desc !=''){ $desc = $pre_data->desc;}else{$desc ='NA';}

							if($pre_data->budget !=''){ $budget = $pre_data->budget;}else{$budget ='NA';}
									
                            if($pre_data->land !=''){ $land = $pre_data->land;}else{$land ='NA';}

							if($pre_data->start_within !=''){ $start_within = $pre_data->start_within;}else{$start_within ='NA';}
				
							$comments='<p><span style="color:#fb483a;">Prequalified Lead-Customer Onboarding</span> <br/> Required Consultant for : '.$req.' <br/> Industry Type : '.$industry_type.'<br/> Industry : '.$ind_nm.'<br/> Description : '.$desc.'<br/> Budget : '.$budget.'<br/> Location : '.$location.'<br/> Land : '.$land.'<br/> Start Within : '.$start_within.'</p>';
										
                            // add note
										
                            $crm['cus_id']=$cus_id;
			 			    $crm['user_id']=1;
			 				$crm['activity']=2;
			 				$crm['type']=0;
			 				$crm['call_outcome']=0;
			 				$crm['comments']=$comments;
			 				$crm['date']=date('Y-m-d H:i:s');

			 				$log = OnboardingService::addLog($crm);

                            if($log){
                                $msg=['message'=>'Lead updated and Note created successfully'];
                                return $msg;
                            }
                            
                            
                        }
			
                
            }
            else{
                //new lead

               $customer = CommonService::getRowByCid($cid);

                if($pre_data ){

                    //if prequalified lead then create note and insert into database_complete as new lead
			
				$data['name'] = $customer->firstname;
				$data['email'] = $customer->email;
				$data['mobile'] = $customer->phone;
				$data['customer_id'] = $customer->customer_id;
				$data['create_date'] = $customer->date_added;
				$data['grade'] = 'P1-Prequalified';
                $data['created_by']=1;
                $data['assigned_to']=28;
                $data['previously_assigned']=1;


				$cus_id=OnboardingService::addCompany($data);

                 //creating note for pre-qualified

                $pre_ind_id = $pre_data->industries;
				$pre_loc_id = $pre_data->location;

				$ind_nm=OnboardingService::getIndustriesName($pre_ind_id);
				$location=OnboardingService::getStatesName($pre_loc_id);

				if($pre_data->requirement !=''){ $req = $pre_data->requirement;}else{$req ='NA';}

				if($pre_data->industry_type !=''){ $industry_type = $pre_data->industry_type;}else{$industry_type ='NA';}

				if($pre_data->desc !=''){ $desc = $pre_data->desc;}else{$desc ='NA';}

				if($pre_data->budget !=''){ $budget = $pre_data->budget;}else{$budget ='NA';}

				if($pre_data->land !=''){ $land = $pre_data->land;}else{$land ='NA';}

				if($pre_data->start_within !=''){ $start_within = $pre_data->start_within;}else{$start_within ='NA';}

					$comments='<p><span style="color:#fb483a;">Prequalified Lead-Customer Onboarding</span> <br/> Required Consultant for : '.$req.' <br/> Industry Type : '.$industry_type.'<br/> Industry : '.$ind_nm.'<br/> Description : '.$desc.'<br/> Budget : '.$budget.'<br/> Location : '.$location.'<br/> Land : '.$land.'<br/> Start Within : '.$start_within.'</p>';
					
                    // add note
					$crm['cus_id']=$cus_id;
					$crm['user_id']=1;
					$crm['activity']=2;
					$crm['type']=0;
					$crm['call_outcome']=0;
					$crm['comments']=$comments;
					$crm['date']=date('Y-m-d H:i:s');
					 
                    $log = OnboardingService::addLog($crm);

                    if($log){
                        $msg=['message'=>'Lead inserted and Note created successfully'];
                        return $msg;
                    }
                   

            }
           
    
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

public function getInfo(){

    try{
        $digits1 = 1;
        $data1 = rand(pow(10, $digits1-1), pow(10, $digits1)-1);
    
        $digits2 = 2;
        $data2 = rand(pow(10, $digits2-1), pow(10, $digits2)-1);
    
        $digits3 = 3;
        $data3 = rand(pow(10, $digits3-1), pow(10, $digits3)-1);
    
        $digits4 = 4;
        $data4 = rand(pow(10, $digits4-1), pow(10, $digits4)-1);
    
    
        $email = auth()->user()->email;
        $customer_id = CommonService::getCidByEmail($email);
        $req= OnboardingService::getReq($customer_id);
    
        $data['requirement']=$req->requirement;
        $ind_nm=OnboardingService::getIndustriesName($req->industries);
        $data['industry']= $ind_nm;
        $data['oneDigit']=$data1;
        $data['twoDigit']=$data2;
        $data['threeDigit']=$data3;
        $data['fourDigit']=$data4;

        return response()->json($data);
    }
    catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 404);
    }

}

public function OnboardingPaymentNotifySS(){
    try{
        $email = auth()->user()->email;
        $customer_id = CommonService::getCidByEmail($email);
        //update step as 4
        $stepData['step']=4;
        $res = ProfileService::editCustomerProfile($stepData,$customer_id);

        //onboarding payment page visitor notification
    
//         $slack_cid = $customer_id;
// 		$slack_name = ProfileService::getFullName($slack_cid);
// 		$slack_email = $email;
// 		$slack_phone = ProfileService::getPhone($slack_cid);
// 		$slack_assign_id = ProfileService::getAssignedTobyEmail($slack_email);
						
// 			if($slack_assign_id!=null){
// 				//$slack_assign_id = ProfileService::getAssignedTobyCid($customer_id);
// 				$slack_assign_name= ProfileService::getAssignedName($slack_assign_id);
// 			}
// 			else{
// 				$slack_assign_name= "Not Assigned";
// 			}

// 			$option= array (
// 				'blocks' => 
// 				array (
// 				  0 => 
// 				  array (
// 					'type' => 'section',
// 					'text' => 
// 					array (
// 					  'type' => 'mrkdwn',
// 					  'text' => '*Onboarding Payment Page Visitor Notification:*',
// 					),
// 				  ),
// 				  1 => 
// 				  array (
// 					'type' => 'divider',
// 				  ),
// 				  2 => 
// 				  array (
// 					'type' => 'section',
// 					'fields' => 
// 					array (
// 					  0 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Customer ID:*
// '.$slack_cid,
// 					  ),
// 					  1 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Name:*
// '.$slack_name,
// 					  ),
// 					  2 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Email ID:*
// '.$slack_email,
// 					  ),
// 					  3 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Phone:*
// '.$slack_phone,
// 					  ),
// 					  4 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Assigned to:*
// '.$slack_assign_name,
// 					  ),
// 					),
// 				  ),
// 				  3 => 
// 				  array (
// 					'type' => 'context',
// 					'elements' => 
// 					array (
// 					  0 => 
// 					  array (
// 						'type' => 'image',
// 						'image_url' => 'https://www.solutionbuggy.com/assets/img/alert.jpg',
// 						'alt_text' => 'cute cat',
// 					  ),
// 					  1 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => 'Action required...',
// 					  ),
// 					),
// 				  ),
// 				),
// 			  );
		  
	  
// 		  $message = array('payload' => json_encode($option));
	  
// 		  $ch = curl_init("https://hooks.slack.com/services/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");
	  
	  
// 	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
// 	  curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
// 	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 	  $result = curl_exec($ch);
// 	  // var_dump($result);
// 	  curl_close($ch);

        return response()->json(['message'=>'Paymennt Notification sent successfully'],Response::HTTP_OK);
  
    }
    catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 404);
    }
}


public function OnboardingPaymentNotifySP(){
    try{
        $email = auth()->user()->email;
        $customer_id = CommonService::getCidByEmail($email);
        //update step as 5
        $stepData['step']=5;
        $res = ProfileService::editCustomerProfile($stepData,$customer_id);

        //onboarding payment page visitor notification
    
//         $slack_cid = $customer_id;
// 		$slack_name = ProfileService::getFullName($slack_cid);
// 		$slack_email = $email;
// 		$slack_phone = ProfileService::getPhone($slack_cid);
// 		$slack_assign_id = ProfileService::getAssignedTobyEmail($slack_email);
						
// 			if($slack_assign_id!=null){
// 				//$slack_assign_id = ProfileService::getAssignedTobyCid($customer_id);
// 				$slack_assign_name= ProfileService::getAssignedName($slack_assign_id);
// 			}
// 			else{
// 				$slack_assign_name= "Not Assigned";
// 			}

// 			$option= array (
// 				'blocks' => 
// 				array (
// 				  0 => 
// 				  array (
// 					'type' => 'section',
// 					'text' => 
// 					array (
// 					  'type' => 'mrkdwn',
// 					  'text' => '*Onboarding Payment Page Visitor Notification:*',
// 					),
// 				  ),
// 				  1 => 
// 				  array (
// 					'type' => 'divider',
// 				  ),
// 				  2 => 
// 				  array (
// 					'type' => 'section',
// 					'fields' => 
// 					array (
// 					  0 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Customer ID:*
// '.$slack_cid,
// 					  ),
// 					  1 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Name:*
// '.$slack_name,
// 					  ),
// 					  2 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Email ID:*
// '.$slack_email,
// 					  ),
// 					  3 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Phone:*
// '.$slack_phone,
// 					  ),
// 					  4 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => '*Assigned to:*
// '.$slack_assign_name,
// 					  ),
// 					),
// 				  ),
// 				  3 => 
// 				  array (
// 					'type' => 'context',
// 					'elements' => 
// 					array (
// 					  0 => 
// 					  array (
// 						'type' => 'image',
// 						'image_url' => 'https://www.solutionbuggy.com/assets/img/alert.jpg',
// 						'alt_text' => 'cute cat',
// 					  ),
// 					  1 => 
// 					  array (
// 						'type' => 'mrkdwn',
// 						'text' => 'Action required...',
// 					  ),
// 					),
// 				  ),
// 				),
// 			  );
		  
	  
// 		  $message = array('payload' => json_encode($option));
	  
// 		  $ch = curl_init("https://hooks.slack.com/services/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");
	  
	  
// 	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
// 	  curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
// 	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 	  $result = curl_exec($ch);
// 	  // var_dump($result);
// 	  curl_close($ch);

        return response()->json(['message'=>'Paymennt Notification sent successfully'],Response::HTTP_OK);
  
    }
    catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 404);
    }
}



}
?>
