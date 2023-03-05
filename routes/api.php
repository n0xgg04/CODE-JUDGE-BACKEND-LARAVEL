<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/auth/register', 'App\Http\Controllers\AuthController@register');
Route::middleware('jwt-verify')->group(function () {
    Route::get('/contest/list', [App\Http\Controllers\GetContestList::class, 'getAll']);
    Route::get('/problem/{id}', [App\Http\Controllers\getProblemInfo::class, 'getAll']);
    Route::get('/code/submissions/all/{userId}', [App\Http\Controllers\getSubmissionInfo::class, 'getAll']);
    Route::post('/code/submit/{id}', [App\Http\Controllers\CodeRunnerController::class, 'judgeSubmission']);
});

Route::any('/done',function(){
    file_put_contents('ok.txt','ok');
});
