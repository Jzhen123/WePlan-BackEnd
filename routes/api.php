<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

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

Route::prefix('auth')->group(function () {
  
    Route::post('/register', [UserController::class, 'register']);
    
    Route::group(['middleware' => ['auth:api']], function () {
        // Gets user and all the data
        Route::get('/user', [UserController::class, 'index']);
        // Logs out the user
        Route::get('/logout', [UserController::class, 'logout']);
    });
});
