<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CommonService;
use App\Services\InvoiceService;
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
			$txnid  = $request->input('txnid ');
			return response()->json($txnid );
		}
		catch (Exception $e) {
			return response()->json(['message' => $e->getMessage()], 404);
		}

	}

	public function paymentFailed(Request $request){
		try {
			$txnid  = $request->input('txnid ');
			echo "hi";
			//return response()->json($txnid );
		}
		catch (Exception $e) {
			return response()->json(['message' => $e->getMessage()], 404);
		}

	}


}
