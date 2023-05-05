<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\CustomerController;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostProject;
use App\Mail\PostProjectInfo;
use App\Mail\AwardIndustryProject;
use App\Mail\ProposalMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Problem;
use App\Models\ProblemFiles;
use App\Services\CommonService;
use App\Services\ProblemService;
use App\Services\ProfileService;
use App\Services\InvoiceService;




use Illuminate\Support\Facades\DB;
use App\Models\Proposal;

class ProblemController extends Controller
{
    public function post_project(Request $request)
    {
        try {
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);

            $location =
                $request->input('city') . ',' . $request->input('state');
            $data = [
                'industries' => trim($request->input('industries')),
                'sub_cat' => trim($request->input('sub_cat')),
                'skills' => trim($request->input('skills')),
                'describe' => trim($request->input('describe')),
                'date_added' => date('Y-m-d H:i:s'),
                'location' => trim($location),
                'state' => trim($request->input('state')),
                'budget' => trim($request->input('budget')),
                'customer_id' => $customer_id,
            ];

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
                return response()->json(['info'=>$validator->errors()->toJson(),'message'=>'Oops Invalid data request!'], 400);
            } else {
                //validation on success
                $pid = ProblemService::postProject($data);
               
                if ($pid > 0) {
                    //Email sending code ...
                    //mail to industry
                    // Mail::to(auth()->user()->email)->send(
                    //     new PostProject(auth()->user()->email)
                    // );
                    //  //mail to info@solutionbuggy.com
                    // Mail::to('saikatsb10@gmail.com')->send(
                    //     new PostProjectInfo('saikatsb10@gmail.com')
                    // );

                   //Substract Problem count code ...
                    //step:1 verify plane Id
                    $verify = ProblemService::verifyPlanId($customer_id);
                    
                    if ($verify) {
                        //step:2 get the plan expiry date
                        $exp_date = ProblemService::getExpDate($customer_id);

                        $today = date('Y-m-d H:i:s');
                        //step:3 get the problem credit
                        $problem_count = ProblemService::getProblemCount($customer_id);
                      
                       
                        //step:4 check expiry date and problem credit count
                        if ($exp_date > $today) {
                            if ($problem_count > 0) {
                                //substract problem count
                                ProblemService::subProblemCount($customer_id);
                                return response()->json(['success' => true,'message' =>'Project has been Posted Successfully','status' => '200','pid'=>$pid],200);
                            } else {
                                return response()->json(['success' => true,'message' =>"Don't have enough credit! Buy Membership to make this Project live",'status' => '210','pid'=>$pid],200);
                            }
                        } else {
                            return response()->json(['success' => true,'message' =>'plan expired! Buy Membership to make this Project live','status' => '220','pid'=>$pid],200);
                        }
                    } else {
                        return response()->json(['success' => true,'message' =>'Buy Membership to make this Project live','status' => '230','pid'=>$pid],200);
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }
    //SS-list of notlive project  
    public function notLiveProject()
    {
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
        //using Eloquent Relationship
        //  method : 1
        // $problems = Problem::where(['customer_id'=>$customer_id,'action'=>0,'execution'=>0])->get();
        //$res = $problems->load(['industry','category']);

        //method : 2
        // $res =  Problem::with(['industry:id,name','category:id,name'])->where(['customer_id'=>$customer_id,'action'=>0,'execution'=>0])->get();

        //using laravel query Builder
        //method : 3
        $res = DB::table('problem')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.execution',
                'problem.date_added'
            )
            ->where(['customer_id' => $customer_id, 'action' => 0])
            ->get();

        return response()->json($res);
    }

    //SS-list of live project including normal & execution project 
    public function liveProject()
    {
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.execution',
                'problem.date_added'
            )
            ->where(['customer_id' => $customer_id, 'action' => 1])
            ->get();

        return response()->json($res);
    }

    //SS-list of completed project including normal & execution project 
    public function completedProject()
    {
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.execution',
                'problem.date_added'
            )
            ->where('customer_id',$customer_id,)
            ->whereIn('action', [2,4,5])
            ->get();

        return response()->json($res);
    }


    //SS-list of onhold project including normal & execution project 
    public function onholdProject()
    {
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.execution',
                'problem.date_added'
            )
            ->where(['customer_id' => $customer_id, 'action' => 3])
            ->get();

        return response()->json($res);
    }
    
    //SP-latest project (forwarded project)

    public function latestNormal(){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
       
        //DB::enableQueryLog();
        $res = DB::table('problem')
                 //->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
			    ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
                ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
                ->select(
                'problem.id as projectId',
                'problem.title',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
                )
           //->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 0])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','1')
            ->orderBy('problem.id','desc')
			->get();

        return response()->json($res);

    }

     //SP-latest Applied Project(Normal)

    public function appliedNormal(){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem_to_provider')
			->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.title',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','1')
            ->orderBy('problem.id','desc')
			->get();

        return response()->json($res);
    }

    //SP-latest Awarded Project(Normal)

    public function awardedNormal(){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem_to_provider')
			->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.title',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 2])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','2')
            ->orderBy('problem.id','desc')
			->get();

        return response()->json($res);
    }

    //SP-latest Not-Awarded Project(Normal)

    public function notawardedNormal(){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);

        $res = DB::table('problem_to_provider')
			->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.title',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where('problem_to_provider.customer_id',$customer_id)
            ->where('problem_to_provider.action','!=', '2')
            ->where('problem.execution', '<', '2')
            ->where('problem.action','2')
            ->orderBy('problem.id','desc')
			->get();

        return response()->json($res);
    }

 //SP-latest project (Execution)

 public function latestExecution(){
    //$customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem')
        //->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.title',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
       // ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 0])
      //  ->where('problem_to_provider.shortlist','0')
        ->where('problem.execution', '2')
        ->where('problem.action','1')
        ->orderBy('problem.id','desc')
        ->get();

    return response()->json($res);
}

 //SP-latest Applied Project(Execution)

public function appliedExecution(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem_to_provider')
        ->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.title',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
        ->where('problem_to_provider.shortlist','0')
        ->where('problem.execution', '2')
        ->where('problem.action','1')
        ->orderBy('problem.id','desc')
        ->get();

    return response()->json($res);
}

//SP-Shortlisted Project(Execution)

public function shortlistedExecution(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem_to_provider')
        ->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.title',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
        ->where('problem_to_provider.shortlist','1')
        ->where('problem.execution','2')
        ->where('problem.action','1')
        ->orderBy('problem.id','desc')
        ->get();

    return response()->json($res);
}

//SP-latest Awarded Project(Execution)

public function awardedExecution(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem_to_provider')
        ->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.title',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 2])
        ->where('problem.execution','2')
        ->where('problem.action','4')
        ->orderBy('problem.id','desc')
        ->get();

    return response()->json($res);
}

//SP-latest Not-Awarded Project(Execution)

public function notawardedExecution(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem_to_provider')
        ->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.title',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where('problem_to_provider.customer_id',$customer_id)
        ->where('problem_to_provider.action','!=', '2')
        ->where('problem.execution','2')
        ->where('problem.action','2')
        ->orderBy('problem.id','desc')
        ->get();

    return response()->json($res);
}

    //SP - Show Interest
    public function ShowInterest(Request $request)
    {
        try {
            $problem_id = trim($request->input('problem_id'));
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);
            //Check forwarded
            $chk = ProblemService::checkProblemForwarded(
                $customer_id,
                $problem_id
            );
            if ($chk) {
                $data['action'] = 5;
                $res = ProblemService::updateProblemToProvider(
                    $data,
                    $customer_id,
                    $problem_id
                );
                if ($res) {
                    return response()->json(['success' => true,'message' => 'Updated Successfully'],200);
                }
            } else {
                $data['action'] = 5;
                $data['customer_id'] = $customer_id;
                $data['problem_id'] = $problem_id;
                $data['date_added'] = date('Y-m-d H:i:s');
                $res = ProblemService::addProblemToProvider($data);
                if ($res) {
                    return response()->json(['success' => true,'message' => 'Added Successfully'],200
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    //SP - Not Relevant (action = 4)
    public function NotRelevant(Request $request)
    {
        try {
            $problem_id = trim($request->input('problem_id'));
            $data['action'] = 4;
            $data['date_added'] = date('Y-m-d H:i:s');
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //Check forwarded
                $chk = ProblemService::checkProblemForwarded($customer_id, $problem_id);
                if($chk) {
                    $res = ProblemService::updateProblemToProvider($data, $customer_id, $problem_id);
                    if ($res) {
                        return response()->json(['success' => true,'message' => 'Updated Successfull'],200);
                    }
                } else {
                    $data['customer_id'] = $customer_id;
                    $data['problem_id'] = $problem_id;
                    $res = ProblemService::addProblemToProvider($data);
                    if ($res) {
                        return response()->json(['success' => true,'message' => 'Updated Successfull'],200);
                    }
                }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    //SP - Project Apply (action = 1)
    public function ApplyProject(Request $request)
    {
        try {
            $problem_id = trim($request->input('problem_id'));
            $data['action'] = 1;
            $data['date_added'] = date('Y-m-d H:i:s');
            $data['offer'] = trim($request->input('offer'));
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);

            //step:1 plan check 
            $verify = ProblemService::verifyPlanId($customer_id);

            if($verify){
                //step:2  plan expiry date
                $exp_date = ProblemService::getExpDate($customer_id);
                $today = date('Y-m-d H:i:s');

                //step:3 get the problem credit
                $problem_count = ProblemService::getApplyCount($customer_id);

                 //step:4 check expiry date and problem credit count
                 if ($exp_date > $today) {
                    if ($problem_count > 0) {
                        //check forwared or not
                        $chk = ProblemService::checkProblemForwarded($customer_id, $problem_id);
                        if($chk) {
                            //update to problem_to_provider table
                            $res = ProblemService::updateProblemToProvider($data,$customer_id,$problem_id);
                        }
                        else{
                            //insert to problem_to_provider table
                            $data['customer_id'] = $customer_id;
                            $data['problem_id'] = $problem_id;
                            $res = ProblemService::addProblemToProvider($data);
                        }
                        //if insert or updation is successfull
                        if($res){
                            //check execution
                            $project =  ProblemService::getProject($problem_id);
                            if($project->execution < 2){
                                //substract apply credit by 1 for normal project
                                $response =  ProblemService::subApplyCount($customer_id);
                                if($response)
                                return response()->json(['success' => true,'message' => 'You have Successfully applied to this Project'],200);
                            }
                            else if($project->execution == 2){
                                return response()->json(['success' => true,'message' => 'You have Successfully applied to this Project'],200);
                            }
                            
                            
                        }
                        
                    }
                    else{
                        //error msg for apply credit
                        return response()->json( [ 'message'=>'Oops Your apply credit is not sufficient! Renew Membership to apply this Project!'], 502);

                    }
                 }
                 else{
                    //error msg for plan expiry
                    return response()->json( [ 'message'=>'Oops Your plan has been expired! Renew Membership to apply this Project!' ], 502);
                 }

            }
            else{
                //error msg for non plan taken customer
                return response()->json( [ 'message'=>'Buy Membership to apply this Project!' ], 502);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    //Award Normal Project
    public function AwardNormalProject(Request $request)
    {
        try {
            $problem_id = trim($request->input('problem_id'));
            $provider_id= trim($request->input('provider_id'));
            $data['awarded_date'] = date('Y-m-d H:i:s');
            $data['action'] = 2;
            $res = ProblemService::updateProblem($data,$problem_id);
            if ($res) {
                $datas['action'] = 2;
                $datas['date_added'] = date('Y-m-d H:i:s');
                $res1 = ProblemService::updateProblemToProvider($datas,$provider_id,$problem_id);
                if ($res1) {

                    $cid = CommonService::getCidByEmail(auth()->user()->email);

                    $email_data['pfullname'] = ProfileService::getFullName($provider_id);
                    $email_data['ifullname'] = ProfileService::getFullName($cid);
                    $email_data['iemail'] = auth()->user()->email;
                    $email_data['iphone'] = ProfileService::getPhone($cid);
                    //$email_data['proTitle'] = ProblemService::getprotitle($problem_id);

                    //sending email 
                  //  Mail::to($email_data['iemail'])->send(new AwardIndustryProject($email_data));
                    
                    return response()->json(['success' => true,'message' => 'Awarded Successfully','cid'  => $cid,'email_data' => $email_data],200);
                }
           }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    //Refer Project to Friend
    public function ReferProject(Request $request)
    {
        try {
            $email_data['problem_id'] = trim($request->input('problem_id'));
            $data['name'] = trim($request->input('name'));
            $data['email'] = trim($request->input('email'));
            $data['phone'] = trim($request->input('phone'));
            $data['date'] = date('Y-m-d H:i:s');
            $data['customer_id'] = CommonService::getCidByEmail(
                auth()->user()->email
            );
            $res = ProblemService::addReferProject($data);
            if ($res) {
                return response()->json(['success' => true,'message' => 'Updated Successfully'],200);
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

     //View Project by ID
     public function ViewProject(Request $request)
     {
         try {
             $p_id = trim($request->input('p_id'));
             return ProblemService::getProject($p_id);
             
         } catch (Exception $e) {
             return response()->json(['message' => $e->getMessage()], 502);
         }
     }

     //SS-Normal Project SP applied list
     public function SP_applied(Request $request){
        $p_id = trim($request->input('p_id'));
    
        $res = DB::table('problem_to_provider')
            ->leftJoin('customer', 'problem_to_provider.customer_id', '=', 'customer.customer_id')
            ->select(
                'problem_to_provider.*',
                'customer.firstname as SP_name',
                'customer.email as SP_email',
                'customer.phone as SP_phone'
            )
            ->where(['problem_to_provider.problem_id'=>$p_id])
            ->where('problem_to_provider.action','>', '0')
            ->where('problem_to_provider.action','<', '3')
            ->get();
    
        return response()->json($res);
    }

    //View SS or SP Profile
    public function ViewProfile(Request $request)
    {
        try {
            $c_id = trim($request->input('c_id'));
            return ProblemService::getProfile($c_id);
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    //Subtract Apply Credits
    public function SubApplyCredits(Request $request)
    {
        try {
            $c_id = trim($request->input('c_id'));
            $res =  ProblemService::subApplyCount($c_id);
            if ($res) {
                return response()->json(['success' => true,'message' => 'Updated Successfully',],200);
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function categoryDepDropdown(Request $request){
        try {
          $ind_id = $request->input('industries');
          $dropdown =  ProblemService::getCategoryDependent($ind_id);
            return response()->json($dropdown);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }
    public function skillDepDropdown(Request $request){
        try {
            $ind_id = trim($request->input('industries'));
            $dropdown =  ProblemService::getSkillDependent($ind_id);
            return response()->json($dropdown);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function getIndustryById(Request $request){
        try {
            $ind_id = trim($request->input('industries'));
            $industries =  CommonService::getIndById($ind_id);
            return response()->json($industries);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function getCategoryById(Request $request){
        try {
            $subCat = trim($request->input('sub_cat'));
            $sub_cat =  CommonService::getCatById($subCat);
            return response()->json($sub_cat);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function getSkillById(Request $request){
        try {
            $skill_id = trim($request->input('skill'));
            $skill =  CommonService::getSkillById($skill_id);
            return response()->json($skill);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function proposalInsert(Request $request){
        try{

            $data['cid']=CommonService::getCidByEmail(auth()->user()->email);
            $data['pid']=trim($request->input('ProjectId'));
            

            $data['ammount']=trim($request->input('amount'));
            $data['is_gst']=trim($request->input('isgst'));
            $data['gst']=trim($request->input('gst'));
            $data['pan']=trim($request->input('pan'));
           
           $rules = [
            "amount" => "required|numeric",
            "ProjectId" => "required",
           ];

           $validator = Validator::make($request->all(), $rules);
                 if ($validator->fails()) {
                     return response()->json(['info' => $validator->errors()->toJson(),'message' => 'Oops Invalid data request!'], 400);
                    }
                    else{
                        $uploaded_file = $this->proposalUpload($request->file('proposalDoc'), $data['cid'],$data['pid']);
                        if($uploaded_file){ 
                            $data['proposal_doc']=$uploaded_file;
                            $res=ProblemService::proposal_insert($data);
                            if($res){
                                $ftype=1;
                                //$root_url = "https://api.solutionbuggy.com/";
                                $root_url = "http://127.0.0.1:8000/";
                                $file_path = $root_url.'proposal/'.$data['cid'].'/'.$uploaded_file;

					            $fileData['fpath']=$file_path;
					            $fileData['fname']=$uploaded_file;	
                                
                                
                                if(ProblemFiles::where(['cid'=>$data['cid'],'pid'=>$data['pid'],'ftype'=>$ftype])->exists()){
                                    //update problem_files
                                    ProblemService::updateProblemFiles($fileData,$data['cid'],$data['pid'],$ftype);
                                }
                                else{
                                    //add problem_files
                                    $fileData['cid']= $data['cid'];
                                    $fileData['pid']=$data['pid'];
                                    $fileData['ftype']=$ftype;
                                    ProblemService::addProblemFiles($fileData);
                                }
                                

                                //UPDATE PROBLEM_TO_PROVIDER TABLE exe_doc 
                                    $provider['exe_doc']=1;
                                    ProblemService::updateProblemToProvider($provider,$data['cid'],$data['pid']);

                                return response()->json(['message'=>'Proposal has been Submitted Successfully'],200);

                            }
                            
                        }
                        
                    }

        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    public function sentProposalMail(Request $request){
        try{
            $email = auth()->user()->email;
            $cid = CommonService::getCidByEmail($email);
            $pid = trim($request->input('pid'));

            $email_data['cid'] = $cid;
            $email_data['pid'] = $pid;
            $email_data['fullname'] = ProfileService::getFullName($cid);

            $proposal=ProblemService::getProposal($cid,$pid);

            $email_data['amount'] = $proposal->ammount;
            $email_data['gst'] = $proposal->gst;
            $email_data['pan'] = $proposal->pan;

            Mail::to($email)->send(new ProposalMail($email_data));

        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()],502);
        }
    }




    public function getProposalCon(Request $request){
        try{
            $email = auth()->user()->email;
            $cid = CommonService::getCidByEmail($email);
            $pid = trim($request->input('pid'));

            $res =  ProblemService::getProposal($cid,$pid);
            $doc_name = $res->proposal_doc;

            //$root_url = "https://api.solutionbuggy.com/";
            $root_url = "http://127.0.0.1:8000/";
            $file_path = $root_url.'proposal/'.$cid.'/'.$doc_name;

            $res->doc_src = $file_path;
            
            return response()->json($res);
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()],502);
        }

    }

    public function updateProposalDoc(Request $request){
        try{
            $email = auth()->user()->email;
            $cid = CommonService::getCidByEmail($email);
            $pid = trim($request->input('ProjectId'));

            $data['ammount']=trim($request->input('amount'));
            $data['is_gst']=trim($request->input('isgst'));
            $data['gst']=trim($request->input('gst'));
            $data['pan']=trim($request->input('pan'));
           
           $rules = [
            "amount" => "required|numeric",
            "ProjectId" => "required",
           ];

           $validator = Validator::make($request->all(), $rules);
                 if ($validator->fails()) {
                     return response()->json(['info' => $validator->errors()->toJson(),'message' => 'Oops Invalid data request!'], 400);
                    }
                    else{
                        $uploaded_file = $this->proposalUpload($request->file('proposalDoc'), $cid,$pid);
                        if($uploaded_file){ 
                            $data['proposal_doc']=$uploaded_file;

                            $res=ProblemService::proposal_update($data,$cid,$pid);
                            if($res){
                               
                                $ftype=1;
                                //$root_url = "https://api.solutionbuggy.com/";
                                $root_url = "http://127.0.0.1:8000/";
                                $file_path = $root_url.'proposal/'.$cid.'/'.$uploaded_file;

					            $fileData['fpath']=$file_path;
					            $fileData['fname']=$uploaded_file;	
                                
                                
                                if(ProblemFiles::where(['cid'=>$cid,'pid'=>$pid,'ftype'=>$ftype])->exists()){
                                    //update problem_files
                                    ProblemService::updateProblemFiles($fileData,$cid,$pid,$ftype);
                                }

                                return response()->json(['message'=>'Proposal has been Updated Successfully'],200);

                            }

                        }
                    }


        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()],502);
        }

    }


    public function proposalUpload($file,$customer_id,$pid)
    {
        $fileName = $file->getClientOriginalName();
        
            //check proposal_doc is already exist or not

            $res =  ProblemService::getProposal($customer_id,$pid);
            if ($res != null || $res != '') {
                $proposal_doc = $res->proposal_doc;

                if (file_exists(public_path('proposal/'.$customer_id.'/'.$proposal_doc))) {
                    unlink(public_path('proposal/'.$customer_id.'/'.$proposal_doc));
                }
            }
            
            if (!is_dir(public_path('proposal/'.$customer_id))) {
    			mkdir(public_path('proposal/'.$customer_id), 0777, true);
        	}
        
        if ($file->move(public_path('proposal/'.$customer_id), $fileName)) {
            return $fileName;
        }
    }


    public function categoryBrowseSp(Request $request){
        //$customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $sub_cat = $request->input('sub_cat');

        if($sub_cat!=""){
            $res=ProblemService::category_browse_sp($sub_cat);
            return response()->json($res);
        }

    }

    public function categoryBrowseSs(Request $request){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $sub_cat = $request->input('sub_cat');

        if($sub_cat!=""){
            $res=ProblemService::category_browse_ss($sub_cat,$customer_id);
            return response()->json($res);
        }

    }


    public function industryBrowseSp(Request $request){
        //$customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $industries = $request->input('industries');

        if($industries!=""){
            $res=ProblemService::industry_browse_sp($industries);
            return response()->json($res);
        }
    }

    public function industryBrowseSs(Request $request){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $industries = $request->input('industries');

        if($industries!=""){
            $res=ProblemService::industry_browse_ss($industries,$customer_id);
            return response()->json($res);
        }
    }

    public function browseSp(Request $request){
        //$customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $sub_cat = $request->input('sub_cat');
        $industries = $request->input('industries');

        if($sub_cat!="" && $industries!="" ){
            $res=ProblemService::browse_sp($sub_cat,$industries);
            return response()->json($res);
        }
    }

    public function browseSs(Request $request){
        $customer_id = CommonService::getCidByEmail(auth()->user()->email);
        $sub_cat = $request->input('sub_cat');
        $industries = $request->input('industries');

        if($sub_cat!="" && $industries!="" ){
            $res=ProblemService::browse_ss($sub_cat,$industries,$customer_id);
            return response()->json($res);
        }
    } 

    public function getProviderDetails(Request $request){
       try {
            $cid = CommonService::getCidByEmail(auth()->user()->email);
            $pid = trim($request->input('problem_id'));
            $res=ProblemService::get_provider($pid,$cid);
            return response()->json($res);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }

    }

    public function consultantProjectStatus(Request $request){
        try{
            $msg ="";
            $cid = CommonService::getCidByEmail(auth()->user()->email);
            $pid = trim($request->input('problem_id'));
            $project_details = ProblemService::getProject($pid);
            $provider_details = ProblemService::get_provider($pid,$cid);

            if($provider_details){
                //forwarded 
            if($project_details->action==1 && $provider_details->action==0){
                $msg ="This Project has been forwarded to you.";
            }

            //Applied
            if($project_details->action==1 && $provider_details->action==1){
                $msg ="You have applied to this project.";
            }

            //Interested
            if($project_details->action==1 && $provider_details->action==5){
                $msg ="You have shown interested to this project.";
            }

            //normal Awarded 
            if($project_details->action==2 && $provider_details->action==2){
                $msg ="You have been  awarded to this project.";
            }

            //execution Awarded
            if($project_details->action==4 && $provider_details->action==2){
                $msg ="You have been  awarded to this project.";
            }

            //Not Awarded
            if($project_details->action==2 && $provider_details->action==1){
                $msg ="This project has been awarded to some other consultant!";
            }

            }
            

            return response()->json($msg);

        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 502);
        }
    }

    


}
?>







