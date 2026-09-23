<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\MailController;

Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Mail Test
|--------------------------------------------------------------------------
*/

Route::get('/mail-test', [MailController::class, 'form'])
    ->name('mail.test');

Route::post('/mail-test', [MailController::class, 'send'])
    ->name('mail.send');

/*
|--------------------------------------------------------------------------
| Mailbox Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/support/dashboard', [SupportController::class, 'dashboard'])
    ->name('support.dashboard');

/*
|--------------------------------------------------------------------------
| Inbox
|--------------------------------------------------------------------------
*/

Route::get('/support', [SupportController::class, 'index'])
    ->name('support.index');

Route::get('/support/{id}', [SupportController::class, 'show'])
    ->name('support.show');

/*
|--------------------------------------------------------------------------
| Email Management
|--------------------------------------------------------------------------
*/

Route::post('/support/{id}/toggle-read', [SupportController::class, 'toggleRead'])
    ->name('support.toggle-read');

Route::post('/support/{id}/priority', [SupportController::class, 'updatePriority'])
    ->name('support.priority');