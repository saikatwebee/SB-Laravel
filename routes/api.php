<?php


use Illuminate\Http\Request;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ProblemController;
use App\Http\Controllers\api\InvoiceController;
use Illuminate\Support\Facades\Route;


        Route::middleware('jwt.verify')->group(function () {
        //Routes available to all users
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['jwt.verify']);
        Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(['jwt.verify']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/profile', [AuthController::class, 'userProfile']);

        //get all state list
        Route::get('/stateList', [AuthController::class, 'stateList']);

        //get all industry list 
        Route::get('/industryList', [AuthController::class, 'industryList']);

        //get all category list
        Route::get('/categoryList', [AuthController::class, 'categoryList']);

        //get all skill list
        Route::get('/skillList', [AuthController::class, 'skillList']);

        //Admin Panel :

        //Routes available to only Admin 
        Route::middleware(['role:A'])->prefix('admin')->group(function () {
       

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

        //Project related routes of SS end

        });

        ////Routes available to only SP(Consultant)////

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
        //Award Normal Project
        Route::post('/AwardNormalProject',[ProblemController::class, 'AwardNormalProject']);
        
        //Subtract Apply Credits
        Route::post('/SubApplyCredits',[ProblemController::class, 'SubApplyCredits']);
        
        
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
            
            
         
        });

});
?>



   
