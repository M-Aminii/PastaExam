<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MultipleChoiceQuestionController;
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
Route::post('/test/{id}',[ExamController::class ,'show']);


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AccessTokenController::class ,'login']);
    Route::post('logout', [AccessTokenController::class ,'logout']);
    Route::post('refresh', [AccessTokenController::class ,'refresh']);
    Route::post('me', [AccessTokenController::class ,'me']);
});

/**
 * روت های سربرگ آزمون
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'exam'], function () {

    Route::post('/addExam',[ExamController::class ,'create']);
    Route::post('/addTest',[ExamController::class ,'addQuestionsToExam']);
    Route::get('/showExam', [ExamController::class, 'showExamDetails'])->name('ShowExam');
    Route::post('/WordDocument', [ExamController::class, 'generateWordDocument']);



});

/**
 * روت های سوالات 4 گزینه ای
 */
Route::group(["middleware" => ["auth:api"],'prefix' => 'question'], function () {

    Route::post('/',[MultipleChoiceQuestionController::class ,'create']);
    Route::get('/ListTest',[MultipleChoiceQuestionController::class ,'list']);


});
