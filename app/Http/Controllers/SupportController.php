<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;

class SupportController extends Controller
{
    // Display inbox messages with pagination
    public function index()
    {
        $messages = SupportMessage::latest()->paginate(10);

        return view('support.index', compact('messages'));
    }

    // Show single email message details
    public function show($id)
    {
        $message = SupportMessage::findOrFail($id);

        return view('support.show', compact('message'));
    }
}