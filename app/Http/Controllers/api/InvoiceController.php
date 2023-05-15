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
use App\Services\ProblemService;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller {
    public function invoiceList() {
        $customer_id = CommonService::getCidByEmail( auth()->user()->email );

        $res = DB::table( 'invoice' )
        ->leftJoin( 'subscriberplane', 'subscriberplane.id', '=', 'invoice.plan_id' )
        ->select(
            'invoice.id as invoiceId',
            'invoice.date',
            'subscriberplane.title',
            'invoice.totalcost'
        )
        ->where( 'invoice.customer_id', $customer_id )
        ->get();

        return response()->json( $res );
    }

    //View Invoice by ID

    public function ViewInvoice( Request $request ) {
        try {
            $inv_id = trim( $request->input( 'inv_id' ) );
            return InvoiceService::getInvoice( $inv_id );

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    //Insert New Invoice Data

    public function add_invoice( Request $request ) {
        try {
            $data = [
                'customer_id' => trim( $request->input( 'customer_id' ) ),
                'plan_id' => trim( $request->input( 'plan_id' ) ),
                'txn_id' => trim( $request->input( 'txn_id' ) ),
                'plancost' => trim( $request->input( 'plancost' ) ),
                'date' => date( 'Y-m-d H:i:s' ),
                'tax' => trim( $request->input( 'tax' ) ),
                'totalcost' => trim( $request->input( 'totalcost' ) )
            ];
            $res = InvoiceService::AddInvoice( $data );
            if ( $res ) {
                return response()->json( [ 'success' => true, 'message' =>'Invoice Added Successfully' ], 200 );
            }
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    //View Current Plan Details

    public function ViewPlan() {
        try {
            $customer_id = CommonService::getCidByEmail( auth()->user()->email );
            $plan = InvoiceService::getPlan( $customer_id );
            return response()->json( $plan );

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function ViewPlanNonAuthenticate(Request $request) {
        try {
            $customer_id = trim($request->input('customer_id'));
            $plan = InvoiceService::getPlan( $customer_id );
            return response()->json( $plan );

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    

    //Add or Update Plan Details

    public function UpdatePlan( Request $request ) {
        try {
            $customer_id = CommonService::getCidByEmail( auth()->user()->email );
            $subscriberplane_id = trim( $request->input( 'subscriberplane_id' ) );
            $data[ 'date_updated' ] = date( 'Y-m-d H:i:s' );
            $plan = InvoiceService::getPlandetails( $subscriberplane_id );
            $data[ 'subscriberplane_id' ] = $subscriberplane_id;
            $data[ 'apply' ] = $plan->apply;
            $data[ 'problem' ] = $plan->problem_posting;
            $currentDateTime = Carbon::now();
            $data[ 'exp_plane' ] = $currentDateTime->addYears( $plan->validity );
            $cid_exist = InvoiceService::verifyCId( $customer_id );
            if ( $cid_exist ) {
                $res = InvoiceService::updatePlandetails( $data, $customer_id );
                if ( $res ) {
                    return response()->json( [ 'success' => true, 'message' => 'Updated Successfully' ], 200 );
                }
            } else {
                $data[ 'customer_id' ] = $customer_id;
                $res = InvoiceService::AddPlandetails( $data );
                if ( $res ) {
                    return response()->json( [ 'success' => true, 'message' => 'Added Successfully' ], 200 );
                }
            }

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    //Update GST Details

    public function update_gst( Request $request ) {
        try {
            $inv_id = trim( $request->input( 'inv_id' ) );
            $data[ 'company' ] = trim( $request->input( 'company' ) );
            $data[ 'address' ] = trim( $request->input( 'address' ) );
            $data[ 'gst' ] = trim( $request->input( 'gst' ) );
            $data[ 'state' ] = trim( $request->input( 'state' ) );
            $res = InvoiceService::updateInvoiceTable( $data, $inv_id );
            if ( $res ) {
                return response()->json( [ 'success' => true, 'message' => 'Updated Successfully' ], 200 );
            }

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    //View Subscriber Plan Details by Plan ID

    public function getPlan( Request $request ) {
        try {
            $plan_id = trim( $request->input( 'id' ) );
            return InvoiceService::getPlandetails( $plan_id );

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function paymentRequest( Request $request ) {
        try {
            $data[ 'cid' ] = CommonService::getCidByEmail( auth()->user()->email );
            $data[ 'pid' ] = trim( $request->input( 'ProjectId' ) );
            $data[ 'amount' ] = trim( $request->input( 'amount' ) );
            $data[ 'is_gst' ] = trim( $request->input( 'isgst' ) );
            $data[ 'gst' ] = trim( $request->input( 'gst' ) );
            $data[ 'doc_type' ] = trim( $request->input( 'doc_type' ) );
            $data[ 'reference' ] = trim( $request->input( 'reference_no' ) );
            $data[ 'account_nm' ] = trim( $request->input( 'account_nm' ) );
            $data[ 'account_no' ] = trim( $request->input( 'account_no' ) );
            $data[ 'ifsc' ] = trim( $request->input( 'ifsc' ) );

            if ( isset( $_FILES[ 'paymentDoc' ][ 'name' ] ) ) {
                if ( $request->file( 'paymentDoc' )->isValid() ) {
                    $uploaded_file = $this->paymentUpload( $request->file( 'paymentDoc' ), $data[ 'cid' ] );

                    if ( $uploaded_file ) {

                        $data[ 'payment_doc' ] = $uploaded_file;
                        $res = InvoiceService::addPaymentReq( $data );
                        if ( $res ) {
                            //add project files for payment_doc

                            $ftype = 3;
                            $root_url = 'https://api.solutionbuggy.com/';
                            //$root_url = 'http://127.0.0.1:8000/';
                            $file_path = $root_url.'payment/'.$data[ 'cid' ].'/'.$uploaded_file;

                            $fileData[ 'fpath' ] = $file_path;
                            $fileData[ 'fname' ] = $uploaded_file;

                            $fileData[ 'cid' ] = $data[ 'cid' ];
                            $fileData[ 'pid' ] = $data[ 'pid' ];
                            $fileData[ 'ftype' ] = $ftype;
                            $check = ProblemService::addProblemFiles( $fileData );
                            if ( $check )
                            return response()->json( [ 'success' => true, 'message' => 'Payment Request Submitted Successfully' ], 200 );

                        }
                    }
                }
            }

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }

    }

    public function getPaymentRequest( Request $request ) {
        try {
            $cid = CommonService::getCidByEmail( auth()->user()->email );
            $pid = trim( $request->input( 'pid' ) );
            $res = InvoiceService::getPaymentReq( $cid, $pid );
            if ( $res )
            return response()->json( $res );

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function paymentUpload( $file, $customer_id ) {
        $fileName = $file->getClientOriginalName();

        if ( !is_dir( public_path( 'payment/'.$customer_id ) ) ) {
            mkdir( public_path( 'payment/'.$customer_id ), 0777, true );
        }

        if ( $file->move( public_path( 'payment/'.$customer_id ), $fileName ) ) {
            return $fileName;
        }
    }

}
