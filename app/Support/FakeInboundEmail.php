<?php

namespace App\Support;

class FakeInboundEmail
{
    protected $from;
    protected $subject;
    protected $text;

    public function __construct($from, $subject, $text)
    {
        $this->from = $from;
        $this->subject = $subject;
        $this->text = $text;
    }

    public function from()
    {
        return $this->from;
    }

    public function subject()
    {
        return $this->subject;
    }

    public function text()
    {
        return $this->text;
    }
}