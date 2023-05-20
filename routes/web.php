<?php

use App\Http\Controllers\CalonController;
use App\Http\Controllers\CompController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::get('/', function () {
    return redirect()->route('login');
});
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile', [UserController::class, 'profileUpdate'])->name('user.profileUpdate');

    Route::get('/company', [CompController::class, 'index'])->name('company.index');
    Route::post('/company', [CompController::class, 'store'])->name('company.store');

    Route::resource('calon', CalonController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
});
