<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PanelController;

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
Route::get('register', [UsersController::class, 'register'])->name('register');
// Route::view('register', 'register')->name('register');

Route::post('login', [UsersController::class, 'login']);
Route::post('register', [UsersController::class, 'register']);

Route::group(['middleware' => ['token.auth']], function(){
    Route::get('panel', [PanelController::class, 'index'])->name('panel');
    Route::get('logout', [UsersController::class, 'logout'])->name('logout');
});

