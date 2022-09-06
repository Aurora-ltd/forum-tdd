<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\ThreadSubscriptionController;

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

Route::get('/threads', [ThreadController::class, 'index']);
Route::get('/threads/create', [ThreadController::class, 'create']);
Route::get('/threads/{channel}/{thread}', [ThreadController::class, 'show']);
Route::delete('/threads/{channel}/{thread}', [ThreadController::class, 'destroy']);
Route::post('/threads', [ThreadController::class, 'store']);
Route::get('/threads/{channel}', [ThreadController::class, 'index']);
Route::get('/threads/{channel}/{thread}/replies', [ReplyController::class, 'index']);
Route::post('/threads/{channel}/{thread}/replies', [ReplyController::class, 'store']);
Route::patch('/replies/{reply}', [ReplyController::class, 'update']);
Route::delete('/replies/{reply}', [ReplyController::class, 'destroy']);
Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'destroy'])->middleware('auth');

Route::post('/replies/{reply}/favorites', [FavoriteController::class, 'store']);
Route::delete('/replies/{reply}/favorites', [FavoriteController::class, 'destroy']);

Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');
Route::get('/profiles/{user}/notifications', [UserNotificationController::class, 'index']);
Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationController::class, 'destroy']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
