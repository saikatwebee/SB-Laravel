<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ProfileService;
use App\Services\CommonService;

class CustomerController extends Controller
{
    //For SS only
    public function editNew(Request $request)
    {
        try {
            $data['firstname'] = trim($request->input('firstname'));
            $data['lastname'] = trim($request->input('firstname'));
            $data['email'] = trim($request->input('email'));
            $data['phone'] = trim($request->input('phone'));
            $data['companyname'] = trim($request->input('companyname'));
            $data['establishment'] = trim($request->input('establishment'));
            $data['brief_bio'] = trim($request->input('brief_bio'));
            $data['city'] = trim($request->input('city'));
            $data['state'] = trim($request->input('state'));
            $data['industries'] = trim($request->input('industries'));
            $data['date_updated'] = date('Y-m-d H:i:s');

            $rules = [
                'firstname' => 'required|min:3',
                'lastname' => 'required|min:3',
                // "email" => "required|email|unique:customer",
                'phone' => 'required|numeric|min:10',
                'industries' => 'required|numeric',
                'companyname' => 'required',
                'establishment' => 'required',
                'brief_bio' => 'required',
                'city' => 'required',
                'state' => 'required',
               // 'companylogo' => 'mimes:png,jpg,jpeg,JPG,JPEG|max:100000000',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //file upload code
                if(isset($_FILES['companylogo']['name'])){
                    if ($request->file('companylogo')->isValid()) {
                        $file = $request->file('companylogo');
                        $uploaded_file = $this->ProfileUpload($file, $customer_id);
                        if ($uploaded_file) {
                            $data['companylogo'] = $uploaded_file;
                        }
                    }
                }
               $res = ProfileService::editCustomerProfile($data, $customer_id);
                if ($res) {
                    ProfileService::addCustomerIndustry(
                        $request->input('industries'),
                        $customer_id
                    );
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

    //For SS only
    public function editExistingPersonal(Request $request)
    {
        try {
            $data['firstname'] = trim($request->input('firstname'));
            $data['lastname'] = trim($request->input('lastname'));
            $data['email'] = trim($request->input('email'));
            $data['phone'] = trim($request->input('phone'));
            $data['date_updated'] = date('Y-m-d H:i:s');

            $rules = [
                'firstname' => 'required|min:3',
                'lastname' => 'required|min:3',
                'phone' => 'required|numeric|min:10',
                'companylogo' => 'mimes:png,jpg,jpeg|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //file upload code
                if ($request->file('companylogo')->isValid()) {
                    $file = $request->file('companylogo');
                    $uploaded_file = $this->ProfileUpload($file, $customer_id);
                    if ($uploaded_file) {
                        $data['companylogo'] = $uploaded_file;
                    }
                }

                $res = ProfileService::editCustomerProfile($data, $customer_id);
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

    //For SS Only
    public function editExistingCompany(Request $request)
    {
        try {
            $data = [
                'companyname' => trim($request->input('companyname')),
                'mycurrentposition' => trim(
                    $request->input('mycurrentposition')
                ),
                'tinno' => trim($request->input('tinno')),
                'city' => trim($request->input('city')),
                'state' => trim($request->input('state')),
                // 'companywebsite'=>trim($request->input('companywebsite')),
                // 'companydesc'=>trim($request->input('companydesc')),
                'industries' => trim($request->input('industries')),
                'date_updated' => date('Y-m-d H:i:s'),
            ];

            $rules = [
                'companyname' => 'required',
                'mycurrentposition' => 'required',
                'tinno' => 'required',
                'city' => 'required',
                'state' => 'required',
                // 'companywebsite' => 'required',
                // 'companydesc' => 'required',
                'industries' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );

                $res = ProfileService::editCustomerProfile($data, $customer_id);
                if ($res) {
                    ProfileService::addCustomerIndustry(
                        $request->input('industries'),
                        $customer_id
                    );
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

    //SP Profile Edit starts
    //SP - Account tab
    public function editSPAccount(Request $request)
    {
        try {
            $data['firstname'] = trim($request->input('firstname'));
            $data['lastname'] = trim($request->input('lastname'));
            $data['email'] = trim($request->input('email'));
            $data['phone'] = trim($request->input('phone'));
            $data['dob'] = trim($request->input('dob'));
            $data['date_updated'] = date('Y-m-d H:i:s');

            $rules = [
                'firstname' => 'required|min:3',
                'lastname' => 'required|min:3',
                'phone' => 'required|numeric|min:10',
                'companylogo' => 'mimes:png,jpg,jpeg|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );
                //file upload code
                if (isset($_FILES['companylogo'])) {
                    if ($request->file('companylogo')->isValid()) {
                        $file = $request->file('companylogo');
                        $uploaded_file = $this->ProfileUpload(
                            $file,
                            $customer_id
                        );
                        if ($uploaded_file) {
                            $data['companylogo'] = $uploaded_file;
                        }
                    }
                }

                $res = ProfileService::editCustomerProfile($data, $customer_id);
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

    //SP - Personal tab
    public function editSPPersonal(Request $request)
    {
        try {
            $data['city'] = trim($request->input('city'));
            $data['state'] = trim($request->input('state'));
            $data['address'] = trim($request->input('address'));

            $rules = [
                'city' => 'required|min:3',
                'state' => 'required|min:3',
                'address' => 'required|min:3',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );

                $res = ProfileService::editCustomerProfile($data, $customer_id);
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

    //SP - Career tab
    public function editSPCareer(Request $request)
    {
        try {
            $data['companyname'] = trim($request->input('companyname'));
            $data['mycurrentposition'] = trim(
                $request->input('mycurrentposition')
            );
            $data['mytotalnoexp'] = trim($request->input('mytotalnoexp'));
            $data['linkedin'] = trim($request->input('linkedin'));
            $data['brief_bio'] = trim($request->input('brief_bio'));
            $data['date_updated'] = date('Y-m-d H:i:s');

            $rules = [
                'companyname' => 'required|min:3',
                'brief_bio' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );

                $res = ProfileService::editCustomerProfile($data, $customer_id);
                if ($res) {
                    $industry_str = $request->input('industries');
                    $industries = explode(',', $industry_str);

                    $category_str = $request->input('category');
                    $categories = explode(',', $category_str);

                    $skill_str = $request->input('skills');
                    $skills = explode(',', $skill_str);

                    //adding Customer Industries
                    foreach ($industries as $ind) {
                        ProfileService::addCustomerIndustry_Sp(
                            $ind,
                            $customer_id
                        );
                    }

                    //adding Customer Categories
                    foreach ($categories as $cat) {
                        ProfileService::addCustomerCategory_Sp(
                            $cat,
                            $customer_id
                        );
                    }

                    //adding Customer Skills
                    foreach ($skills as $skill) {
                        ProfileService::addCustomerSkill_Sp(
                            $skill,
                            $customer_id
                        );
                    }

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

    public function microEdit(Request $request)
    {
        try {
            //$id = $this->customer->getId();

            $data['mc_status'] = 0;
            $data['mc_hour'] = $request->input('mc_hour');
            $data['mc_location'] = $request->input('mc_location');
            $data['mc_cost'] = $request->input('mc_cost');

            $rules = [
                'mc_hour' => 'required',
                'mc_location' => 'required',
                'mc_cost' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            } else {
                //validation successfull
                $customer_id = CommonService::getCidByEmail(
                    auth()->user()->email
                );

                $res = ProfileService::editCustomerProfile($data,$customer_id);
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

    //get customer industries list for authenticated users 
    public function customerIndustries(){
        try{
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);
            $res = ProfileService::getCustomerIndustries($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //get customer industries list by post request of customerId
    public function customerIndustriesByCid(Request $request){
        try{
            $customer_id = $request->input('c_id');
            $res = ProfileService::getCustomerIndustries($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //get customer category list for authenticated users 
    public function customerCategory(){
      try{
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);
            $res = ProfileService::getCustomerCategory($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

     //get customer category list by post request of customerId
    public function customerCategoryByCid(Request $request){
        try{
            $customer_id = $request->input('c_id');
            $res = ProfileService::getCustomerCategory($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //get customer skill list for authenticated users 
    public function customerSkill(){
       try{
            $customer_id = CommonService::getCidByEmail(auth()->user()->email);
            $res = ProfileService::getCustomerSkill($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

     //get customer skill list by post request of customerId
    public function customerSkillByCid(Request $request){
        try{
            $customer_id = $request->input('c_id');
            $res = ProfileService::getCustomerSkill($customer_id);
            return response()->json($res);
        }
        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function ProfileUpload($file, $customer_id)
    {
        $fileName = $customer_id . '.' . $file->getClientOriginalExtension();
        //check profile pic is already exist or not
        $row = CommonService::getRowByCid($customer_id);
        $logo = $row->companylogo;
        if ($logo != null || $logo != '') {
            if (file_exists(public_path('customerProfile/' . $logo))) {
                unlink(public_path('customerProfile/' . $logo));
            }
        }
        if ($file->move(public_path('customerProfile'), $fileName)) {
            return $fileName;
        }
    }

    //Add General Referral
    public function add_referral(Request $request)
    {
        try {
            $data = [
                'name' => trim($request->input('name')),
                'email' => trim($request->input('email')),
                'phone' => trim($request->input('phone')),
                'referer_id' => trim($request->input('customer_id'))
            ];
            $res = ProfileService::AddRefferal($data);
            if ($res) {
                return response()->json(
                    [
                        'success' => true,
                        'message' =>
                            'Referral Added Successfully',
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
