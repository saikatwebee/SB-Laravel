<?php


use Illuminate\Http\Request;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ProblemController;
use Illuminate\Support\Facades\Route;


        Route::middleware('jwt.verify')->group(function () {
        //Routes available to all users
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['jwt.verify']);
        Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(['jwt.verify']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/profile', [AuthController::class, 'userProfile']);  

        //Admin Panel :

        //Routes available to only Admin 
        Route::middleware(['role:A'])->prefix('admin')->group(function () {
       

        });

        //Routes available to Admin and Project 
        Route::middleware(['role:A,P'])->prefix('admin')->group(function () {
        
        });


        //Routes available to Admin,Sales-Admin and Project 
        Route::middleware(['role:A,S,P'])->prefix('admin')->group(function () {
        
        });


        //Routes available to Admin,Sales-Admin,Project and Sales-Executive
        Route::middleware(['role:A,S,P,U'])->prefix('admin')->group(function () {
        
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

       
        //SP: latest Project Normal 
        Route::get('/latestNormal-project', [ProblemController::class, 'latestNormal']);
        //SP: Applied Project Normal
        Route::get('/appliedNormal-project', [ProblemController::class, 'appliedNormal']);
        //SP: Awarded Project Normal
        Route::get('/awardedNormal-project', [ProblemController::class, 'awardedNormal']);
        //SP: Awarded Project Normal
        Route::get('/notawardedNormal-project', [ProblemController::class, 'notawardedNormal']);
        

        //Show Interest
        Route::post('/ShowInterest',[ProblemController::class, 'ShowInterest']);
        
        
        });



        //Routes available to only SS(Industry) and SP(Consultant)
       
        Route::middleware(['role:SS,SP'])->prefix('customer')->group(function () {
         
        });

});



   
