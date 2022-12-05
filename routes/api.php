<?php


use Illuminate\Http\Request;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
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
        Route::get('/customer-datatable', [CustomerController::class, 'getCustomerDemo']); 
    });

     //Routes available to only SP(Consultant)
     Route::middleware(['role:SP'])->prefix('customer')->group(function () {
        
    });

    //Routes available to only SS(Industry) and SP(Consultant)
    Route::middleware(['role:SS,SP'])->prefix('customer')->group(function () {
         
    });

});



   
