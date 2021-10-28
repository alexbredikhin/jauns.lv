<?php

use App\Http\Controllers\Api\TriviaGameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/question', [TriviaGameController::class, 'getQuestion']);
Route::post('/answer', [TriviaGameController::class, 'getAnswer']);
Route::post('/clean-game', [TriviaGameController::class, 'cleanGame']);
