<?php

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

Route::post('login', [AccountController::class, 'login']);
Route::get('/order/{uuid}', [TaskController::class, 'OrderSearch']);

Route::group(['prefix'  =>  '/', 'middleware'   =>  'auth:api'], function () {
    Route::get('/index', [TaskController::class, 'getAllTasks']);
    Route::get('/task/{uuid}', [TaskController::class, 'getTask'])->whereUuid('uuid');
    Route::get('/task/{uuid}/status', [TaskController::class, 'getTaskStatus'])->whereUuid('uuid');
    Route::post('/order/update', [TaskController::class, 'updateOrderStatus']);
    Route::get('/order/view/{uuid}', [TaskController::class, 'viewOrder'])->whereUuid('uuid');
});

Route::get('/image/inventory/{company_id}', [UploadController::class, 'getImageInventory'])->name('getImageInventory')->whereNumber('company_id');
Route::post('/image/upload', [UploadController::class, 'fileStore'])->name('upload');
Route::post('/file/upload', [UploadController::class, 'fileImport'])->name('import');

Route::post('/route/planning', [PanelController::class, 'routePlanning'])->name('route.planning');
Route::post('/route/storing', [PanelController::class, 'routeStoring'])->name('route.storing');

Route::get('/staff/{company_id}', [PanelController::class, 'getStaffList'])->name('route.staff')->whereNumber('company_id');
Route::post('/assign', [PanelController::class, 'assignTask'])->name('route.assign')->where('model', Constants::MODEL_REGEXP);
