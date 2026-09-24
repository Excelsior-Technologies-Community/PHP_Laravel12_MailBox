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
                'trashCount'
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

        return view(
            'support.show',
            compact('message')
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