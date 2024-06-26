<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DescriptiveQuestionController;
use App\Http\Controllers\ExamHeaderController;
use App\Http\Controllers\MultipleChoiceQuestionController;
use App\Http\Controllers\QuestionController;
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
    Route::post('/',[ExamHeaderController::class ,'create']);
    Route::delete('/',[ExamHeaderController::class ,'delete']);
    Route::post('/WordDocument', [ExamHeaderController::class, 'generateWordDocument']);
});
/**
 * روت های سوالات آزمون
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'exam'], function () {
    Route::post('/addQuestion',[QuestionController::class ,'addQuestionsToExam']);
    Route::get('/showQuestion', [QuestionController::class, 'showExamDetails']);

});



/**
 * روت های سوالات 4 گزینه ای
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'question'], function () {

    Route::post('/multiple-choice',[MultipleChoiceQuestionController::class ,'create']);
    Route::get('/multiple-choice',[MultipleChoiceQuestionController::class ,'listMultipleChoice']);
    Route::delete('/multiple-choice/{question}',[MultipleChoiceQuestionController::class ,'delete']);
    Route::put('/multiple-choice/{question}',[MultipleChoiceQuestionController::class ,'update']);


});

/**
 * روت های سوالات تشریحی
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'question'], function () {

    Route::post('/descriptive',[DescriptiveQuestionController::class ,'create']);
    Route::get('/descriptive',[DescriptiveQuestionController::class ,'ListDescriptive']);
    Route::delete('/descriptive/{question}',[DescriptiveQuestionController::class ,'delete']);
    Route::put('/descriptive/{question}',[DescriptiveQuestionController::class ,'update']);


});



/*
Route::get('/books', function (Request $request) {
    $books = \App\Models\Textbook::all();
    return response()->json($books);
});*/
