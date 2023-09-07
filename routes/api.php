<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CrudController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', CrudController::class . '@index');
Route::post('/', CrudController::class . '@store');
Route::get('/{id}', CrudController::class . '@show');
Route::put('/{id}', CrudController::class . '@update');
Route::delete('/{id}', CrudController::class . '@destroy');
