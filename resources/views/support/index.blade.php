<!DOCTYPE html>
<html>

<head>

    <title>Mailbox Inbox</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            color: #111827;
        }

        .container {
            width: 94%;
            max-width: 1300px;
            margin: 30px auto;
        }

        .header {
            background: #4f46e5;
            color: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .header h2 {
            margin: 0;
        }

        .header-links a {
            display: inline-block;
            background: white;
            color: #4f46e5;
            padding: 9px 14px;
            border-radius: 6px;
            text-decoration: none;
            margin-left: 5px;
            font-weight: bold;
        }

        .success {
            margin-top: 20px;
            padding: 12px 15px;
            background: #dcfce7;
            color: #166534;
            border-radius: 7px;
            border: 1px solid #bbf7d0;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .summary-card {
            background: white;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .summary-title {
            color: #6b7280;
            font-size: 14px;
        }

        .summary-value {
            font-size: 26px;
            font-weight: bold;
            margin-top: 5px;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .filters h3 {
            margin-top: 0;
        }

        .filter-row {
            display: grid;
            grid-template-columns:
                2fr
                1.4fr
                1fr
                1fr
                1fr
                1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .filter-row.second {
            grid-template-columns:
                1fr
                1fr
                1fr
                1fr
                auto
                auto;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            border: none;
            cursor: pointer;
        }

        .search-btn {
            background: #4f46e5;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
        }

        .reset-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #4f46e5;
            font-size: 14px;
        }

        .bulk-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .bulk-delete {
            background: #dc2626;
            color: white;
            padding: 9px 14px;
            border-radius: 6px;
            font-weight: bold;
        }

        .mail-card {
            background: white;
            padding: 18px;
            margin-top: 15px;
            border-radius: 9px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 5px solid #d1d5db;
        }

        .mail-card.unread {
            border-left-color: #4f46e5;
            background: #fafaff;
        }

        .mail-card.starred {
            border-left-color: #f59e0b;
        }

        .mail-top {
            display: grid;
            grid-template-columns: 35px 1fr auto;
            align-items: center;
            gap: 12px;
        }

        .checkbox {
            width: 18px;
            height: 18px;
        }

        .star-form {
            display: inline;
        }

        .star-btn {
            background: transparent;
            font-size: 24px;
            padding: 0;
        }

        .email {
            font-weight: bold;
            color: #111827;
        }

        .subject {
            color: #4b5563;
            margin: 8px 0;
            font-size: 16px;
        }

        .date {
            color: #9ca3af;
            font-size: 13px;
        }

        .message-preview {
            color: #6b7280;
            margin: 8px 0;
            line-height: 1.5;
        }

        .badges {
            margin-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }

        .badge-unread {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-read {
            background: #dcfce7;
            color: #166534;
        }

        .badge-starred {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-normal {
            background: #e5e7eb;
            color: #374151;
        }

        .priority-high {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            margin-top: 14px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .view-btn,
        .read-btn,
        .delete-btn {
            display: inline-block;
            padding: 7px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
        }

        .view-btn {
            background: #4f46e5;
        }

        .read-btn {
            background: #374151;
        }

        .delete-btn {
            background: #dc2626;
            border: none;
            cursor: pointer;
        }

        .pagination {
            margin-top: 25px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            min-width: 38px;
            text-align: center;
            padding: 9px 12px;
            border-radius: 6px;
            text-decoration: none;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
        }

        .pagination .active {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .empty {
            background: white;
            margin-top: 20px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #6b7280;
        }

        @media(max-width: 1100px) {

            .filter-row,
            .filter-row.second {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width: 700px) {

            .summary {
                grid-template-columns: 1fr;
            }

            .filter-row,
            .filter-row.second {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-links a {
                margin-left: 0;
                margin-right: 5px;
            }

            .mail-top {
                grid-template-columns: 30px 1fr;
            }

            .date {
                grid-column: 2;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>📥 Mailbox Inbox</h2>

        <div class="header-links">

            <a href="{{ route('support.dashboard') }}">
                📊 Dashboard
            </a>

            <a href="{{ route('support.trash') }}">
                🗑️ Trash
            </a>

            <a href="{{ route('mail.test') }}">
                ✉ Send Email
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <div class="summary">

        <div class="summary-card">

            <div class="summary-title">
                Total Emails
            </div>

            <div class="summary-value">
                {{ $totalInbox }}
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Unread
            </div>

            <div class="summary-value">
                {{ $unreadCount }}
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Starred
            </div>

            <div class="summary-value">
                {{ $starredCount }}
            </div>

        </div>

    </div>

    <div class="filters">

        <h3>🔎 Search & Advanced Filters</h3>

        <form
            method="GET"
            action="{{ route('support.index') }}"
        >

            <div class="filter-row">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search sender, subject or message..."
                >

                <select name="sender">

                    <option value="">
                        All Senders
                    </option>

                    @foreach($senders as $email)

                        <option
                            value="{{ $email }}"
                            {{ $sender === $email ? 'selected' : '' }}
                        >
                            {{ $email }}
                        </option>

                    @endforeach

                </select>

                <select name="priority">

                    <option value="">
                        All Priority
                    </option>

                    <option
                        value="normal"
                        {{ $priority === 'normal' ? 'selected' : '' }}
                    >
                        Normal
                    </option>

                    <option
                        value="high"
                        {{ $priority === 'high' ? 'selected' : '' }}
                    >
                        High
                    </option>

                    <option
                        value="urgent"
                        {{ $priority === 'urgent' ? 'selected' : '' }}
                    >
                        Urgent
                    </option>

                </select>

                <select name="status">

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="unread"
                        {{ $status === 'unread' ? 'selected' : '' }}
                    >
                        Unread
                    </option>

                    <option
                        value="read"
                        {{ $status === 'read' ? 'selected' : '' }}
                    >
                        Read
                    </option>

                </select>

                <select name="starred">

                    <option value="">
                        All Star Status
                    </option>

                    <option
                        value="yes"
                        {{ $starred === 'yes' ? 'selected' : '' }}
                    >
                        ⭐ Starred Only
                    </option>

                </select>

                <select name="per_page">

                    <option
                        value="5"
                        {{ $perPage == 5 ? 'selected' : '' }}
                    >
                        5 / Page
                    </option>

                    <option
                        value="10"
                        {{ $perPage == 10 ? 'selected' : '' }}
                    >
                        10 / Page
                    </option>

                    <option
                        value="25"
                        {{ $perPage == 25 ? 'selected' : '' }}
                    >
                        25 / Page
                    </option>

                    <option
                        value="50"
                        {{ $perPage == 50 ? 'selected' : '' }}
                    >
                        50 / Page
                    </option>

                </select>

            </div>

            <div class="filter-row second">

                <div>

                    <label>
                        Date From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                    >

                </div>

                <div>

                    <label>
                        Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                    >

                </div>

                <select name="sort">

                    <option
                        value="created_at"
                        {{ $sort === 'created_at' ? 'selected' : '' }}
                    >
                        Sort by Date
                    </option>

                    <option
                        value="from_email"
                        {{ $sort === 'from_email' ? 'selected' : '' }}
                    >
                        Sort by Sender
                    </option>

                    <option
                        value="subject"
                        {{ $sort === 'subject' ? 'selected' : '' }}
                    >
                        Sort by Subject
                    </option>

                    <option
                        value="priority"
                        {{ $sort === 'priority' ? 'selected' : '' }}
                    >
                        Sort by Priority
                    </option>

                </select>

                <select name="direction">

                    <option
                        value="desc"
                        {{ $direction === 'desc' ? 'selected' : '' }}
                    >
                        Descending
                    </option>

                    <option
                        value="asc"
                        {{ $direction === 'asc' ? 'selected' : '' }}
                    >
                        Ascending
                    </option>

                </select>

                <button
                    type="submit"
                    class="search-btn"
                >
                    Apply Filters
                </button>

            </div>

            <a
                href="{{ route('support.index') }}"
                class="reset-btn"
            >
                Reset All Filters
            </a>

        </form>

    </div>

    <form
        method="POST"
        action="{{ route('support.bulk-delete') }}"
        id="bulkForm"
    >

        @csrf

        <div class="bulk-bar">

            <label>

                <input
                    type="checkbox"
                    id="selectAll"
                >

                Select All Emails

            </label>

            <button
                type="submit"
                class="bulk-delete"
                onclick="
                    return confirm(
                        'Move selected emails to Trash?'
                    );
                "
            >
                🗑️ Delete Selected
            </button>

        </div>

        @forelse($messages as $msg)

            <div
                class="
                    mail-card
                    {{ !$msg->is_read ? 'unread' : '' }}
                    {{ $msg->is_starred ? 'starred' : '' }}
                "
            >

                <div class="mail-top">

                    <input
                        type="checkbox"
                        class="checkbox email-checkbox"
                        name="ids[]"
                        value="{{ $msg->id }}"
                    >

                    <div>

                        <form
                            method="POST"
                            action="{{ route('support.toggle-star', $msg->id) }}"
                            class="star-form"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="star-btn"
                                title="Star / Unstar"
                            >
                                {{ $msg->is_starred ? '⭐' : '☆' }}
                            </button>

                        </form>

                        <span class="email">
                            {{ $msg->from_email }}
                        </span>

                        <div class="subject">
                            {{ $msg->subject ?: '(No Subject)' }}
                        </div>

                    </div>

                    <div class="date">
                        {{ $msg->created_at->format('d M Y, h:i A') }}
                    </div>

                </div>

                <div class="message-preview">

                    {{ \Illuminate\Support\Str::limit(
                        $msg->message,
                        150
                    ) }}

                </div>

                <div class="badges">

                    @if($msg->is_read)

                        <span class="badge badge-read">
                            ✓ Read
                        </span>

                    @else

                        <span class="badge badge-unread">
                            ● Unread
                        </span>

                    @endif

                    @if($msg->is_starred)

                        <span class="badge badge-starred">
                            ⭐ Starred
                        </span>

                    @endif

                    <span
                        class="badge {{ $msg->priorityClass() }}"
                    >
                        {{ ucfirst($msg->priority) }}
                        Priority
                    </span>

                </div>

                <div class="actions">

                    <a
                        href="{{ route(
                            'support.show',
                            $msg->id
                        ) }}"
                        class="view-btn"
                    >
                        View Email
                    </a>

                    <form
                        method="POST"
                        action="{{ route(
                            'support.toggle-read',
                            $msg->id
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="read-btn"
                        >

                            @if($msg->is_read)
                                Mark Unread
                            @else
                                Mark Read
                            @endif

                        </button>

                    </form>

                    <form
                        method="POST"
                        action="{{ route(
                            'support.destroy',
                            $msg->id
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Move this email to Trash?'
                            );
                        "
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="delete-btn"
                        >
                            🗑️ Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="empty">

                <h3>
                    📭 No Emails Found
                </h3>

                <p>
                    No emails match your current filters.
                </p>

            </div>

        @endforelse

    </form>

    @if($messages->hasPages())

        <div class="pagination">

            @foreach($messages->getUrlRange(
                1,
                $messages->lastPage()
            ) as $page => $url)

                @if($page == $messages->currentPage())

                    <span class="active">
                        {{ $page }}
                    </span>

                @else

                    <a href="{{ $url }}">
                        {{ $page }}
                    </a>

                @endif

            @endforeach

        </div>

    @endif

</div>

<script>

    document
        .getElementById('selectAll')
        .addEventListener('change', function () {

            document
                .querySelectorAll('.email-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked =
                        this.checked;

                }, this);

        });

</script>

</body>

</html>