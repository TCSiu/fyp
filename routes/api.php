<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\PanelController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\TaskController;

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
//	 return $request->user();
// });

// Route::post('register',					 	[AccountController::class,  'register']);
Route::post('login',						[AccountController::class,  'login']);
Route::post('register',					 	[AccountController::class,  'register']);
Route::get('/order/{uuid}',                 [PanelController::class,    'OrderSearch']);

Route::group(['prefix'  =>  '/', 'middleware'   =>  'auth:api'], function(){
    Route::get('/index',                    [TaskController::class,     'getAllTasks']);
    Route::get('/task/{uuid}',              [TaskController::class,     'getTask'])->whereUuid('uuid');
    Route::get('/task/{uuid}/status',       [TaskController::class,     'getTaskStatus'])->whereUuid('uuid');
    Route::post('/order/update',            [TaskController::class,     'updateOrderStatus']);
    Route::get('/order/view/{uuid}',        [PanelController::class,     'viewOrder'])->whereUuid('uuid');
});

Route::get('/image/inventory/{company_id}',	[UploadController::class,   'getImageInventory'])	->name('getImageInventory')->whereNumber('company_id');
Route::post('/image/upload',				[UploadController::class,   'fileStore'])			->name('upload');

Route::post('/file/upload',				 	[UploadController::class,   'fileImport'])			->name('import');

// Route::get('route/prepare/{company_id}',	[PanelController::class,   'routePrepare'])		 ->name('route.prepare')->whereNumber('company_id');
Route::post('/route/planning',			  	[PanelController::class,	'routePlanning'])		->name('route.planning');
Route::post('/route/storing',			   	[PanelController::class,	'routeStoring'])		->name('route.storing');

Route::get('/staff/{id}',                   [PanelController::class,    'getStaffList'])        ->name('route.staff')->whereNumber('id');
Route::post('/assign',                      [TaskController::class,     'assignTask'])          ->name('route.assign')->where('model', Constants::MODEL_REGEXP);
