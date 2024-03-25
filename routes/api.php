<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DescriptiveQuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MultipleChoiceQuestionController;
use App\Http\Controllers\UserController;
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

Route::post('register',[AuthController::class ,'register']);
Route::post('register-verify',[AuthController::class ,'registerVerify']);
Route::post('resend-verification-code',[AuthController::class ,'resendVerificationCodeToUser']);

Route::post('send-password-reset-code', [AuthController::class , 'sendPasswordResetCode']);
Route::patch('reset-password',[AuthController::class ,'confirmPasswordResetCode']);
Route::post('resend-password-reset-code', [AuthController::class , 'resendPasswordResetCode']);


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AccessTokenController::class ,'login']);
    Route::post('logout', [AccessTokenController::class ,'logout']);
    Route::post('refresh', [AccessTokenController::class ,'refresh']);
    Route::post('me', [AccessTokenController::class ,'me']);
});


/**
 * روت های کاربر
 */
Route::group(["middleware" => ["auth:api"]],function (){

    Route::put('change-password',[UserController::class ,'changePassword']);


    Route::group(['prefix' => 'user'], function () {

        Route::post('/profile',[UserController::class ,'profile']);

    });

});

/**
 * روت های سربرگ آزمون
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'exam'], function () {

    Route::post('/addExam',[ExamController::class ,'create']);
    Route::post('/addQuestion',[ExamController::class ,'addQuestionsToExam']);
    Route::get('/showExam', [ExamController::class, 'showExamDetails']);
    Route::post('/WordDocument', [ExamController::class, 'generateWordDocument']);

    Route::put('change-password',[UserController::class ,'changePassword']);



});

/**
 * روت های سوالات 4 گزینه ای
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'question'], function () {

    Route::post('/multiple-choice',[MultipleChoiceQuestionController::class ,'create']);
    Route::get('/ListTest',[MultipleChoiceQuestionController::class ,'listMultipleChoice']);


});
/**
 * روت های سوالات تشریحی
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'question'], function () {

    Route::post('/descriptive',[DescriptiveQuestionController::class ,'create']);
    Route::get('/ListDescriptive',[DescriptiveQuestionController::class ,'ListDescriptive']);


});
