<?php

use App\Http\Controllers\CalonController;
use App\Http\Controllers\CompController;
use App\Http\Controllers\DteventController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
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

    Route::delete('/calon', [CalonController::class, 'destroy'])->name('calon.destroy');
    Route::resource('calon', CalonController::class)->only(['index', 'store', 'edit', 'update']);

    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');
    Route::resource('user', UserController::class)->only(['index', 'store', 'edit', 'update']);

    Route::delete('/event', [EventController::class, 'destroy'])->name('event.destroy');
    Route::resource('event', EventController::class)->only(['index', 'store', 'edit', 'update']);

    // Route::delete('/dtevent', [DteventController::class, 'destroy'])->name('dtevent.destroy');
    Route::resource('dtevent', DteventController::class)->only(['index', 'store', 'destroy']);

    Route::get('/vote/statistic', [VoteController::class, 'statistic'])->name('vote.statistic');
    Route::delete('/vote', [VoteController::class, 'destroy'])->name('vote.destroy');
    Route::resource('vote', VoteController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});
