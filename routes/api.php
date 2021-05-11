<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InviteController;

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

Route::get('/group/all', [GroupController::class, 'index']); // Get all data from Groups table
Route::get('/group/show/{id}', [GroupController::class, 'show']); // Get all data from a specfic group
Route::post('/group/create', [GroupController::class, 'create']); // Create a group with certain rules. When you createa group, you get added to the group_users table.
Route::post('/group/update', [GroupController::class, 'update']); // Update group details
Route::post('/group/delete', [GroupController::class, 'delete']); // Delete a group

Route::post('/group/invite', [InviteController::class, 'process']);
Route::get('/group/accept/{token}', [InviteController::class, 'accept']);
