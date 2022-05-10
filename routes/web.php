<?php

use Illuminate\Support\Facades\Route;
use App\Commons\Constants;
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

Route::group(['prefix' => '/', 'middleware' => ['token.auth']], function(){
    Route::get('panel',                     [PanelController::class,    'index'])           ->name('panel');
    Route::get('logout',                    [UsersController::class,    'logout'])          ->name('logout');
    Route::get('/{model}/{page?}',          [PanelController::class,    'list'])            ->name('cms.list')->where('model', Constants::MODEL_REGEXP)->whereNumber('page');
    Route::get('/{model}/create',           [PanelController::class,    'create'])          ->name('cms.create')->where('model', Constants::MODEL_REGEXP);
    Route::get('/{model}/edit/{id}',        [PanelController::class,    'edit'])            ->name('cms.edit')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/view/{id}',        [PanelController::class,    'view'])            ->name('cms.view')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/delete/{id}',		[PanelController::class,    'delete'])	        ->name('cms.delete')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
});

