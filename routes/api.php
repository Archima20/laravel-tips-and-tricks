<?php

use App\Http\Controllers\AuthenticationContorller;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ExportController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-email', [EmailController::class, 'send']);

Route::post('/upload-image', [Controller::class, 'uploadImage']);


Route::post('/create-user', [AuthenticationContorller::class , 'createUser']);
Route::post('/forgot-password', [AuthenticationContorller::class , 'forgotPassword']);
Route::post('/change-password', [AuthenticationContorller::class , 'changePassword']);


Route::get('/export-excel', [ExportController::class , 'excel']);
Route::get('/export-vcf', [ExportController::class , 'vcf']);
// Route::get('/export-pdf', [ExportController::class, 'pdf']);
Route::get('create-pdf-file', [ExportController::class, 'pdf']);