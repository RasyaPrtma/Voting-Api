<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AuthMiddleware;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['prefix' => 'v1'], function(){
        Route::get('vote',[VoteController::class,'resultVote']);

        Route::post('users',[UserController::class,'store']);
        Route::get('users',[UserController::class,'index']);
        Route::put('users/{id}',[UserController::class,'update']);
        Route::delete('users/{id}',[UserController::class,'destroy']);

    Route::group(['prefix' => 'admin/kandidat'],function(){
        Route::post('candidate',[CandidatesController::class,'store']);
        Route::get('candidate',[CandidatesController::class,'index']);
        Route::put('candidate/{id}',[CandidatesController::class,'update']);
        Route::delete('candidate/{id}',[CandidatesController::class,'destroy']);
    });

    Route::post('login',[AuthenticationController::class,'Login']);
    Route::middleware([AuthMiddleware::class])->group(function(){
        Route::post('vote',[VoteController::class,'Voting']);
        Route::post('logout',[AuthenticationController::class,'Logout']);
    });

});
