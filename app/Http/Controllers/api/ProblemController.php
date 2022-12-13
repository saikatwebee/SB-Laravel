<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Mail\PostProject;
use App\Mail\PostProjectInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Problem;
use APP\Models\Customer;
use App\Services\ProfileService;
use App\Services\ProblemService;
use Illuminate\Support\Facades\Mail;



class ProblemController extends Controller
{
    public function post_project(Request $request){
        try{
            $customer_id = ProfileService::getCidByEmail(
                auth()->user()->email
            );
    
            $location =$request->input('city').','.$request->input('state');
            $data = array (
                'industries' => trim($request->input('industries')),
                'sub_cat' => trim($request->input('sub_cat')),
                'skills'=> trim($request->input('skills')),
                'describe' => trim($request->input('describe')),
                'date_added' => date('Y-m-d H:i:s'),
                'location'=> trim($location),
                'state'=>trim($request->input('state')),
                'budget' => trim($request->input('budget')),
                'customer_id' => $customer_id,
            );
    
            $rules = [
                'describe' => 'required',
                'industries' => 'required|numeric',
                'sub_cat' => 'required|numeric',
                'skills' => 'required|numeric   ',
                'state' => 'required',
                'city' => 'required',
                'budget' => 'required',
                ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation on success
                $res=ProblemService::postProject($data);
                if($res){
                   
                    //Email sending code ...
                        //mail to industry
                    Mail::to(auth()->user()->email)->send(new PostProject(auth()->user()->email));
                        //mail to info@solutionbuggy.com
                    Mail::to('saikatsb10@gmail.com')->send(new PostProjectInfo('saikatsb10@gmail.com'));    

                    //Substract Problem count code ...  
                        //step:1 verify plane Id
                    $verify=ProblemService::verifyPlanId($customer_id);
                    if($verify){
                        //step:2 get the plan expiry date
                        $exp_date = ProblemService::getExpDate($customer_id);
                        var_dump($exp_date);
                        die;
                        $today = date('Y-m-d H:i:s');
                        //step:3 get the problem credit
                        $problem_count = ProblemService::getProblemCount($customer_id);
                        //step:4 check expiry date and problem credit count 
                        if($exp_date > $today){
                            if($problem_count > 0){
                                //substract problem count 
                                ProblemService::subProblemCount($customer_id);
                                return response()->json(['success' => true,'message' => 'Project has been Posted Successfully','status' => '200',],Response::HTTP_OK); 
                            }
                            else{
                                return response()->json(['success' => true,'message' => "Don't have enough credit! Buy Membership to make this Project live",'status' => '210',],Response::HTTP_OK); 
                            }
                        }
                        else{
                            return response()->json(['success' => true,'message' => "plan expired! Buy Membership to make this Project live",'status' => '220',],Response::HTTP_OK); 
                        }
                    }
                    else{
                        return response()->json(['success' => true,'message' => "Buy Membership to make this Project live",'status' => '230',],Response::HTTP_OK); 
                        
                    }

                }

            }
    
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
