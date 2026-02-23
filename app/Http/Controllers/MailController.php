<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mailboxes\SupportMailbox;
use App\Support\FakeInboundEmail;

class MailController extends Controller
{
    public function form()
    {
        return view('mailtest');
    }

    public function send(Request $request)
    {
        // create fake email (SAFE)
        $email = new FakeInboundEmail(
            $request->from,
            $request->subject,
            $request->input('body-plain')
        );

        // call mailbox handler
        app(SupportMailbox::class)($email);

        return redirect('/support');
    }
}