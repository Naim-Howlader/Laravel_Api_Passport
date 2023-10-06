<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::get('/user/show-user/{id?}', [UserController::class, 'showUser']);
Route::post('/user/add-user', [UserController::class, 'addUser']);
Route::post('/user/add-multiple-user', [UserController::class, 'addMultipleUser']);
Route::put('/user/update-user/{id}', [UserController::class, 'updateUser']);
Route::patch('/user/update-single-record/{id}', [UserController::class, 'updateSingleRecord']);
Route::delete('/user/delete-user/{id}', [UserController::class, 'deleteUser']);
Route::delete('/user/delete-multiple-user/{ids}', [UserController::class, 'deleteMultipleUser']);
Route::delete('/user/delete-user-json', [UserController::class, 'deleteUserJson']);
Route::delete('/user/delete-multiple-user-json', [UserController::class, 'deleteMultipleUserJson']);
//Laravel Passport
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user/user-info/{id}', [UserController::class, 'userInfo']);
});
