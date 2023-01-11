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
//use APP\Models\Customer;
// use APP\Models\Industry;
// use APP\Models\Category;
use App\Services\CommonService;
use App\Services\ProblemService;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation on success
                $res = ProblemService::postProject($data);
                if ($res) {
                    //Email sending code ...
                    //mail to industry
                    // Mail::to(auth()->user()->email)->send(
                    //     new PostProject(auth()->user()->email)
                    // );
                    // //mail to info@solutionbuggy.com
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
                        $problem_count = ProblemService::getProblemCount(
                            $customer_id
                        );
                        //step:4 check expiry date and problem credit count
                        if ($exp_date > $today) {
                            if ($problem_count > 0) {
                                //substract problem count
                                ProblemService::subProblemCount($customer_id);
                                return response()->json(
                                    [
                                        'success' => true,
                                        'message' =>
                                            'Project has been Posted Successfully',
                                        'status' => '200',
                                    ],
                                    Response::HTTP_OK
                                );
                            } else {
                                return response()->json(
                                    [
                                        'success' => true,
                                        'message' =>
                                            "Don't have enough credit! Buy Membership to make this Project live",
                                        'status' => '210',
                                    ],
                                    Response::HTTP_OK
                                );
                            }
                        } else {
                            return response()->json(
                                [
                                    'success' => true,
                                    'message' =>
                                        'plan expired! Buy Membership to make this Project live',
                                    'status' => '220',
                                ],
                                Response::HTTP_OK
                            );
                        }
                    } else {
                        return response()->json(
                            [
                                'success' => true,
                                'message' =>
                                    'Buy Membership to make this Project live',
                                'status' => '230',
                            ],
                            Response::HTTP_OK
                        );
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
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
        $res = DB::table('problem_to_provider')
			->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
            ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
            ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
            ->select(
                'problem.id as projectId',
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 0])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','1')
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
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','1')
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
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 2])
            ->where('problem.execution', '<', '2')
            ->where('problem.action','2')
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
                'problem.describe',
                'industries.name as industry',
                'category.name as category',
                'problem.location',
                'problem.date_added'
            )
            ->where('problem_to_provider.customer_id',$customer_id)
            ->where('problem_to_provider.action','!=', '2')
            ->where('problem.execution', '<', '2')
            ->where('problem.action','2')
			->get();

        return response()->json($res);
    }

 //SP-latest project (Execution)

 public function latestExecution(){
    $customer_id = CommonService::getCidByEmail(auth()->user()->email);

    $res = DB::table('problem_to_provider')
        ->leftJoin('problem', 'problem_to_provider.problem_id', '=', 'problem.id')
        ->leftJoin('industries', 'problem.industries', '=', 'industries.id')
        ->leftJoin('category', 'problem.sub_cat', '=', 'category.id')
        ->select(
            'problem.id as projectId',
            'problem.describe',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 0])
        ->where('problem_to_provider.shortlist','0')
        ->where('problem.execution', '2')
        ->where('problem.action','1')
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
            'problem.describe',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
        ->where('problem_to_provider.shortlist','0')
        ->where('problem.execution', '2')
        ->where('problem.action','1')
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
            'problem.describe',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 1])
        ->where('problem_to_provider.shortlist','1')
        ->where('problem.execution','2')
        ->where('problem.action','1')
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
            'problem.describe',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where(['problem_to_provider.customer_id' => $customer_id, 'problem_to_provider.action' => 2])
        ->where('problem.execution','2')
        ->where('problem.action','2')
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
            'problem.describe',
            'industries.name as industry',
            'category.name as category',
            'problem.location',
            'problem.date_added'
        )
        ->where('problem_to_provider.customer_id',$customer_id)
        ->where('problem_to_provider.action','!=', '2')
        ->where('problem.execution','2')
        ->where('problem.action','2')
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
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'Updated Successfully',
                            'status' => '200',
                        ],
                        Response::HTTP_OK
                    );
                }
            } else {
                $data['action'] = 5;
                $data['customer_id'] = $customer_id;
                $data['problem_id'] = $problem_id;
                $data['date_added'] = date('Y-m-d H:i:s');
                $res = ProblemService::addProblemToProvider($data);
                if ($res) {
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'Added Successfully',
                            'status' => '200',
                        ],
                        Response::HTTP_OK
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
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
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Updated Successfull',
                                'status' => '200',
                            ],
                            Response::HTTP_OK
                        );
                    }
                } else {
                    $data['customer_id'] = $customer_id;
                    $data['problem_id'] = $problem_id;
                    $res = ProblemService::addProblemToProvider($data);
                    if ($res) {
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Updated Successfull',
                                'status' => '200',
                            ],
                            Response::HTTP_OK
                        );
                    }
                }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
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
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //Check forwarded
                $chk = ProblemService::checkProblemForwarded($customer_id, $problem_id);
                if($chk) {
                    $res = ProblemService::updateProblemToProvider($data, $customer_id, $problem_id);
                    if ($res) {
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Updated Successfully',
                                'status' => '200',
                            ],
                            Response::HTTP_OK
                        );
                    }
                } else {
                    $data['customer_id'] = $customer_id;
                    $data['problem_id'] = $problem_id;
                    $res = ProblemService::addProblemToProvider($data);
                    if ($res) {
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Updated Successfully',
                                'status' => '200',
                            ],
                            Response::HTTP_OK
                        );
                    }
                }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
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
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'Updated Successfully',
                            'status' => '200',
                        ],
                        Response::HTTP_OK
                    );
                }
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //Refer Project to Friend
    public function ReferProject(Request $request)
    {
        try {
            $data['problem_id'] = trim($request->input('problem_id'));
            $data['name'] = trim($request->input('name'));
            $data['email'] = trim($request->input('email'));
            $data['phone'] = trim($request->input('phone'));
            $data['date'] = date('Y-m-d H:i:s');
            $data['customer_id'] = CommonService::getCidByEmail(
                auth()->user()->email
            );
            $res = ProblemService::addReferProject($data);
            if ($res) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Updated Successfully',
                        'status' => '200',
                    ],
                    Response::HTTP_OK
                );
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

     //View Project by ID
     public function ViewProject(Request $request)
     {
         try {
             $p_id = trim($request->input('p_id'));
             return ProblemService::getProject($p_id);
             
         } catch (Exception $e) {
             return response()->json(['message' => $e->getMessage()], 404);
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
            ->where('problem_to_provider.problem_id',$p_id)
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
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //Subtract Apply Credits
    public function SubApplyCredits(Request $request)
    {
        try {
            $c_id = trim($request->input('c_id'));
            $res =  ProblemService::subApplyCount($c_id);
            if ($res) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Updated Successfully',
                        'status' => '200',
                    ],
                    Response::HTTP_OK
                );
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function categoryDepDropdown(Request $request){
        try {
          $ind_id = $request->input('industries');
          $dropdown =  ProblemService::getCategoryDependent($ind_id);
            return response()->json($dropdown);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    public function skillDepDropdown(Request $request){
        try {
            $ind_id = trim($request->input('industries'));
            $dropdown =  ProblemService::getSkillDependent($ind_id);
            return response()->json($dropdown);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getIndustryById(Request $request){
        try {
            $ind_id = trim($request->input('industries'));
            $industries =  CommonService::getIndById($ind_id);
            return response()->json($industries);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getCategoryById(Request $request){
        try {
            $subCat = trim($request->input('sub_cat'));
            $sub_cat =  CommonService::getCatById($subCat);
            return response()->json($sub_cat);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getSkillById(Request $request){
        try {
            $skill_id = trim($request->input('skill'));
            $skill =  CommonService::getSkillById($skill_id);
            return response()->json($skill);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
