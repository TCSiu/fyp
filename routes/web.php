<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Commons\Constants;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AJAX\ImageController;

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
Route::get('login', [RegisterController::class, 'login'])->name('login');
Route::get('register', [RegisterController::class, 'register'])->name('register');
// Route::view('register', 'register')->name('register');

Route::post('login', [RegisterController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);


Route::group(['prefix' => '/', 'middleware' => ['auth']], function(){
    Route::get('panel',                         [PanelController::class,    'index'])           ->name('panel');
    Route::get('logout',                        [RegisterController::class, 'logout'])          ->name('logout');
    Route::get('/profile',                      [PanelController::class,    'profile'])         ->name('profile');
    Route::get('/company',                      [PanelController::class,    'company'])         ->name('company');
    Route::get('/image',                        [PanelController::class,    'image'])           ->name('image');
    Route::post('/image/upload',                [ImageController::class,    'fileStore'])       ->name('upload');
    Route::get('/image/{id}',                   [ImageController::class,    'viewImage'])       ->name('viewImage');
    Route::get('/{model}',                      [PanelController::class,    'list'])            ->name('cms.list')->where('model', Constants::MODEL_REGEXP);
    Route::get('/{model}/create',               [PanelController::class,    'create'])          ->name('cms.create')->where('model', Constants::MODEL_REGEXP);
    Route::get('/{model}/edit/{id}',            [PanelController::class,    'edit'])            ->name('cms.edit')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::put('/{model}/post/{id?}',           [PanelController::class,    'store'])           ->name('cms.store')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/view/{id}',            [PanelController::class,    'view'])            ->name('cms.view')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/delete/{id}',		    [PanelController::class,    'delete'])	        ->name('cms.delete')->where('model', Constants::MODEL_REGEXP)->whereNumber('id');
    Route::get('/{model}/get_csv',              [PanelController::class,    'get_csv'])         ->name('cms.get_csv')->where('model', Constants::MODEL_REGEXP);
});
