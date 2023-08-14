<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobListingController;
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


Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[App\Http\Controllers\UserController::class,'profile']);
    Route::prefix('candidate')->group(function(){
        Route::get('applications',[CandidateController::class,'applicationListByCandidate']);
        Route::post('application/{jobId}',[CandidateController::class,'store']);
        Route::put('application/{id}',[CandidateController::class,'update']);
        Route::delete('application/{id}',[CandidateController::class,'destroy']);
    });
});

Route::post('signup',[App\Http\Controllers\Auth\AuthController::class,'registration']);
Route::post('login',[App\Http\Controllers\Auth\AuthController::class,'login']);


Route::prefix('employer')->group(function(){
    Route::get('jobs',[EmployerController::class,'jobListByEmployer']);
    Route::post('job',[EmployerController::class,'jobCreate']);
    Route::put('job/{id}',[EmployerController::class,'jobUpdate']);
    Route::delete('job/{id}',[EmployerController::class,'jobDelete']);
});

Route::get('jobs',[JobListingController::class,'index']);




