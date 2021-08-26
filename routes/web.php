<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\authController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\questionnaireController;
use App\Http\Controllers\userController;
use App\Http\Controllers\FirebaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [authController::class, 'index'])->name('login');
Route::post('sign_in', [authController::class, 'sign_in'])->name('auth.login');
Route::get('sign_in', [authController::class, 'getSign_in']);
Route::get('/', [homeController::class, 'index']);
Route::get('/questionnaire/{id}', [questionnaireController::class, 'getQuestionnaire']);

Route::middleware(['auth'])->group(function(){
    Route::get('/logout', [authController::class, 'logout'])->name('auth.logout');
    Route::get('/questionnaires', [questionnaireController::class, 'index'])->name('questionnaires');
    Route::get('/new-questionnaire', [questionnaireController::class, 'addNew']);
    Route::get('/edit-questionnaire/{id}', [questionnaireController::class, 'edit']);
    Route::get('/profile', [userController::class, 'getProfile'])->name('users.profile');
    Route::Post('/edit-user', [userController::class, 'postProfile'])->name('users.edit');
    Route::Post('/edit-password', [userController::class, 'postPassword'])->name('users.password');
    Route::post('/questionnaire-edit', [questionnaireController::class, 'postEdit'])->name('post.edit');
    Route::post('/add-questionnaire', [questionnaireController::class, 'postNew']);
    Route::post('/questionnaire-status', [questionnaireController::class, 'postActive'])->name('questionnaire.status');
    Route::post('/questionnaire-display', [questionnaireController::class, 'postDisplay'])->name('questionnaire.display');
    Route::post('/questionnaire-delete', [questionnaireController::class, 'postDelete'])->name('questionnaire.delete');
});

Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function(){
    Route::get('/users', [userController::class, 'index'])->name('users');
    Route::get('/new-user', [userController::class, 'getNew']);
    Route::post('/post-user', [userController::class, 'register'])->name('register.user');
    Route::post('/update-role', [userController::class, 'updateRole']);
    Route::post('/user-delete', [userController::class, 'postDelete'])->name('user.delete');
});



