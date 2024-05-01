<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('login');
// });

Auth::routes();
Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
Route::get('/home', [App\Http\Controllers\ChatController::class, 'index'])->name('home');
Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
Route::post('/send-message', [App\Http\Controllers\ChatController::class, 'send_message'])->name('send-message');
Route::get('/get-all-message/{id}', [App\Http\Controllers\ChatController::class, 'getallmessages'])->name('get-all-message');
Route::post('/delete-setting', [App\Http\Controllers\ChatController::class, 'delete_setting'])->name('delete-setting');
// cron
Route::get('/delete-message-cron', [App\Http\Controllers\CronController::class, 'delete_message_cron'])->name('delete-message-cron');

