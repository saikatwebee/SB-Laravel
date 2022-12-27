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

    
    //View Invoice by ID
    public function ViewInvoice(Request $request)
    {
        try {
            $inv_id = trim($request->input('inv_id'));
            return InvoiceService::getInvoice($inv_id);
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //Insert New Invoice Data
    public function add_invoice(Request $request)
    {
        try {
            $data = [
                'customer_id' => trim($request->input('customer_id')),
                'plan_id' => trim($request->input('plan_id')),
                'txn_id' => trim($request->input('txn_id')),
                'plancost' => trim($request->input('plancost')),
                'date' => date('Y-m-d H:i:s'),
                'tax' => trim($request->input('tax')),
                'totalcost' => trim($request->input('totalcost'))
            ];
            $res = InvoiceService::AddInvoice($data);
            if ($res) {
                return response()->json(
                    [
                        'success' => true,
                        'message' =>
                            'Invoice Added Successfully',
                        'status' => '200',
                    ],
                    Response::HTTP_OK
                );

            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
}
