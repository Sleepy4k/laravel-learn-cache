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

Route::any('/', function () {
    return response()->json([
        'code' => 200,
        'message' => 'OK',
        'data' => [
            'name' => 'Laravel 10',
            'version' => '1.0.0',
            'author' => 'Sleepy4k'
        ]
    ], 200);
});

Route::apiResource('/crud', CrudController::class);
