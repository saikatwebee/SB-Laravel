<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth ;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Auth as AuthModel;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Services\AuthService;
use App\Services\CommonService;
use App\Services\ProfileService;
use App\Mail\OtpSent;
use App\Mail\RegistrationMail;

class AuthController extends Controller {

    public function register( Request $request ) {

        try {
            $firstname = trim( $request->input( 'firstname' ) );
            $email = trim( $request->input( 'email' ) );
            $phone = trim( $request->input( 'phone' ) );
            $last_ten = substr( $phone, -10, 10 );
            $ph = '+91'.$last_ten;

            $password = trim( $request->input( 'password' ) );
            $role = trim( $request->input( 'role' ) );
            $status = trim( $request->input( 'status' ) );

            $rules = [
                'firstname' => 'required|min:3',
                'email' => 'required|email|unique:auth',
                'password'=>'required|min:5|max:15',
                'phone'=>'required|numeric|min:10',
                'role'=>'required'
            ];

            if ( $email != '' ) {
                $check_email = AuthService::check_email( $email );
                if ( !$check_email ) {
                    //for new user
                    $validator = Validator::make( $request->all(), $rules );

                    if ( $validator->fails() ) {
                        return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
                    } else {
                        $data = AuthService::auth_insert( $firstname, $email, $password, $role, $status );
                        if ( $data ) {
                            $role = $data[ 'role' ];
                            if ( $role == 'SS' || $role == 'SP' ) {

                                //insert into customer
                                $howsb = trim( $request->input( 'howsb' ) );
                                $reg_url = trim( $request->input( 'reg_url' ) );
                                $res = AuthService::customer_insert( $firstname, $email, $phone, $role, $howsb, $reg_url );
                                if ( $res ) {
                                    $cid = AuthService::get_cid_reg( $email );
                                    $fullname = ProfileService::getFullName( $cid );

                                    //thank you sms after registration complete

                                    $body = [
                                        'parameters' => [
                                            [
                                                'name' => 'fullname',
                                                'value' => $fullname
                                            ],

                                        ],
                                        'template_name' => 'thankyou_msg_registered',
                                        'broadcast_name' => 'sb-ThankYou'
                                    ];

                                    $msg = json_encode( $body );

                                    $ch = curl_init( 'https://live-server-6804.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$ph );

                                    $authorization = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2NDczODQzNy0zMDVjLTQ5NDctOGI1MC0zMzllMWRhNjIxNGIiLCJ1bmlxdWVfbmFtZSI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwibmFtZWlkIjoiYWRtaW5Ac29sdXRpb25idWdneS5jb20iLCJlbWFpbCI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwiYXV0aF90aW1lIjoiMDEvMTcvMjAyMiAxMDoyMTo1OCIsImRiX25hbWUiOiI2ODA0IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Y_KsRhEnu_NKsxOf0U5HfHRILpnENXShJsgjjTbL5Ss';
                                    // Prepare the authorisation token

                                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', $authorization ) );
                                    // Inject the token into the header
                                    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
                                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $msg );
                                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                                    $result = curl_exec( $ch );
                                    curl_close( $ch );

                                    //registration mail to user

                                    $email_data[ 'fullname' ] = $fullname;
                                    Mail::to( $email )->send( new RegistrationMail( $email_data ) );

                                    $trackingdata = array (
                                        'customer_id' => $cid,
                                        'sb_first_typ' => $request->input( 'sb_first_typ' ),
                                        'sb_first_src' => $request->input( 'sb_first_src' ),
                                        'sb_first_mdm' => $request->input( 'sb_first_mdm' ),
                                        'sb_first_cmp' => $request->input( 'sb_first_cmp' ),
                                        'sb_first_cnt' => $request->input( 'sb_first_cnt' ),
                                        'sb_first_trm' => $request->input( 'sb_first_trm' ),
                                        'sb_current_typ' => $request->input( 'sb_current_typ' ),
                                        'sb_current_src' => $request->input( 'sb_current_src' ),
                                        'sb_current_mdm' => $request->input( 'sb_current_mdm' ),
                                        'sb_current_cmp' => $request->input( 'sb_current_cmp' ),
                                        'sb_current_cnt' => $request->input( 'sb_current_cnt' ),
                                        'sb_current_trm' => $request->input( 'sb_current_trm' ),
                                        'sb_first_add_fd' => $request->input( 'sb_first_add_fd' ),
                                        'sb_first_add_ep' => $request->input( 'sb_first_add_ep' ),
                                        'sb_first_add_rf' => $request->input( 'sb_first_add_rf' ),
                                        'sb_current_add_fd' => $request->input( 'sb_current_add_fd' ),
                                        'sb_current_add_ep' => $request->input( 'sb_current_add_ep' ),
                                        'sb_current_add_rf' => $request->input( 'sb_current_add_rf' ),
                                        'sb_session_pgs' => $request->input( 'sb_session_pgs' ),
                                        'sb_session_cpg' => $request->input( 'sb_session_cpg' ),
                                        'sb_udata_vst' => $request->input( 'sb_udata_vst' ),
                                        'sb_udata_uip' => $request->input( 'sb_udata_uip' )
                                    );

                                    AuthService::addTracking( $trackingdata );

                                    //generating token after successfully register to solutionbuggy portal.

                                    $jwtarr = [
                                        'email'=>$email,
                                        'password'=>$password
                                    ];

                                    if ( !$token = JWTAuth::attempt( $jwtarr ) ) {
                                        return response()->json( [ 'message'=>'Unauthorized User!' ], 502 );

                                    }

                                    $user = $this->auth_user_profile();
                                    $role = $this->get_role();

                                    return response()->json( [
                                        'access_token'=>$token,
                                        'token_type'=>'bearer',
                                        'role'=>$role,
                                        'user'=>$user,
                                        'tracking' =>$trackingdata,
                                        'expires_in'=> auth()->factory()->getTTL()*60,
                                    ] );

                                    //return  $this->createNewToken( $token );

                                }
                            } else {
                                //insert into user
                                $res = AuthService::user_insert( $firstname, $email, $phone, $role, $status );
                                if ( $res )
                                return response()->json( [ 'success' => true, 'message' => 'Admin Registration Successful' ], 200 );
                            }

                        }

                    }
                } else {
                    return response()->json( [ 'success' => true, 'message' => 'Email is already exist!' ], 502 );
                }
            }
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }


    public function sentRegisterMail(Request $request){
       
        $email = trim($request->input('email'));
        $phone = trim( $request->input( 'phone' ) );

        $last_ten = substr( $phone, -10, 10 );
        $ph = '+91'.$last_ten;
        $cid = AuthService::get_cid_reg($email);
        $fullname = ProfileService::getFullName($cid);

        //register wati sms 

        $body = [
            'parameters' => [
                [
                    'name' => 'fullname',
                    'value' => $fullname
                ],

            ],
            'template_name' => 'thankyou_msg_registered',
            'broadcast_name' => 'sb-ThankYou'
        ];

        $msg = json_encode( $body );

        $ch = curl_init( 'https://live-server-6804.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$ph );

        $authorization = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2NDczODQzNy0zMDVjLTQ5NDctOGI1MC0zMzllMWRhNjIxNGIiLCJ1bmlxdWVfbmFtZSI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwibmFtZWlkIjoiYWRtaW5Ac29sdXRpb25idWdneS5jb20iLCJlbWFpbCI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwiYXV0aF90aW1lIjoiMDEvMTcvMjAyMiAxMDoyMTo1OCIsImRiX25hbWUiOiI2ODA0IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Y_KsRhEnu_NKsxOf0U5HfHRILpnENXShJsgjjTbL5Ss';
        // Prepare the authorisation token

        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', $authorization ) );
        // Inject the token into the header
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $msg );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        $result = curl_exec( $ch );
        curl_close( $ch );

        //registration mail to user

        $email_data[ 'fullname' ] = $fullname;
        Mail::to( $email )->send( new RegistrationMail( $email_data ) );


    }


    public function adsRegister( Request $request ) {
        try {
            $firstname = trim( $request->input( 'firstname' ) );
            $email = trim( $request->input( 'email' ) );
            $phone = trim( $request->input( 'phone' ) );
            $password = trim( $request->input( 'password' ) );
            $role = trim( $request->input( 'role' ) );
            $status = trim( $request->input( 'status' ) );

            $rules = [
                'firstname' => 'required|min:3',
                'email' => 'required|email|unique:auth',
                'password'=>'required|min:5|max:15',
                'phone'=>'required|numeric|min:10',
                'role'=>'required'
            ];

            if ( $email != '' ) {
                $check_email = AuthService::check_email( $email );
                if ( !$check_email ) {
                    //for new user

                    $validator = Validator::make( $request->all(), $rules );

                    if ( $validator->fails() ) {
                        return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
                    } else {
                        $data = AuthService::auth_insert( $firstname, $email, $password, $role, $status );
                        if ( $data ) {
                            $role = $data[ 'role' ];
                            if ( $role == 'SS' || $role == 'SP' ) {

                                //insert into customer

                                $howsb = trim( $request->input( 'howsb' ) );
                                $reg_url = trim( $request->input( 'reg_url' ) );
                                $res = AuthService::customer_insert( $firstname, $email, $phone, $role, $howsb, $reg_url );
                                if ( $res ) {

                                    $cid = AuthService::get_cid_reg( $email );
                                    $trackingdata = array (
                                        'customer_id' => $cid,
                                        'sb_first_typ' => $request->input( 'sb_first_typ' ),
                                        'sb_first_src' => $request->input( 'sb_first_src' ),
                                        'sb_first_mdm' => $request->input( 'sb_first_mdm' ),
                                        'sb_first_cmp' => $request->input( 'sb_first_cmp' ),
                                        'sb_first_cnt' => $request->input( 'sb_first_cnt' ),
                                        'sb_first_trm' => $request->input( 'sb_first_trm' ),
                                        'sb_current_typ' => $request->input( 'sb_current_typ' ),
                                        'sb_current_src' => $request->input( 'sb_current_src' ),
                                        'sb_current_mdm' => $request->input( 'sb_current_mdm' ),
                                        'sb_current_cmp' => $request->input( 'sb_current_cmp' ),
                                        'sb_current_cnt' => $request->input( 'sb_current_cnt' ),
                                        'sb_current_trm' => $request->input( 'sb_current_trm' ),
                                        'sb_first_add_fd' => $request->input( 'sb_first_add_fd' ),
                                        'sb_first_add_ep' => $request->input( 'sb_first_add_ep' ),
                                        'sb_first_add_rf' => $request->input( 'sb_first_add_rf' ),
                                        'sb_current_add_fd' => $request->input( 'sb_current_add_fd' ),
                                        'sb_current_add_ep' => $request->input( 'sb_current_add_ep' ),
                                        'sb_current_add_rf' => $request->input( 'sb_current_add_rf' ),
                                        'sb_session_pgs' => $request->input( 'sb_session_pgs' ),
                                        'sb_session_cpg' => $request->input( 'sb_session_cpg' ),
                                        'sb_udata_vst' => $request->input( 'sb_udata_vst' ),
                                        'sb_udata_uip' => $request->input( 'sb_udata_uip' )
                                    );

                                    AuthService::addTracking( $trackingdata );

                                    //redirecting to thank-you page
                                    header( 'Location: https://solutionbuggy.com/register/thanks/'.$cid );
                                }

                            }
                        }
                    }
                } else {
                    //existing warning
                    return response()->json( [ 'success' => true, 'message' => 'Email is already exist!' ], 400 );
                }
            }

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function login( Request $request ) {

        try {
            $rules = [
                'email' => 'required|email',
                'password'=>'required|min:5|max:15',
            ];

            $validator = Validator::make( $request->all(), $rules );
            if ( $validator->fails() ) {
                return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
            }

            if ( !$token = JWTAuth::attempt( $validator->validated() ) ) {
                return response()->json( [ 'message'=>'Unauthorized User!' ], 502 );

            }

            return  $this->createNewToken( $token );
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }

    }

    public function loginWithOtp( Request $request ) {
        try {
            $email = trim( $request->input( 'email' ) );
            $rules = [
                'email' => 'required|email',
            ];

            $validator = Validator::make( $request->all(), $rules );
            if ( $validator->fails() ) {
                return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
            } else {
                if ( $email != '' ) {
                    $check_email = AuthService::check_email( $email );
                    if ( $check_email ) {
                        //for existing user
                        $customer_id = CommonService::getCidByEmail( $email );
                        if ( $customer_id ) {
                            $auth_id = CommonService::getAuthIdByEmail( $email );
                            $phone  = CommonService::getCphByEmail( $email );
                            $last_ten = substr( $phone, -10, 10 );
                            $ph = '+91'.$last_ten;
                            $otp = mt_rand( 1111, 9999 );

                            //Account Activation
                            $data = [ 'status'=>1 ];
                            $res = ProfileService::editCustomerProfile( $data, $customer_id );

                            //check Account Activation
                            $check = ProfileService::CheckActivation( $email );
                            if ( $check ) {

                                //sending otp sms
                                //otp sms

                                $ch = curl_init();

                                curl_setopt( $ch, CURLOPT_URL, 'https://api.kaleyra.io/v1/HXIN1700258037IN/messages' );
                                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                curl_setopt( $ch, CURLOPT_POST, 1 );
                                curl_setopt( $ch, CURLOPT_POSTFIELDS, 'to='.$ph.'&type=OTP&sender=SOLBUG&body= '.$otp.' is your OTP for login to SolutionBuggy Portal. Please do not share it with anyone.&template_id=1007163645755201460' );

                                $headers = array();
                                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                                $headers[] = 'Api-Key: Aaa25fd00f22308bba995277ea7baea2b';
                                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

                                $result = curl_exec( $ch );
                                $error = curl_error( $ch );

                                curl_close( $ch );

                                //sending email otp
                                $email_data[ 'otp' ] = $otp;
                                $email_data[ 'fullname' ] = ProfileService::getFullName( $customer_id );
                                Mail::to( $email )->send( new OtpSent( $email_data ) );

                                //wati sms for otp

                                $body = [
                                    'parameters' => [
                                        [
                                            'name' => 'otp',
                                            'value' => $otp
                                        ],

                                    ],
                                    'template_name' => 'otp_verification',
                                    'broadcast_name' => 'sb-otp'
                                ];

                                $msg = json_encode( $body );

                                $ch2 = curl_init( 'https://live-server-6804.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$ph );

                                $authorization = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2NDczODQzNy0zMDVjLTQ5NDctOGI1MC0zMzllMWRhNjIxNGIiLCJ1bmlxdWVfbmFtZSI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwibmFtZWlkIjoiYWRtaW5Ac29sdXRpb25idWdneS5jb20iLCJlbWFpbCI6ImFkbWluQHNvbHV0aW9uYnVnZ3kuY29tIiwiYXV0aF90aW1lIjoiMDEvMTcvMjAyMiAxMDoyMTo1OCIsImRiX25hbWUiOiI2ODA0IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Y_KsRhEnu_NKsxOf0U5HfHRILpnENXShJsgjjTbL5Ss';
                                // Prepare the authorisation token

                                curl_setopt( $ch2, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', $authorization ) );
                                // Inject the token into the header
                                curl_setopt( $ch2, CURLOPT_CUSTOMREQUEST, 'POST' );
                                curl_setopt( $ch2, CURLOPT_POSTFIELDS, $msg );
                                curl_setopt( $ch2, CURLOPT_RETURNTRANSFER, true );
                                curl_setopt( $ch2, CURLOPT_SSL_VERIFYPEER, false );
                                $result2 = curl_exec( $ch2 );
                                curl_close( $ch2 );

                                $otpData = [ 'password'=>bcrypt( $otp ) ];
                                $row = AuthService::auth_update( $otpData, $auth_id );

                                return response()->json( [ 'message' => 'Otp has been sent successfully' ], 200 );

                            } else {
                                //Account not activated
                                return response()->json( [ 'message' => 'Account not activated. Please check your registered email inbox and activate your account. If you face any difficulty contact - 080-42171111' ], 502 );
                            }
                        } else {
                            return response()->json( [ 'message'=>'Oops! Invalid Email' ], 502 );
                        }

                    } else {
                        return response()->json( [ 'message' => 'Your Email is not Registered' ], 502 );
                    }
                }
            }
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function checkOtp( Request $request ) {
        try {
            $data[ 'password' ] = trim( $request->input( 'password' ) );
            //$data[ 'email' ] = auth()->user()->email;
            $data[ 'email' ] = trim( $request->input( 'email' ) );
            $rules = [
                'email'  => 'required',
                'password' => 'required',

            ];

            $validator = Validator::make( $data, $rules );
            if ( $validator->fails() ) {
                return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
            } else {
                if ( !$token = JWTAuth::attempt( $validator->validated() ) ) {
                    return response()->json( [ 'message'=>'Unauthorized User!' ], 502 );

                }

                //update step if it is less than 3

                $customer_id = CommonService::getCidByEmail( $data[ 'email' ] );
                $row = CommonService::getRowByCid( $customer_id );
                $step = $row->step;
                if ( $step < 3 ) {
                    //update step as 2
                    $stepData[ 'step' ] = 2;
                    AuthService::updateStep( $stepData, $customer_id );
                }

                return  $this->createNewToken( $token );
            }
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function logout() {
        auth()->logout();
        return response()->json( [ 'message' => 'User successfully signed out' ], 200 );
    }

    public function refresh() {

        return $this->createNewToken( auth()->refresh() );
        //return $this->respondWithToken( auth()->refresh() );
    }

    public function get_role() {
        return auth()->user()->role;
    }

    public function userProfile() {
        $user = $this->auth_user_profile();
        return response()->json( $user );
    }

    public function auth_user_profile() {

        $authUser_email = auth()->user()->email;
        $role = $this->get_role();
        if ( $role == 'SS' || $role == 'SP' )
        //fetch from customer table
        return AuthService::customer_auth( $authUser_email );
        else
        //fetch from user table
        return AuthService::user_auth( $authUser_email );
    }

public function getAuthUser() {
        $authUser_email = auth()->user()->email;
        $role = $this->get_role();
        if ( $role == 'SS' || $role == 'SP' ) {
            //fetch from customer table
            $user = AuthService::customer_auth( $authUser_email );
        } else {
            //fetch from user table
            $user = AuthService::user_auth( $authUser_email );
        }

        return response()->json(['user'=>$user,'role'=> $role]);

    }

    public function stateList() {
        $res = AuthService::get_state_list();
        return response()->json( $res );
    }

    public function industryList() {
        $res = AuthService::get_industry_list();
        return response()->json( $res );
    }

    public function categoryList() {
        $res = AuthService::get_category_list();
        return response()->json( $res );
    }

    public function skillList() {
        $res = AuthService::get_skill_list();
        return response()->json( $res );
    }

    public function changePassword( Request $request ) {
        try {
            // $oldPassword = trim( $request->input( 'oldpassword' ) );
            $password = trim( $request->input( 'newpassword' ) );

            $rules = [
                'newpassword'=>'required|min:5|max:15',
            ];

            $validator = Validator::make( $request->all(), $rules );
            if ( $validator->fails() ) {
                return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
            } else {
                $res = AuthService::changePassword( bcrypt( $password ), auth()->user()->id );
                if ( $res )
                return response()->json( [ 'success' => true, 'message' => 'Password changed Successfully' ], 200 );

            }

        } catch ( Exception $e ) {
            return response()->json( [ 'message' => $e->getMessage() ], 502 );
        }
    }

    public function admin_login( Request $request ) {

        $mac = $request->input( 'mac' );
        $email = $request->input( 'email' );

        $rules = [
            'email' => 'required|email|',
            'password'=>'required|min:5|max:15',
        ];

        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return response()->json( [ 'info' => $validator->errors()->toJson(), 'message' => 'Oops Invalid data request!' ], 400 );
        } else {

            $check_mac = AuthService::check_mac( $mac, $email );

            if ( $check_mac ) {
                //token creation
                if ( !$token = JWTAuth::attempt( $validator->validated() ) ) {
                    return response()->json( [ 'message'=>'Email or Password is incorrect!' ], 502 );
                }
                return  $this->createNewToken( $token );
            } else {
                return response()->json( [ 'message'=>'Mac Address is not present,Kindly Contact IT Team!' ], 502 );
            }
        }

    }

    public function adminTokenValidation( Request $request ) {

        try {
            $token = $request->input( 'token' );
            $payload = JWTAuth::setToken( $token )->getPayload();
            return response()->json( $payload );

        } catch ( \Tymon\JWTAuth\Exceptions\TokenExpiredException $e ) {

            return response()->json( [ 'message'=>'Token is expired' ], 403 );

        } catch ( \Tymon\JWTAuth\Exceptions\TokenInvalidException $e ) {

            return response()->json( [ 'message'=>'Token is invalid' ], 401 );

        } catch ( \Tymon\JWTAuth\Exceptions\JWTException $e ) {

            return response()->json( [ 'message' => 'Authorization Token not found' ], 500 );

        }
    }

    public function createNewToken( $token ) {
        $user = $this->auth_user_profile();
        $role = $this->get_role();

        $email = auth()->user()->email;
        //date_default_timezone_set( 'Asia/Kolkata' );
        $loginData[ 'last_login' ] = date( 'Y-m-d H:i:s' );
        //update last_login
        $check = AuthService::updateLastLogin( $email, $loginData );

        if ( $check ) {
            return response()->json( [
                'access_token'=>$token,
                'token_type'=>'bearer',
                'role'=>$role,
                'user'=>$user,
                'expires_in'=> auth()->factory()->getTTL()*60,
            ] );
        }

    }

}

?>