<?php


use Illuminate\Http\Request;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ProblemController;
use App\Http\Controllers\api\InvoiceController;
use App\Http\Controllers\api\PlaneController;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\OnboardingController;
use Illuminate\Support\Facades\Route;


        Route::middleware('jwt.verify')->group(function () {
        //Routes available to all users
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['jwt.verify']);
        Route::post('/loginWithOtp', [AuthController::class, 'loginWithOtp'])->withoutMiddleware(['jwt.verify']);
        Route::post('/checkOtp', [AuthController::class, 'checkOtp'])->withoutMiddleware(['jwt.verify']);
        
        Route::post('/adminLogin', [AuthController::class, 'admin_login'])->withoutMiddleware(['jwt.verify']);
        Route::post('/adminTokenValidation',[AuthController::class, 'adminTokenValidation'])->withoutMiddleware(['jwt.verify']);

        //success page
        Route::post('/paymentSuccess', [PlaneController::class, 'paymentSuccess'])->withoutMiddleware(['jwt.verify']);
        Route::get('/api_check', [PlaneController::class, 'api_check'])->withoutMiddleware(['jwt.verify']);
       

        //falied page
        Route::post('/paymentFailed', [PlaneController::class, 'paymentFailed'])->withoutMiddleware(['jwt.verify']);
     
       
        Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(['jwt.verify']);
        Route::post('/adsRegister', [AuthController::class, 'adsRegister'])->withoutMiddleware(['jwt.verify']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/profile', [AuthController::class, 'userProfile']);
        Route::post('/changePassword', [AuthController::class, 'changePassword']);

        //get all state list
        Route::get('/stateList', [AuthController::class, 'stateList']);

        //get all industry list 
        Route::get('/industryList', [AuthController::class, 'industryList']);

        //get all category list
        Route::get('/categoryList', [AuthController::class, 'categoryList']);

        //get all skill list
        Route::get('/skillList', [AuthController::class, 'skillList']);

        //get industry name by id
        Route::post('/getIndustryById', [ProblemController::class, 'getIndustryById']);

         //get category name by id
         Route::post('/getCategoryById', [ProblemController::class, 'getCategoryById']);

        //get skill name by id
        Route::post('/getSkillById', [ProblemController::class, 'getSkillById']);
        

        //Admin Panel :

        //Routes available to only Admin 
        Route::middleware(['role:A'])->prefix('admin')->group(function () {
                Route::get('/dashboard_section1', [AdminController::class, 'dashboard_section1']);
                
        });

        //Routes available to Admin and Project 
        Route::middleware(['role:A|P'])->prefix('admin')->group(function () {
        
        });


        //Routes available to Admin,Sales-Admin and Project 
        Route::middleware(['role:A|S|P'])->prefix('admin')->group(function () {
        
        });


        //Routes available to Admin,Sales-Admin,Project and Sales-Executive
        Route::middleware(['role:A|S|P|U'])->prefix('admin')->group(function () {
            
        });


        //Customer Panel :

        ////Routes available to only SS(Industry)////

        Route::middleware(['role:SS'])->prefix('customer')->group(function () {

        //Profile Edit start
        Route::post('/editNew',[CustomerController::class, 'editNew']); 
        Route::post('/editExistingPersonal',[CustomerController::class, 'editExistingPersonal']); 
        Route::post('/editExistingCompany',[CustomerController::class, 'editExistingCompany']); 
        //Profile Edit end

        //Project related routes of SS start
        Route::post('/postProject',[ProblemController::class, 'post_project']); 
        Route::get('/notlive-project', [ProblemController::class, 'notLiveProject']);
        Route::get('/live-project', [ProblemController::class, 'liveProject']);
        Route::get('/completed-project', [ProblemController::class, 'completedProject']);
        Route::get('/onhold-project', [ProblemController::class, 'onholdProject']);

        //SP details applied to the project
        Route::post('/SP_applied',[ProblemController::class, 'SP_applied']); 

        //Award Normal Project
        Route::post('/AwardNormalProject',[ProblemController::class, 'AwardNormalProject']);
        
        Route::post('/categoryDepDropdown',[ProblemController::class, 'categoryDepDropdown']);
        Route::post('/skillDepDropdown',[ProblemController::class, 'skillDepDropdown']);

        //Project related routes of SS end
        //insert and update in onboarding second form(requirement submisssion)
        Route::post('/reqSub',[OnboardingController::class, 'req_sub']);

        //industry dashboard api data
        Route::get('/getIndustryDashboard',[CustomerController::class, 'getIndustryDashboard']);

        Route::post('/categoryBrowseSs',[ProblemController::class, 'categoryBrowseSs']);
        Route::post('/industryBrowseSs',[ProblemController::class, 'industryBrowseSs']);
        Route::post('/browseSs',[ProblemController::class, 'browseSs']);
        
        
        });

        //Routes available to only SP(Consultant)//

        Route::middleware(['role:SP'])->prefix('customer')->group(function () {
      
        //Profile Edit start
        Route::post('/editSPAccount',[CustomerController::class, 'editSPAccount']);
        Route::post('/editSPPersonal',[CustomerController::class, 'editSPPersonal']);
        Route::post('/editSPCareer',[CustomerController::class, 'editSPCareer']);
        Route::post('/microEdit',[CustomerController::class, 'microEdit']);
        //Profile Edit end

        //SP Normal Project
        //Latest Project  
        Route::get('/latestNormal-project', [ProblemController::class, 'latestNormal']);
        //Applied Project
        Route::get('/appliedNormal-project', [ProblemController::class, 'appliedNormal']);
        //Awarded Project
        Route::get('/awardedNormal-project', [ProblemController::class, 'awardedNormal']);
        //Not Awarded Project
        Route::get('/notawardedNormal-project', [ProblemController::class, 'notawardedNormal']);

        //SP Execution Project
        //Latest Project  
        Route::get('/latestExecution-project', [ProblemController::class, 'latestExecution']);
        //Applied Project
        Route::get('/appliedExecution-project', [ProblemController::class, 'appliedExecution']);
        //Shortlisted Project
        Route::get('/shortlistedExecution-project', [ProblemController::class, 'shortlistedExecution']);
        //Awarded Project
        Route::get('/awardedExecution-project', [ProblemController::class, 'awardedExecution']);
        //Not Awarded Project
        Route::get('/notawardedExecution-project', [ProblemController::class, 'notawardedExecution']);
        

        //Show Interest
        Route::post('/ShowInterest',[ProblemController::class, 'ShowInterest']);
        //Not Relevant
        Route::post('/NotRelevant',[ProblemController::class, 'NotRelevant']);
        //Apply Project
        Route::post('/ApplyProject',[ProblemController::class, 'ApplyProject']);
        //Refer Project
        Route::post('/ReferProject',[ProblemController::class, 'ReferProject']);
        
        //Subtract Apply Credits
        Route::post('/SubApplyCredits',[ProblemController::class, 'SubApplyCredits']);
        
        //proposal insert
        Route::post('/proposalInsert', [ProblemController::class, 'proposalInsert']);

        //consultant dashboard api data
        Route::get('/getConsultantDashboard',[CustomerController::class, 'getConsultantDashboard']);

        //sp browsw project
        Route::post('/categoryBrowseSp', [ProblemController::class, 'categoryBrowseSp']);
        Route::post('/industryBrowseSp', [ProblemController::class, 'industryBrowseSp']);
        Route::post('/browseSp', [ProblemController::class, 'browseSp']);

        Route::post('/OnboardingPreference', [OnboardingController::class, 'OnboardingPreference']);
        Route::post('/updateOnboardingExperience', [OnboardingController::class, 'updateOnboardingExperience']);
        
        

        });





        //Routes available to only SS(Industry) and SP(Consultant)
       
        Route::middleware(['role:SS|SP'])->prefix('customer')->group(function () {

            //Get Invoice List
            Route::get('/invoiceList', [InvoiceController::class, 'invoiceList']);
            //Get Invoice by ID with details
            Route::post('/ViewInvoice', [InvoiceController::class, 'ViewInvoice']);
            //Add New Invoice
            Route::post('/add_invoice', [InvoiceController::class, 'add_invoice']);
            //Update GST Details
            Route::post('/update_gst', [InvoiceController::class, 'update_gst']);

            //Get Plan Details
            Route::get('/ViewPlan', [InvoiceController::class, 'ViewPlan']);
            //Add or Update Plan Details
            Route::post('/UpdatePlan', [InvoiceController::class, 'UpdatePlan']);

            //Project Details
            Route::post('/ViewProject', [ProblemController::class, 'ViewProject']);

            //View Profile
            Route::post('/ViewProfile', [ProblemController::class, 'ViewProfile']);
                  
            //Add referral
            Route::post('/add_referral', [CustomerController::class, 'add_referral']);
            
              //get customer industry list for current authenticated user
              Route::get('/customerIndustries', [CustomerController::class, 'customerIndustries']);

              //get customer category list for current authenticated user
              Route::get('/customerCategory', [CustomerController::class, 'customerCategory']);
  
              //get customer skill list for current authenticated user
              Route::get('/customerSkill', [CustomerController::class, 'customerSkill']);


              //get customer industry list for current authenticated user
              Route::post('/customerIndustriesByCid', [CustomerController::class, 'customerIndustriesByCid']);

              //get customer category list for current authenticated user
              Route::post('/customerCategoryByCid', [CustomerController::class, 'customerCategoryByCid']);
  
              //get customer skill list for current authenticated user
              Route::post('/customerSkillByCid', [CustomerController::class, 'customerSkillByCid']);




              //get plan details from subscriberplane table
              Route::post('/getPlan', [InvoiceController::class, 'getPlan']);
              
              // plane checkout details
              Route::post('/getSubcriberPlane', [PlaneController::class, 'getSubcriberPlane']);
              
              //bug report
              Route::post('/bugReport', [CustomerController::class, 'bugReport']);

              //referral insert
              Route::post('/referalInsert',[CustomerController::class, 'referalInsert']);

               //Payment page visitor notification
               Route::get('/membershipVisitorNotify',[PlaneController::class, 'membershipVisitorNotify']);

                //get onboarding step
                Route::get('/getOnboardingstep',[OnboardingController::class, 'getOnboardingstep']);

                //get onboarding otp
                Route::get('/sentOtp',[OnboardingController::class, 'sentOtp']);
                
                //otp verification for onboarding step 1
                Route::post('/otpVerification',[OnboardingController::class, 'otpVerification']);
                

               

         
        });
         
});


