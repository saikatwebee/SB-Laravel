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
                    Mail::to(auth()->user()->email)->send(
                        new PostProject(auth()->user()->email)
                    );
                    //mail to info@solutionbuggy.com
                    Mail::to('saikatsb10@gmail.com')->send(
                        new PostProjectInfo('saikatsb10@gmail.com')
                    );

                    //Substract Problem count code ...
                    //step:1 verify plane Id
                    $verify = ProblemService::verifyPlanId($customer_id);
                    if ($verify) {
                        //step:2 get the plan expiry date
                        $exp_date = ProblemService::getExpDate($customer_id);
                        var_dump($exp_date);
                        die();
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
            ->join('industries', 'problem.industries', '=', 'industries.id')
            ->join('category', 'problem.sub_cat', '=', 'category.id')
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

    
    //SP - Show Interest
    public function ShowInterest(Request $request)
    {
        try {
            $problem_id = trim($request->input('problem_id'));
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //Check forwarded
                $chk = ProblemService::checkProblemForwarded($customer_id, $problem_id);
                if($chk) {
                    $data['action'] = 5;
                    $res = ProblemService::updateProblemToProvider($data, $customer_id, $problem_id);
                    if ($res) {
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Update Successfull',
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
                                'message' => 'Update Successfull',
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
}
