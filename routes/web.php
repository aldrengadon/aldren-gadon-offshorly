<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ToDosController;

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
    return redirect('login');
});
Auth::routes();

Route::post('/register-user', [RegisterController::class, 'registerUser']);
Route::post('/authenticate', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::group(['middleware' => ['auth']], function() {

    Route::controller(ToDosController::class)->group(function () {
        Route::get('/to-dos', 'index');
        Route::post('/register-to-dos', 'registerTodos');
    });

});



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
