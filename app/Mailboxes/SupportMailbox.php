<?php

namespace App\Mailboxes;

use App\Models\SupportMessage;

class SupportMailbox
{
    public function __invoke($email)
    {
        SupportMessage::create([
            'from_email' => $email->from(),
            'subject'    => $email->subject(),
            'message'    => $email->text(),
        ]);
    }
}