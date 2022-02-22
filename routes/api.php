<?php

use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user/register', [UserController::class,'register']);
Route::post('user/login', [UserController::class,'login']);
Route::post('loan/apply', [LoanController::class,'apply'])->middleware('auth:sanctum');
Route::get('loan', [LoanController::class,'loan'])->middleware('auth:sanctum');
Route::post('loan/payment', [LoanController::class,'payment'])->middleware('auth:sanctum');
Route::post('loan/status', [LoanController::class,'status']);
Route::get('loan/plans', [LoanController::class,'loans']);
Route::get('loan/types', [LoanController::class,'types']);
