<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MenuController;

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

Route::redirect('/', '/login');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'login']);

Route::view('register', 'register')->name('register');
Route::post('register', [UsersController::class, 'register']);

Route::get('/menu', [MenuController::class, 'index']);

// Route::group(['prefix' => 'menu', 'middleware' => 'auth:panel'], function(){
//     Route::get('/', [MenuController::class, 'index']);
// });
