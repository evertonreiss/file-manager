<?php

use App\Http\Controllers\Api\V1\AuthContoller;
use App\Http\Controllers\Api\V1\MediaFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('arquivos', [MediaFileController::class, 'index']);
        Route::post('arquivos', [MediaFileController::class, 'store']);
        Route::get('arquivos/{media_file}', [MediaFileController::class, 'show']);
        Route::put('arquivos/{media_file}', [MediaFileController::class, 'update']);
        Route::delete('arquivos/{media_file}', [MediaFileController::class, 'destroy']);
        Route::get('arquivos/download/{media_file}', [MediaFileController::class, 'fileDownload']);
    });

    Route::post('register', [AuthContoller::class, 'register']);
    Route::post('login', [AuthContoller::class, 'login'])->name('login');
});
