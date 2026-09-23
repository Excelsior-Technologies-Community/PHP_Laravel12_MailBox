<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display mailbox analytics dashboard.
     */
    public function dashboard()
    {
        $totalEmails = SupportMessage::count();

        $unreadEmails = SupportMessage::where('is_read', false)->count();

        $readEmails = SupportMessage::where('is_read', true)->count();

        $todayEmails = SupportMessage::whereDate(
            'created_at',
            today()
        )->count();

        $uniqueSenders = SupportMessage::distinct('from_email')->count('from_email');

        $urgentEmails = SupportMessage::where('priority', 'urgent')->count();

        $highPriorityEmails = SupportMessage::where('priority', 'high')->count();

        $normalPriorityEmails = SupportMessage::where('priority', 'normal')->count();

        $recentEmails = SupportMessage::latest()
            ->take(5)
            ->get();

        $topSenders = SupportMessage::selectRaw(
                'from_email, COUNT(*) as total'
            )
            ->groupBy('from_email')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('support.dashboard', compact(
            'totalEmails',
            'unreadEmails',
            'readEmails',
            'todayEmails',
            'uniqueSenders',
            'urgentEmails',
            'highPriorityEmails',
            'normalPriorityEmails',
            'recentEmails',
            'topSenders'
        ));
    }

    /**
     * Display inbox with search, sender filtering and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sender = $request->input('sender');
        $priority = $request->input('priority');
        $status = $request->input('status');

        $query = SupportMessage::query();

        /*
         * Search by sender, subject or message.
         */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('from_email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        /*
         * Filter by sender.
         */
        if ($sender) {
            $query->where('from_email', $sender);
        }

        /*
         * Filter by priority.
         */
        if ($priority && in_array($priority, ['normal', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }

        /*
         * Filter by read/unread status.
         */
        if ($status === 'read') {
            $query->where('is_read', true);
        }

        if ($status === 'unread') {
            $query->where('is_read', false);
        }

        $messages = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
         * Get unique senders for the filter dropdown.
         */
        $senders = SupportMessage::query()
            ->select('from_email')
            ->distinct()
            ->orderBy('from_email')
            ->pluck('from_email');

        return view('support.index', compact(
            'messages',
            'senders',
            'search',
            'sender',
            'priority',
            'status'
        ));
    }

    /**
     * Show single email message details.
     */
    public function show($id)
    {
        $message = SupportMessage::findOrFail($id);

        /*
         * Opening an unread email automatically marks it as read.
         */
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('support.show', compact('message'));
    }

    /**
     * Toggle email read/unread status.
     */
    public function toggleRead($id)
    {
        $message = SupportMessage::findOrFail($id);

        if ($message->is_read) {
            $message->markAsUnread();

            $status = 'Email marked as unread.';
        } else {
            $message->markAsRead();

            $status = 'Email marked as read.';
        }

        return back()->with('success', $status);
    }

    /**
     * Update email priority.
     */
    public function updatePriority(Request $request, $id)
    {
        $message = SupportMessage::findOrFail($id);

        $validated = $request->validate([
            'priority' => [
                'required',
                'in:normal,high,urgent',
            ],
        ]);

        $message->update([
            'priority' => $validated['priority'],
        ]);

        return back()->with(
            'success',
            'Email priority updated successfully.'
        );
    }
}