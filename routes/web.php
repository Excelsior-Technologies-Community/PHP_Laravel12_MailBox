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

Route::get(
    '/support/dashboard',
    [SupportController::class, 'dashboard']
)->name('support.dashboard');

/*
|--------------------------------------------------------------------------
| Trash
|--------------------------------------------------------------------------
|
| Keep this route BEFORE /support/{id}
|
*/

Route::get(
    '/support/trash',
    [SupportController::class, 'trash']
)->name('support.trash');

/*
|--------------------------------------------------------------------------
| Bulk Email Management
|--------------------------------------------------------------------------
*/

Route::post(
    '/support/bulk-delete',
    [SupportController::class, 'bulkDelete']
)->name('support.bulk-delete');

/*
|--------------------------------------------------------------------------
| Inbox
|--------------------------------------------------------------------------
*/

Route::get(
    '/support',
    [SupportController::class, 'index']
)->name('support.index');

/*
|--------------------------------------------------------------------------
| Email Management
|--------------------------------------------------------------------------
*/

Route::post(
    '/support/{id}/toggle-read',
    [SupportController::class, 'toggleRead']
)->name('support.toggle-read');

Route::post(
    '/support/{id}/toggle-star',
    [SupportController::class, 'toggleStar']
)->name('support.toggle-star');

Route::post(
    '/support/{id}/priority',
    [SupportController::class, 'updatePriority']
)->name('support.priority');

Route::delete(
    '/support/{id}',
    [SupportController::class, 'destroy']
)->name('support.destroy');

/*
|--------------------------------------------------------------------------
| Trash Management
|--------------------------------------------------------------------------
*/

Route::post(
    '/support/trash/{id}/restore',
    [SupportController::class, 'restore']
)->name('support.restore');

Route::delete(
    '/support/trash/{id}/force-delete',
    [SupportController::class, 'forceDelete']
)->name('support.force-delete');

/*
|--------------------------------------------------------------------------
| Email Detail
|--------------------------------------------------------------------------
*/

Route::get(
    '/support/{id}',
    [SupportController::class, 'show']
)->name('support.show');