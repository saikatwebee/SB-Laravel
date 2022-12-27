<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Invoice;
use App\Models\Subscriberplane;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

interface InvoiceInterface
{
    public static function updateInvoiceTable($data,$customer_id);
    public static function getInvoice($id);
    public static function AddInvoice($data);

}

class InvoiceService implements InvoiceInterface
{
    public static function updateInvoiceTable($data,$inv_id)
    {
        $affectedRows = Invoice::where('id', $inv_id)->update(
            $data
        );
        if ($affectedRows > 0) {
            return true;
        }
    }

    public static function getInvoice($id){
        $inv = DB::table('invoice')
            ->leftJoin('subscriberplane', 'subscriberplane.id', '=', 'invoice.plan_id')
            ->select(
                'invoice.*',
                'subscriberplane.title'
            )
            ->where('invoice.id',$id)
            ->get();
        return $inv;
        
    }
    public static function AddInvoice($data){
        $affectedRows = Invoice::insert($data);
        if($affectedRows > 0)
        return true;
    }
     
}

?>
