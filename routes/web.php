<?php

use Illuminate\Support\Facades\Route;
use App\Commons\Constants;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\PanelController;

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
Route::get('login', [AccountController::class, 'login'])->name('login');
Route::get('register', [AccountController::class, 'register'])->name('register');
Route::post('login', [AccountController::class, 'login']);
Route::post('register', [AccountController::class, 'register']);
// Route::view('register', 'register')->name('register');

Route::group(['prefix' => '/'], function () {
    Route::get('panel', [PanelController::class,    'index'])    ->name('panel');
    Route::get('logout', [AccountController::class,  'logout'])   ->name('logout');
    Route::get('/test', [panelController::class,    'test'])     ->name('test');
    Route::get('/{model}', [PanelController::class,    'list'])     ->name('cms.list')    ->where('model', Constants::MODEL_REGEXP);
    Route::get('/{model}/create', [PanelController::class,    'create'])   ->name('cms.create')  ->where('model', Constants::MODEL_REGEXP);
    Route::get('/{model}/edit/{id}', [PanelController::class,    'edit'])     ->name('cms.edit')    ->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::put('/{model}/post/{id?}', [PanelController::class,    'store'])    ->name('cms.store')   ->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/view/{id}', [PanelController::class,    'view'])     ->name('cms.view')    ->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/delete/{id}', [PanelController::class,    'delete'])	 ->name('cms.delete')  ->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
});
