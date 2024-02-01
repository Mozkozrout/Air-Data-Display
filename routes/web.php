<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Public Routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/handle-login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/', [DataController::class, 'index'])->name('home');
});

