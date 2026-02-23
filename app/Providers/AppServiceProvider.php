<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BeyondCode\Mailbox\Facades\Mailbox;
use App\Mailboxes\SupportMailbox;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // emails sent to support@yourapp.com
        Mailbox::to('[email protected]', SupportMailbox::class);
    }
}