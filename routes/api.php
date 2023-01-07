<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ImageController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AccountController::class, 'register']);
Route::post('login',    [AccountController::class, 'login']);
Route::post('register', [AccountController::class, 'register']);

Route::get('/image/viewAll',                 [ImageController::class,    'getImageInventory'])   ->name('getImageInventory');
Route::post('/image/upload',                [ImageController::class,    'fileStore'])           ->name('upload');
