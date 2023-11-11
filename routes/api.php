<?php

use App\Http\Controllers\EmailController;
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

Route::post('{user}/send', [EmailController::class, 'send'])->name('send')->middleware('auth.token');
Route::get('list', [EmailController::class, 'list'])->name('list');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
