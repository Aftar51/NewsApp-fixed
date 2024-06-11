<?php

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [App\Http\Controllers\API\AuthContoller::class, 'logout']);
    Route::post('/updatePassword', [\App\Http\Controllers\API\AuthContoller::class, 'updatePassword']);
    Route::post('/storeProfile', [\App\Http\Controllers\API\AuthContoller::class, 'storeProfile']);
    Route::post('/updateProfile', [\App\Http\Controllers\API\AuthContoller::class, 'updateProfile']);
});


//Route for admin
Route::group(['middleware'=> ['auth:sanctum', 'admin']], function(){
    // Route Category
    Route::post('/category/create', [App\Http\Controllers\API\CategoryController::class, 'store']);
    Route::post('/category/update/{id}',[App\Http\Controllers\API\CategoryController::class, 'update']);
    Route::delete('/category/destroy/{id}',[App\Http\Controllers\API\CategoryController::class, 'destroy']);

    // Route News
    Route::post('/news/create',[App\Http\Controllers\API\NewsController::class, 'store']);
    Route::delete('/news/destroy/{id}',[App\Http\Controllers\API\NewsController::class, 'destroy']);
    Route::post('/news/update/{id}', [App\Http\Controllers\API\NewsController::class, 'update']);
});

Route::post('/login', [App\Http\Controllers\API\AuthContoller::class,'login']);
Route::post('/register', [App\Http\Controllers\API\AuthContoller::class,'register']);
Route::get('/allUsers', [App\Http\Controllers\API\AuthContoller::class, 'allUsers']);

//get data news
Route::get('/allNews', [App\Http\Controllers\API\NewsController::class, 'index']);
// get data news by id
Route::get('/news/{id}', [App\Http\Controllers\API\NewsController::class, 'show']);

//get data category
Route::get('/allCategory', [App\Http\Controllers\API\CategoryController::class, 'index']);
// get data category by id
Route::get('/category/{id}', [App\Http\Controllers\API\CategoryController::class, 'show']);

//get carousel from news
Route::get('/carousel', [App\Http\Controllers\API\FrontEndController::class, 'index']);