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

    //Routes available to only SS(Industry)
     Route::middleware(['role:SS'])->prefix('customer')->group(function () {
        Route::get('/notlive-project', [ProblemController::class, 'notLiveProject']); 
        Route::post('/editNew',[CustomerController::class, 'editNew']); 
        Route::post('/editExistingPersonal',[CustomerController::class, 'editExistingPersonal']); 
        Route::post('/editExistingCompany',[CustomerController::class, 'editExistingCompany']); 
        //Post Project
        Route::post('/postProject',[ProblemController::class, 'post_project']); 
        
        
    });

     //Routes available to only SP(Consultant)
     Route::middleware(['role:SP'])->prefix('customer')->group(function () {
        //Profile Edit
        Route::post('/editSPAccount',[CustomerController::class, 'editSPAccount']);
        
    });

    //Routes available to only SS(Industry) and SP(Consultant)
    Route::middleware(['role:SS,SP'])->prefix('customer')->group(function () {
         
    });

});



   
