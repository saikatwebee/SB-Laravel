<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

use Illuminate\Support\Facades\Auth ;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Auth as AuthModel;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Services\AuthService;




class AuthController extends Controller

{

    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function register(Request $request)
    {

        try {
            $firstname = trim($request->input('firstname'));
            $email = trim($request->input('email'));
            $phone = trim($request->input('phone'));
            $password = trim($request->input('password'));
            $role = trim($request->input('role'));
            $status = trim($request->input('status'));
            $rules = [
                "firstname" => "required|min:3",
                "email" => "required|email|unique:auth",
                "password"=>"required|min:5|max:15",
                "phone"=>"required|numeric|min:10",
                "role"=>"required"
            ];

           

            if($email!=''){
               $check_email= AuthService::check_email($email);
               if(!$check_email){
                    //for new user
                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                      // return response()->json($validator->errors()->toJson(),400);
                       return response()->json(['info' => $validator->errors()->toJson(),'message' => 'Oops! Invalid data request.','status'=>'220'], Response::HTTP_OK);
                    }
                    else{
                        $data = AuthService::auth_insert($firstname,$email,$password,$role,$status);
                        if($data){
                            $role = $data['role'];
                            if($role=="SS" || $role=="SP"){   
                                //insert into customer
                                $howsb = trim($request->input('howsb'));
                                $reg_url = trim($request->input('reg_url'));
                                $res = AuthService::customer_insert($firstname,$email,$phone,$role,$howsb,$reg_url);
                                if($res){
                                    //return response()->json(["Customer Registration Successful"]);
                                    return response()->json(['success' => true,'message' => 'Customer Registration Successful','status'=>'200'], Response::HTTP_OK);
                                }
                            }
                            else{
                                //insert into user
                                $res = AuthService::user_insert($firstname,$email,$phone,$role,$status);
                                if($res)
                                return response()->json(['success' => true,'message' => 'Admin Registration Successful','status'=>'200'], Response::HTTP_OK);
                            }
        
                        }
        
                    }
               }
               else{
                    return response()->json(['success' => true,'message' => 'Email is already exist!','status'=>'210'], Response::HTTP_OK);
               }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $request){

        $rules = [
           "email" => "required|email|",
           "password"=>"required|min:5|max:15",
            ];

           
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['info' => $validator->errors()->toJson(),'message' => 'Oops! Invalid data request.','status'=>'220'], Response::HTTP_OK);
        }
        
       if(!$token = JWTAuth::attempt($validator->validated())){
           return response()->json(['message'=>"Unauthorized User!"],401);
           
        }

        return  $this->createNewToken($token);
    }

     public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        
       return $this->createNewToken(auth()->refresh());
       //return $this->respondWithToken(auth()->refresh());
    }
    public function get_role(){
        return auth()->user()->role;
    }
    public function userProfile() {
        $user=$this->auth_user_profile();
        return response()->json($user);
    }

    public function auth_user_profile(){
        
        $authUser_email = auth()->user()->email;
        $role = $this->get_role();
        if($role=='SS' || $role =='SP')
            //fetch from customer table 
            return AuthService::customer_auth($authUser_email);
        else
             //fetch from user table 
            return AuthService::user_auth($authUser_email);
    }
    public function createNewToken($token){
          $user=$this->auth_user_profile();
          $role = $this->get_role();
        
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'role'=>$role,
            'user'=>$user,
            'expires_in'=> auth()->factory()->getTTL()*60,
        ]);
    }

    public function getmac(){
        $MacAdd = substr(exec('getmac'), 0, 17); 
        return $MacAdd;
    }

    
}

?>