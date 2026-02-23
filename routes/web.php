<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\MailController;

Route::get('/', fn () => view('welcome'));

/* Mail Test */
Route::get('/mail-test', [MailController::class,'form']);
Route::post('/mail-test', [MailController::class,'send']);

/* Inbox */
Route::get('/support', [SupportController::class,'index']);
Route::get('/support/{id}', [SupportController::class,'show']);