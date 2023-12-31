<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('test/get',function(Request $request){
    return response()->json(['data'=>$request->all(),'message'=>'Request Completed'],200);
});

Route::get('verify/email',[\App\Http\Controllers\Auth\VerifyEmailController::class,'mailVerify']);
Route::post('forgot/password',[\App\Http\Controllers\Auth\ForgotPasswordController::class,'forgotPassword']);
Route::post('reset/password',[\App\Http\Controllers\Auth\ForgotPasswordController::class,'resetPassword']);


Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[App\Http\Controllers\UserController::class,'profile']);
    Route::post('profile',[App\Http\Controllers\UserController::class,'profileUpdate']);
    Route::put('profile/address',[App\Http\Controllers\UserController::class,'addressUpdate']);
    Route::put('profile/candidate',[App\Http\Controllers\UserController::class,'candidateInfoUpdate']);

    Route::prefix('candidate')->group(function(){
        Route::get('applications',[CandidateController::class,'applicationListByCandidate']);
        Route::post('application/update/{id}',[CandidateController::class,'update']);
        Route::post('application/{jobId}',[CandidateController::class,'store']);
        Route::delete('application/{id}',[CandidateController::class,'destroy']);
    });
});

Route::post('signup',[App\Http\Controllers\Auth\AuthController::class,'registration']);
Route::post('login',[App\Http\Controllers\Auth\AuthController::class,'login']);


Route::prefix('admin')->group(function(){

    Route::prefix('user')->group(function(){
        Route::get('all',[\App\Http\Controllers\Admin\UserController::class,'index']);
    });

    Route::prefix('company')->group(function(){
        Route::get('all',[\App\Http\Controllers\Admin\CompanyController::class,'index']);
    });

    Route::get('jobs',[EmployerController::class,'jobListByEmployer']);
    Route::post('job',[EmployerController::class,'jobCreate']);
    Route::put('job/{id}',[EmployerController::class,'jobUpdate']);
    Route::delete('job/{id}',[EmployerController::class,'jobDelete']);
});

Route::middleware('auth:sanctum')-> prefix('employer')->group(function(){

    Route::prefix('company')->group(function(){
        Route::get('details',[\App\Http\Controllers\CompanyController::class,'index']);
        Route::post('details',[\App\Http\Controllers\CompanyController::class,'storeOrUpdate']);
    });
    Route::prefix('job')->group(function(){
        Route::get('all',[EmployerController::class,'jobListByEmployer']);
        Route::post('create',[EmployerController::class,'jobCreate']);
        Route::put('update/{id}',[EmployerController::class,'jobUpdate']);
        Route::delete('delete/{id}',[EmployerController::class,'jobDelete']);

    });

    Route::prefix('application')->group(function(){
        Route::get('all',[ApplicationController::class,'index']);
        Route::get('show/{applicationId}',[ApplicationController::class,'show']);
        Route::put('update/{applicationId}',[ApplicationController::class,'update']);

    });
});

Route::get('jobs',[JobListingController::class,'index']);

Route::post('file/test',[TestController::class,'fileUpload']);
Route::get('file/test',[TestController::class,'fileRetrieve']);




