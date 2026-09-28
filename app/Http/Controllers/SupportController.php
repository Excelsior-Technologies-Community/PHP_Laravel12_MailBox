<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Support dashboard.
     */
    public function dashboard()
    {
        $total = SupportMessage::count();

        $unread = SupportMessage::where(
            'is_read',
            false
        )->count();

        $read = SupportMessage::where(
            'is_read',
            true
        )->count();

        $starred = SupportMessage::where(
            'is_starred',
            true
        )->count();

        $urgent = SupportMessage::where(
            'priority',
            'urgent'
        )->count();

        $high = SupportMessage::where(
            'priority',
            'high'
        )->count();

        $normal = SupportMessage::where(
            'priority',
            'normal'
        )->count();

        $today = SupportMessage::whereDate(
            'created_at',
            today()
        )->count();

        $uniqueSenders = SupportMessage::distinct(
            'from_email'
        )->count('from_email');

        $recentMessages = SupportMessage::latest()
            ->take(5)
            ->get();

        $topSenders = SupportMessage::select(
                'from_email'
            )
            ->selectRaw('COUNT(*) as total')
            ->groupBy('from_email')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $trashCount = SupportMessage::onlyTrashed()->count();

        $scheduledCount = SupportMessage::where('is_scheduled', true)->count();
        $remindersCount = SupportMessage::whereNotNull('reminder_at')->count();
        $repliedCount = SupportMessage::where('is_sent_reply', true)->count();

        return view(
            'support.dashboard',
            compact(
                'total',
                'unread',
                'read',
                'starred',
                'urgent',
                'high',
                'normal',
                'today',
                'uniqueSenders',
                'recentMessages',
                'topSenders',
                'trashCount',
                'scheduledCount',
                'remindersCount',
                'repliedCount'
            )
        );
    }

    /**
     * Support inbox.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search', '');

        $sender = $request->input('sender', '');

        $priority = $request->input('priority', '');

        $status = $request->input('status', '');

        $starred = $request->boolean('starred');

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        $dateFrom = $request->input('date_from');

        $dateTo = $request->input('date_to');

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'created_at',
            'from_email',
            'subject',
            'priority',
            'is_read',
            'is_starred',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (!in_array(
            $sort,
            $allowedSorts,
            true
        )) {
            $sort = 'created_at';
        }

        $direction = strtolower(
            $request->input(
                'direction',
                'desc'
            )
        );

        if (!in_array(
            $direction,
            ['asc', 'desc'],
            true
        )) {
            $direction = 'desc';
        }

        /*
        |--------------------------------------------------------------------------
        | Emails Per Page
        |--------------------------------------------------------------------------
        */

        $allowedPerPage = [
            5,
            10,
            25,
            50,
        ];

        $perPage = (int) $request->input(
            'per_page',
            5
        );

        if (!in_array(
            $perPage,
            $allowedPerPage,
            true
        )) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Query
        |--------------------------------------------------------------------------
        */

        $query = SupportMessage::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'from_email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'subject',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'message',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sender
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sender')) {
            $query->where(
                'from_email',
                $sender
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {
            $query->where(
                'priority',
                $priority
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read / Unread
        |--------------------------------------------------------------------------
        */

        if ($status === 'read') {
            $query->where(
                'is_read',
                true
            );
        } elseif ($status === 'unread') {
            $query->where(
                'is_read',
                false
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Starred
        |--------------------------------------------------------------------------
        */

        if ($starred) {
            $query->where(
                'is_starred',
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | filled() prevents whereDate() from receiving null.
        |
        */

        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $dateFrom
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $dateTo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalInbox = SupportMessage::count();

        $unreadCount = SupportMessage::where(
            'is_read',
            false
        )->count();

        $starredCount = SupportMessage::where(
            'is_starred',
            true
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Trash
        |--------------------------------------------------------------------------
        */

        $trashCount = SupportMessage::onlyTrashed()->count();

        /*
        |--------------------------------------------------------------------------
        | Senders
        |--------------------------------------------------------------------------
        */

        $senders = SupportMessage::query()
            ->select('from_email')
            ->distinct()
            ->orderBy(
                'from_email',
                'asc'
            )
            ->pluck('from_email');

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $messages = $query
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'support.index',
            compact(
                'messages',
                'senders',

                // Summary
                'totalInbox',
                'unreadCount',
                'starredCount',
                'trashCount',

                // Filters
                'search',
                'sender',
                'priority',
                'status',
                'starred',
                'dateFrom',
                'dateTo',

                // Sorting
                'sort',
                'direction',

                // Pagination
                'perPage'
            )
        );
    }

    /**
     * Show email.
     */
    public function show($id)
    {
        $message = SupportMessage::findOrFail($id);

        if (!$message->is_read) {
            $message->markAsRead();
        }

        $threadMessages = $message->getThreadMessages();

        return view(
            'support.show',
            compact('message', 'threadMessages')
        );
    }

    /**
     * Send or Schedule Direct Reply to Email Thread.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
            'scheduled_at' => 'nullable|date',
        ]);

        $parent = SupportMessage::findOrFail($id);
        $rootId = $parent->thread_id ?? $parent->id;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $isScheduled = $request->filled('scheduled_at') && strtotime($request->scheduled_at) > time();

        $reply = SupportMessage::create([
            'parent_id' => $parent->id,
            'thread_id' => $rootId,
            'from_email' => 'support@company.com',
            'to_email' => $parent->from_email,
            'subject' => str_starts_with($parent->subject, 'Re:') ? $parent->subject : 'Re: ' . $parent->subject,
            'message' => $request->reply_message,
            'attachment' => $attachmentPath,
            'is_read' => true,
            'is_sent_reply' => true,
            'priority' => $parent->priority ?? 'normal',
            'is_scheduled' => $isScheduled,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
            'status' => $isScheduled ? 'scheduled' : 'open',
        ]);

        $parent->update(['status' => $isScheduled ? 'scheduled' : 'open']);

        $statusMsg = $isScheduled 
            ? 'Reply successfully scheduled for ' . date('d M Y, h:i A', strtotime($request->scheduled_at)) . '.'
            : 'Direct reply sent to ' . $parent->from_email . ' successfully.';

        return back()->with('success', $statusMsg);
    }

    /**
     * Set Follow-up Reminder.
     */
    public function setReminder(Request $request, $id)
    {
        $request->validate([
            'reminder_at' => 'required|date',
            'reminder_note' => 'nullable|string|max:500',
        ]);

        $message = SupportMessage::findOrFail($id);

        $message->update([
            'reminder_at' => $request->reminder_at,
            'reminder_note' => $request->reminder_note,
        ]);

        return back()->with(
            'success',
            'Follow-up reminder set for ' . date('d M Y, h:i A', strtotime($request->reminder_at)) . '.'
        );
    }

    /**
     * Toggle read/unread.
     */
    public function toggleRead($id)
    {
        $message = SupportMessage::findOrFail($id);

        if ($message->is_read) {
            $message->markAsUnread();

            return back()->with(
                'success',
                'Email marked as unread.'
            );
        }

        $message->markAsRead();

        return back()->with(
            'success',
            'Email marked as read.'
        );
    }

    /**
     * Update priority.
     */
    public function updatePriority(
        Request $request,
        $id
    ) {
        $validated = $request->validate([
            'priority' => [
                'required',
                'in:normal,high,urgent',
            ],
        ]);

        $message = SupportMessage::findOrFail($id);

        $message->update([
            'priority' => $validated['priority'],
        ]);

        return back()->with(
            'success',
            'Email priority updated successfully.'
        );
    }

    /**
     * Toggle star/unstar.
     */
    public function toggleStar($id)
    {
        $message = SupportMessage::findOrFail($id);

        $message->update([
            'is_starred' => !$message->is_starred,
        ]);

        return back()->with(
            'success',
            $message->is_starred
                ? 'Email starred successfully.'
                : 'Email unstarred successfully.'
        );
    }

    /**
     * Soft delete email.
     */
    public function destroy($id)
    {
        $message = SupportMessage::findOrFail($id);

        $message->delete();

        return back()->with(
            'success',
            'Email moved to Trash.'
        );
    }

    /**
     * Bulk soft delete.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'selected_ids' => [
                'required',
                'array',
            ],

            'selected_ids.*' => [
                'integer',
                'exists:support_messages,id',
            ],
        ]);

        $count = SupportMessage::whereIn(
            'id',
            $validated['selected_ids']
        )->delete();

        return back()->with(
            'success',
            $count . ' email(s) moved to Trash.'
        );
    }

    /**
     * Trash.
     */
    public function trash(Request $request)
    {
        $search = $request->input(
            'search',
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Trash Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'created_at',
            'from_email',
            'subject',
            'priority',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (!in_array(
            $sort,
            $allowedSorts,
            true
        )) {
            $sort = 'created_at';
        }

        $direction = strtolower(
            $request->input(
                'direction',
                'desc'
            )
        );

        if (!in_array(
            $direction,
            ['asc', 'desc'],
            true
        )) {
            $direction = 'desc';
        }

        /*
        |--------------------------------------------------------------------------
        | Trash Per Page
        |--------------------------------------------------------------------------
        */

        $allowedPerPage = [
            5,
            10,
            25,
            50,
        ];

        $perPage = (int) $request->input(
            'per_page',
            10
        );

        if (!in_array(
            $perPage,
            $allowedPerPage,
            true
        )) {
            $perPage = 10;
        }

        /*
        |--------------------------------------------------------------------------
        | Trash Query
        |--------------------------------------------------------------------------
        */

        $query = SupportMessage::onlyTrashed();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'from_email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'subject',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'message',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $messages = $query
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Trash Count
        |--------------------------------------------------------------------------
        */

        $trashCount = SupportMessage::onlyTrashed()->count();

        return view(
            'support.trash',
            compact(
                'messages',
                'search',
                'sort',
                'direction',
                'perPage',
                'trashCount'
            )
        );
    }

    /**
     * Restore email.
     */
    public function restore($id)
    {
        $message = SupportMessage::onlyTrashed()
            ->findOrFail($id);

        $message->restore();

        return back()->with(
            'success',
            'Email restored successfully.'
        );
    }

    /**
     * Permanently delete email.
     */
    public function forceDelete($id)
    {
        $message = SupportMessage::onlyTrashed()
            ->findOrFail($id);

        $message->forceDelete();

        return back()->with(
            'success',
            'Email permanently deleted.'
        );
    }
}