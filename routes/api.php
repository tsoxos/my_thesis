<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\questionnaireController;

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
Route::get('/active-questionnaire', [questionnaireController::class, 'getActiveQuestionnaire']);
Route::post('/new-answer', [questionnaireController::class, 'postAnswer']);
Route::post('/new-token', [FirebaseController::class, 'postToken']);
//

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('sign_in', [authController::class, 'sign_in'])->name('auth.login');
