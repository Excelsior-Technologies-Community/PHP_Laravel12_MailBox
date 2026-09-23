<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mailboxes\SupportMailbox;
use App\Support\FakeInboundEmail;

class MailController extends Controller
{
    /**
     * Display fake email testing form.
     */
    public function form()
    {
        return view('mailtest');
    }

    /**
     * Send a fake inbound email through the mailbox handler.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'from' => [
                'required',
                'email',
                'max:255',
            ],
            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],
            'body-plain' => [
                'required',
                'string',
                'max:10000',
            ],
        ]);

        $email = new FakeInboundEmail(
            $validated['from'],
            $validated['subject'] ?? '',
            $validated['body-plain']
        );

        app(SupportMailbox::class)($email);

        return redirect('/support')
            ->with('success', 'Email received successfully and added to the mailbox.');
    }
}