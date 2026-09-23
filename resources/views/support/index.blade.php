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
            width: 90%;
            max-width: 1200px;
            margin: 35px auto;
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
            grid-template-columns: 2fr 1.5fr 1fr 1fr auto;
            gap: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-btn {
            border: none;
            background: #4f46e5;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .reset-btn {
            display: inline-block;
            margin-top: 12px;
            text-decoration: none;
            color: #4f46e5;
            font-size: 14px;
        }

        .mail-card {
            background: white;
            padding: 18px;
            margin-top: 15px;
            border-radius: 9px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-left: 5px solid #d1d5db;
        }

        .mail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .mail-card.unread {
            border-left-color: #4f46e5;
            background: #fafaff;
        }

        .mail-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
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
        .read-btn {
            display: inline-block;
            padding: 7px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }

        .view-btn {
            background: #4f46e5;
        }

        .read-btn {
            background: #374151;
        }

        .pagination {
            margin-top: 25px;
        }

        .empty {
            background: white;
            margin-top: 20px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #6b7280;
        }

        @media(max-width: 900px) {
            .filter-row {
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
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>📥 Mailbox Inbox</h2>

        <div class="header-links">
            <a href="{{ route('support.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('mail.test') }}">✉ Send Email</a>
        </div>

    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="filters">

        <h3>🔎 Search & Filter Emails</h3>

        <form method="GET" action="{{ route('support.index') }}">

            <div class="filter-row">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search sender, subject or message..."
                >

                <select name="sender">

                    <option value="">All Senders</option>

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

                    <option value="">All Priority</option>

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

                    <option value="">All Status</option>

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

                <button type="submit" class="search-btn">
                    Search
                </button>

            </div>

            <a href="{{ route('support.index') }}" class="reset-btn">
                Reset Filters
            </a>

        </form>

    </div>

    @forelse($messages as $msg)

        <div class="mail-card {{ !$msg->is_read ? 'unread' : '' }}">

            <div class="mail-top">

                <div>
                    <div class="email">
                        {{ $msg->from_email }}
                    </div>

                    <div class="subject">
                        {{ $msg->subject ?: '(No Subject)' }}
                    </div>
                </div>

                <div class="date">
                    {{ $msg->created_at->format('d M Y, h:i A') }}
                </div>

            </div>

            <div class="message-preview">
                {{ \Illuminate\Support\Str::limit($msg->message, 150) }}
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

                <span class="badge {{ $msg->priorityClass() }}">
                    {{ ucfirst($msg->priority) }} Priority
                </span>

            </div>

            <div class="actions">

                <a
                    href="{{ route('support.show', $msg->id) }}"
                    class="view-btn"
                >
                    View Email
                </a>

                <form
                    method="POST"
                    action="{{ route('support.toggle-read', $msg->id) }}"
                    style="display:inline;"
                >

                    @csrf

                    <button type="submit" class="read-btn">

                        @if($msg->is_read)
                            Mark Unread
                        @else
                            Mark Read
                        @endif

                    </button>

                </form>

            </div>

        </div>

    @empty

        <div class="empty">

            <h3>📭 No Emails Found</h3>

            <p>
                No emails match your current search or filters.
            </p>

        </div>

    @endforelse

    @if($messages->hasPages())

        <div class="pagination">
            {{ $messages->links() }}
        </div>

    @endif

</div>

</body>

</html>