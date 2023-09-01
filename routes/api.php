<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostAPIController;
use App\Http\Controllers\RabbitMQController;
use App\Http\Controllers\CategoryAPIController;


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

Route::get('/indexPostAPI', [PostAPIController::class, 'indexPostAPI']);
Route::post('/storePostAPI', [PostAPIController::class, 'storePostAPI']);
Route::get('/showPostAPI/{id}', [PostAPIController::class, 'showPostAPI']);
Route::get('/searchPostAPI/{search}', [PostAPIController::class, 'searchPostAPI']);
Route::post('/updatePostAPI', [PostAPIController::class, 'updatePostAPI']);
Route::post('/destroyPostAPI/{id}', [PostAPIController::class, 'destroyPostAPI']);

Route::post('/rabbit/send', [RabbitMQController::class, 'send']);

Route::get('/getCategoriesAPI', [CategoryAPIController::class, 'getCategories']);

Route::post('/storePostAPILsg', [PostAPIController::class, 'storePostAPILsg']);
