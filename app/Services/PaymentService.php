<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Invoice;
use App\Models\Subscriberplane;
use App\Models\Customer;
use App\Models\CustomerPlane;
use Illuminate\Support\Facades\DB;

interface PaymentInterface
{
    // public static function failureNotification($customer__id);

}

class PaymentService implements PaymentInterface
{
    // public static function failureNotification($customer__id){
    //     $date = date('Y-m-d H:i:s');
    //     $res = DB::table('customer')
    //                 ->select('payment_status')
    //                 ->where('customer_id',$customer__id)
    //                 ->get()
    //                 ->first();
    //      $payment_status=$res->payment_status;
		
	// 	if($payment_status==2){
	// 		$data=['date_updated'=>$date];
	// 	}
	// 	else{
	// 		$data=[
	// 			'date_updated'=>$date,
	// 			'payment_status'=>2
	// 		];
	// 	}
	// 	$affectedRows = Customer::where('customer_id', $customer_id)->update($data);
    //     if ($affectedRows > 0) {
    //         return true;
    //     }
    // }
}
?>