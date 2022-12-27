<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Invoice;
use App\Services\CommonService;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function invoiceList(){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
    
        $res = DB::table('invoice')
            ->leftJoin('subscriberplane', 'subscriberplane.id', '=', 'invoice.plan_id')
            ->select(
                'invoice.id as invoiceId',
                'invoice.date',
                'subscriberplane.title',
                'invoice.totalcost'
            )
            ->where('invoice.customer_id',$customer_id)
            ->get();
    
        return response()->json($res);
    }
    
}
