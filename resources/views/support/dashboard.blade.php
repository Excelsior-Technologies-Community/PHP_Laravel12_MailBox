<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Support Mailbox Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .container {
            width: 95%;
            max-width: 1400px;
            margin: 30px auto;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            opacity: 0.9;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            cursor: pointer;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-white {
            background: white;
            color: #1e3a8a;
        }

        .btn-light {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.07);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            right: -25px;
            top: -25px;
            background: rgba(37, 99, 235, 0.08);
        }

        .stat-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
        }

        .stat-link {
            display: inline-block;
            margin-top: 12px;
            text-decoration: none;
            font-size: 13px;
            color: #2563eb;
            font-weight: 600;
        }

        .blue {
            border-left: 5px solid #2563eb;
        }

        .green {
            border-left: 5px solid #16a34a;
        }

        .orange {
            border-left: 5px solid #f97316;
        }

        .red {
            border-left: 5px solid #dc2626;
        }

        .purple {
            border-left: 5px solid #7c3aed;
        }

        .yellow {
            border-left: 5px solid #ca8a04;
        }

        .gray {
            border-left: 5px solid #6b7280;
        }

        .pink {
            border-left: 5px solid #db2777;
        }

        .section-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 20px;
            color: #111827;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #eef0f3;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .email {
            font-weight: 600;
            color: #111827;
        }

        .subject {
            color: #374151;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-read {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unread {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-normal {
            background: #e5e7eb;
            color: #374151;
        }

        .badge-high {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-urgent {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-starred {
            background: #fef3c7;
            color: #92400e;
        }

        .sender-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .sender-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #f8fafc;
            border-radius: 9px;
        }

        .sender-email {
            font-size: 13px;
            font-weight: 600;
            word-break: break-word;
        }

        .sender-count {
            min-width: 32px;
            text-align: center;
            padding: 5px 8px;
            background: #2563eb;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .quick-action {
            display: block;
            text-decoration: none;
            padding: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #111827;
            transition: 0.2s;
        }

        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .quick-icon {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .quick-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .quick-text {
            color: #6b7280;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .section-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .container {
                width: 92%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 24px;
            }

            .card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Header --}}
    <div class="header">
        <h1>📬 Support Mailbox Dashboard</h1>

        <p>
            Monitor incoming emails, starred messages, unread messages,
            priorities and deleted emails.
        </p>

        <div class="actions">
            <a href="{{ url('/support') }}" class="btn btn-white">
                📥 Open Inbox
            </a>

            <a href="{{ url('/support?starred=1') }}" class="btn btn-light">
                ⭐ Starred
            </a>

            <a href="{{ url('/trash') }}" class="btn btn-light">
                🗑️ Trash
            </a>

            <a href="{{ url('/mail-test') }}" class="btn btn-light">
                ✉️ Send Test Email
            </a>
        </div>
    </div>


    {{-- Main Statistics --}}
    <div class="stats-grid">

        <div class="card stat-card blue">
            <div class="stat-title">📨 Total Emails</div>

            <div class="stat-number">
                {{ $total ?? 0 }}
            </div>

            <a href="{{ url('/support') }}" class="stat-link">
                View Inbox →
            </a>
        </div>


        <div class="card stat-card orange">
            <div class="stat-title">📬 Unread Emails</div>

            <div class="stat-number">
                {{ $unread ?? 0 }}
            </div>

            <a href="{{ url('/support?status=unread') }}" class="stat-link">
                View Unread →
            </a>
        </div>


        <div class="card stat-card green">
            <div class="stat-title">📖 Read Emails</div>

            <div class="stat-number">
                {{ $read ?? 0 }}
            </div>

            <a href="{{ url('/support?status=read') }}" class="stat-link">
                View Read →
            </a>
        </div>


        <div class="card stat-card purple">
            <div class="stat-title">⭐ Starred Emails</div>

            <div class="stat-number">
                {{ $starred ?? 0 }}
            </div>

            <a href="{{ url('/support?starred=1') }}" class="stat-link">
                View Starred →
            </a>
        </div>


        <div class="card stat-card red">
            <div class="stat-title">🗑️ Trash</div>

            <div class="stat-number">
                {{ $trashed ?? 0 }}
            </div>

            <a href="{{ url('/trash') }}" class="stat-link">
                Manage Trash →
            </a>
        </div>


        <div class="card stat-card yellow">
            <div class="stat-title">🔥 Urgent Emails</div>

            <div class="stat-number">
                {{ $urgent ?? 0 }}
            </div>

            <a href="{{ url('/support?priority=urgent') }}" class="stat-link">
                View Urgent →
            </a>
        </div>


        <div class="card stat-card orange">
            <div class="stat-title">⚠️ High Priority</div>

            <div class="stat-number">
                {{ $high ?? 0 }}
            </div>

            <a href="{{ url('/support?priority=high') }}" class="stat-link">
                View High Priority →
            </a>
        </div>


        <div class="card stat-card gray">
            <div class="stat-title">📅 Today's Emails</div>

            <div class="stat-number">
                {{ $today ?? 0 }}
            </div>

            <a href="{{ url('/support?from_date=' . now()->format('Y-m-d') . '&to_date=' . now()->format('Y-m-d')) }}"
               class="stat-link">
                View Today →
            </a>
        </div>

    </div>


    {{-- Recent Emails + Top Senders --}}
    <div class="section-grid">

        {{-- Recent Emails --}}
        <div class="card">

            <h2 class="section-title">
                🕐 Recent Emails
            </h2>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Star</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($recentMessages ?? [] as $message)

                        <tr>

                            <td>
                                <div class="email">
                                    {{ $message->from_email }}
                                </div>
                            </td>

                            <td>
                                <div class="subject">
                                    {{ \Illuminate\Support\Str::limit($message->subject, 40) }}
                                </div>
                            </td>

                            <td>

                                @if($message->is_read)

                                    <span class="badge badge-read">
                                        Read
                                    </span>

                                @else

                                    <span class="badge badge-unread">
                                        Unread
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($message->priority === 'urgent')

                                    <span class="badge badge-urgent">
                                        Urgent
                                    </span>

                                @elseif($message->priority === 'high')

                                    <span class="badge badge-high">
                                        High
                                    </span>

                                @else

                                    <span class="badge badge-normal">
                                        Normal
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($message->is_starred)

                                    <span class="badge badge-starred">
                                        ⭐ Starred
                                    </span>

                                @else

                                    <span style="color:#9ca3af;">
                                        ☆
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty">
                                No emails available.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Top Senders --}}
        <div class="card">

            <h2 class="section-title">
                👥 Top Senders
            </h2>

            <div class="sender-list">

                @forelse($topSenders ?? [] as $sender)

                    @php
                        $senderEmail = is_array($sender)
                            ? ($sender['from_email'] ?? $sender['email'] ?? '')
                            : ($sender->from_email ?? '');

                        $senderCount = is_array($sender)
                            ? ($sender['count'] ?? $sender['total'] ?? 0)
                            : ($sender->count ?? 0);
                    @endphp

                    <div class="sender-item">

                        <span class="sender-email">
                            {{ $senderEmail }}
                        </span>

                        <span class="sender-count">
                            {{ $senderCount }}
                        </span>

                    </div>

                @empty

                    <div class="empty">
                        No sender data available.
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- Quick Actions --}}
    <div class="card">

        <h2 class="section-title">
            ⚡ Mailbox Management
        </h2>

        <div class="quick-grid">

            <a href="{{ url('/support') }}" class="quick-action">

                <div class="quick-icon">
                    📥
                </div>

                <div class="quick-title">
                    Inbox
                </div>

                <div class="quick-text">
                    Browse and manage all active emails.
                </div>

            </a>


            <a href="{{ url('/support?starred=1') }}" class="quick-action">

                <div class="quick-icon">
                    ⭐
                </div>

                <div class="quick-title">
                    Starred Emails
                </div>

                <div class="quick-text">
                    View only your starred emails.
                </div>

            </a>


            <a href="{{ url('/trash') }}" class="quick-action">

                <div class="quick-icon">
                    🗑️
                </div>

                <div class="quick-title">
                    Trash
                </div>

                <div class="quick-text">
                    Restore or permanently delete emails.
                </div>

            </a>


            <a href="{{ url('/support?status=unread') }}" class="quick-action">

                <div class="quick-icon">
                    📩
                </div>

                <div class="quick-title">
                    Unread
                </div>

                <div class="quick-text">
                    Quickly find unread messages.
                </div>

            </a>


            <a href="{{ url('/support?priority=urgent') }}" class="quick-action">

                <div class="quick-icon">
                    🚨
                </div>

                <div class="quick-title">
                    Urgent Emails
                </div>

                <div class="quick-text">
                    Review urgent priority messages.
                </div>

            </a>


            <a href="{{ url('/support?priority=high') }}" class="quick-action">

                <div class="quick-icon">
                    ⚠️
                </div>

                <div class="quick-title">
                    High Priority
                </div>

                <div class="quick-text">
                    Review high priority messages.
                </div>

            </a>


            <a href="{{ url('/support?sort=from_email&direction=asc') }}" class="quick-action">

                <div class="quick-icon">
                    ↕️
                </div>

                <div class="quick-title">
                    Sort Emails
                </div>

                <div class="quick-text">
                    Sort emails by available fields.
                </div>

            </a>


            <a href="{{ url('/mail-test') }}" class="quick-action">

                <div class="quick-icon">
                    ✉️
                </div>

                <div class="quick-title">
                    Test Email
                </div>

                <div class="quick-text">
                    Create a fake incoming support email.
                </div>

            </a>

        </div>

    </div>

</div>

</body>
</html>
