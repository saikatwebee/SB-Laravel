<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Invoice;
use App\Models\Subscriberplane;
use App\Models\Customer;

interface InvoiceInterface
{
    public static function addInvoiceTable($data,$customer_id);
}

class InvoiceService implements InvoiceInterface
{
    public static function addInvoiceTable($data,$customer_id)
    {
        $affectedRows = Invoice::where('customer_id', $customer_id)->update(
            $data
        );
        if ($affectedRows > 0) {
            return true;
        }
    }
     
}

?>
