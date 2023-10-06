<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,SectionController,PermissionController};

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/{role}/register', [AuthController::class, 'register']);
Route::group(['prefix' => 'user','middleware' => ['auth:api']], function () { 
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/create-section', [SectionController::class, 'createSection']);
    Route::post('/create-sub-section', [SectionController::class, 'createSubSection']);
    Route::post('/edit-section', [SectionController::class, 'editSection']);
    Route::post('/edit-sub-section', [SectionController::class, 'editSubSection']);
    Route::get('/section', [SectionController::class, 'index']);
});

Route::group(['prefix' => 'user','middleware' => ['auth:api','role:author']], function () {
    Route::post('/assign-permission', [PermissionController::class, 'assignPermission']);
    Route::post('/revoke-permission', [PermissionController::class, 'revokePermission']);
});
