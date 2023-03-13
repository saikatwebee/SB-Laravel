<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CommonService;
use App\Services\ProfileService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PlaneController extends Controller
{
    public function getSubcriberPlane(Request $request){
        try {
            $plane_id = trim($request->input('id'));

            $wallet = 0;
            $code = "";
            $data['planeInfo'] = InvoiceService::getPlandetails($plane_id);
            $discount = $data['planeInfo']->discount;
            $cost = $data['planeInfo']->cost;
            $maxwallet = $data['planeInfo']->refer_discount;
				
				if ($maxwallet >= $wallet ){
					$wallet = $wallet;
				} else {
					$wallet = $maxwallet;
				}
				$cost = $cost - $wallet;
				

				$data['wallet'] = $wallet;
				$data['coupon'] = $code;
				$data['amount'] = $cost;
				$data['gst'] = $cost*0.18;
				$data['cost'] = $cost*1.18;
                $customer_id =  CommonService::getCidByEmail(auth()->user()->email);
                $data['customerInfo']= CommonService::getRowByCid($customer_id);
				$data['firstname'] = $data['customerInfo']->firstname;
				$data['lastname'] = ($data['customerInfo']->lastname) ? $data['customerInfo']->lastname : "";
				$data['address'] = ($data['customerInfo']->address) ? $data['customerInfo']->address : "";
				$data['city'] = ($data['customerInfo']->city) ? $data['customerInfo']->city : "";
				$data['state'] = ($data['customerInfo']->state) ? $data['customerInfo']->state : "";
                $data['merchant_key'] ="pS1BAdcV";
                $data['salt'] = "r7kqyRbrYF";
				$data['payu_base_url'] = "https://secure.payu.in/_payment";	
				$data['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

                $hash_array = array();
				$hash_array['key'] = $data['merchant_key'];
				$hash_array['txnid'] = $data['txnid'];
				$hash_array['amount'] = $cost*1.18;
				$hash_array['productinfo'] = $data['planeInfo']->title;
				$hash_array['firstname'] = $data['customerInfo']->firstname;
				$hash_array['email'] = $data['customerInfo']->email;
				//$hash_array['phone'] = $data['customerInfo']->phone;
				$hash_array['udf1'] = $customer_id;
				$hash_array['udf2'] = $plane_id;
				$hash_array['udf3'] = $code;
				$hash_array['udf4'] = $wallet;
				$hash_array['udf5'] = '';
				$hash_array['udf6'] = '';
				$hash_array['udf7'] = '';
				$hash_array['udf8'] = '';
				$hash_array['udf9'] = '';
				$hash_array['udf10'] = '';
				$hash_string = implode('|',$hash_array);
	    		$hash_string .= '|'.$data['salt'];
				$data['hash'] = strtolower(hash('sha512', $hash_string));
				$data['action'] = $data['payu_base_url'] . '/_payment';
                return response()->json($data);
             
         } catch (Exception $e) {
             return response()->json(['message' => $e->getMessage()], 404);
         }
    }

	public function paymentSuccess(Request $request){
		try {
			$data  = $request->all();
			
			return View('payment_success',['jsondata' => json_encode($data)]);

		}
		catch (Exception $e) {
			return response()->json(['message' => $e->getMessage()], 404);
		}

	}

	public function paymentFailed(Request $request){
		try {
			$postdata  = $request->all();
			
			// var_dump($postdata['additionalCharges']);
			// die;

				$data['status'] = $postdata['status'];
				$data['message'] = $postdata['error_Message'];
				$data['firstname'] = $postdata['firstname'];
				$data['amount'] = $postdata['amount'];
				$data['txnid'] = $postdata['txnid'];
				$posted_hash = $postdata['hash'];
				$data['key'] = $postdata['key'];
				$data['productinfo'] = $postdata['productinfo'];
				$data['email'] = $postdata['email'];
				$data['udf4'] = $postdata['udf4'];
				$data['udf3'] = $postdata['udf3'];
				$data['udf2'] = $postdata['udf2'];
				$data['udf1'] = $postdata['udf1'];
				$data['salt'] = "r7kqyRbrYF";

				

				if (!empty($postdata['additionalCharges'])){
					$data['additionalCharges']= $postdata['additionalCharges'];

					$hash_array = array();
					$hash_array['additionalCharges'] = $data['additionalCharges'];
					$hash_array['salt'] = $data['salt'];
					$hash_array['status'] = $data['status'];
					$hash_array['udf10'] = '';
					$hash_array['udf9'] = '';
					$hash_array['udf8'] = '';
					$hash_array['udf7'] = '';
					$hash_array['udf6'] = '';
					$hash_array['udf5'] = '';
					$hash_array['udf4'] = $data['udf4'];
					$hash_array['udf3'] = $data['udf3'];
					$hash_array['udf2'] = $data['udf2'];
					$hash_array['udf1'] = $data['udf1'];
					$hash_array['email'] = $data['email'];
					$hash_array['firstname'] = $data['firstname'];
					$hash_array['productinfo'] = $data['productinfo'];
					$hash_array['amount'] = $data['amount'];
					$hash_array['txnid'] = $data['txnid'];
					$hash_array['key'] = $data['key'];
					$hash_string = implode('|',$hash_array);
					}
					else {
					$hash_array = array();
					$hash_array['salt'] = $data['salt'];
					$hash_array['status'] = $data['status'];
					$hash_array['udf10'] = '';
					$hash_array['udf9'] = '';
					$hash_array['udf8'] = '';
					$hash_array['udf7'] = '';
					$hash_array['udf6'] = '';
					$hash_array['udf5'] = '';
					$hash_array['udf4'] = $data['udf4'];
					$hash_array['udf3'] = $data['udf3'];
					$hash_array['udf2'] = $data['udf2'];
					$hash_array['udf1'] = $data['udf1'];
					$hash_array['email'] = $data['email'];
					$hash_array['firstname'] = $data['firstname'];
					$hash_array['productinfo'] = $data['productinfo'];
					$hash_array['amount'] = $data['amount'];
					$hash_array['txnid'] = $data['txnid'];
					$hash_array['key'] = $data['key'];
					$hash_string = implode('|',$hash_array);	
					}
					$hash = strtolower(hash('sha512', $hash_string));

					if ($hash == $posted_hash) {
						//for failure notification
						$customer_id =  CommonService::getCidByEmail($data['email']);
						
						//PaymentService::failureNotification($customer__id);

						//email notification for failure

						//slack notification for failure



						$slack_cid=$customer_id;
						$slack_name = ProfileService::getFullName($customer_id);
						$slack_email = $data['email'];
						$slack_phone = ProfileService::getPhone($customer_id);
						$slack_assign_id = ProfileService::getAssignedTobyEmail($data['email']);
						
						if($slack_assign_id!=null){
							//$slack_assign_id = ProfileService::getAssignedTobyCid($customer_id);
							$slack_assign_name= ProfileService::getAssignedName($slack_assign_id);
						}
						else{
							$slack_assign_name= "Not Assigned";
						}
						$planId= $data['udf2'];
						$planData = InvoiceService::getPlandetails($planId);
						 $slack_planName= $planData->title;
						 $slack_planCost= $planData->cost;

						 $option= array (
							'blocks' => 
							array (
							  0 => 
							  array (
								'type' => 'section',
								'text' => 
								array (
								  'type' => 'mrkdwn',
								  'text' => '*Payment Failure Notification:*
'.$data['amount'].' ('.$slack_planName.')',
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
									'text' => '*Assigned to:*
'.$slack_assign_name,
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
									'image_url' => 'https://www.solutionbuggy.com/assets/img/cancel.png',
									'alt_text' => 'cute cat',
								  ),
								  1 => 
								  array (
									'type' => 'mrkdwn',
									'text' => 'Payment Failed',
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
						 



					}
					return View('payment_failed',['data' => json_encode($data)]);
				}
		catch (Exception $e) {
			return response()->json(['message' => $e->getMessage()], 404);
		}

	}


}
