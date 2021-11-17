<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
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


Route::group(['middleware' => 'clearbit'], function () {
    Route::post('/company', [CompanyController::class, 'store']);
});

Route::post('/company/show', [CompanyController::class, 'show']);
Route::post('/company/status', [CompanyController::class, 'status']);
