<!DOCTYPE html>
<html>

<head>

    <title>Mailbox Analytics Dashboard</title>

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

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stat-icon {
            font-size: 28px;
        }

        .stat-title {
            color: #6b7280;
            margin-top: 8px;
            font-size: 14px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: bold;
            margin-top: 5px;
        }

        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .section h3 {
            margin-top: 0;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .priority-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .priority-row:last-child {
            border-bottom: none;
        }

        .priority-name {
            font-weight: bold;
        }

        .priority-count {
            font-weight: bold;
        }

        .recent-mail {
            padding: 13px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .recent-mail:last-child {
            border-bottom: none;
        }

        .recent-mail a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: bold;
        }

        .recent-mail .sender {
            color: #374151;
        }

        .recent-mail .date {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 4px;
        }

        .sender-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .sender-row:last-child {
            border-bottom: none;
        }

        .badge {
            padding: 5px 10px;
            background: #ede9fe;
            color: #5b21b6;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .empty {
            color: #6b7280;
            padding: 15px 0;
        }

        @media(max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .two-columns {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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

        <h2>📊 Mailbox Analytics Dashboard</h2>

        <div class="header-links">

            <a href="{{ route('support.index') }}">
                📥 Inbox
            </a>

            <a href="{{ route('mail.test') }}">
                ✉ Send Email
            </a>

        </div>

    </div>

    <div class="stats">

        <div class="stat-card">

            <div class="stat-icon">📧</div>

            <div class="stat-title">
                Total Emails
            </div>

            <div class="stat-value">
                {{ $totalEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">📩</div>

            <div class="stat-title">
                Unread Emails
            </div>

            <div class="stat-value">
                {{ $unreadEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">📬</div>

            <div class="stat-title">
                Read Emails
            </div>

            <div class="stat-value">
                {{ $readEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">📅</div>

            <div class="stat-title">
                Emails Today
            </div>

            <div class="stat-value">
                {{ $todayEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">👥</div>

            <div class="stat-title">
                Unique Senders
            </div>

            <div class="stat-value">
                {{ $uniqueSenders }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">🚨</div>

            <div class="stat-title">
                Urgent Emails
            </div>

            <div class="stat-value">
                {{ $urgentEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">⚠️</div>

            <div class="stat-title">
                High Priority
            </div>

            <div class="stat-value">
                {{ $highPriorityEmails }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon">📨</div>

            <div class="stat-title">
                Normal Priority
            </div>

            <div class="stat-value">
                {{ $normalPriorityEmails }}
            </div>

        </div>

    </div>

    <div class="two-columns">

        <div class="section">

            <h3>⭐ Priority Statistics</h3>

            <div class="priority-row">

                <div class="priority-name">
                    Normal
                </div>

                <div class="priority-count">
                    {{ $normalPriorityEmails }}
                </div>

            </div>

            <div class="priority-row">

                <div class="priority-name">
                    High
                </div>

                <div class="priority-count">
                    {{ $highPriorityEmails }}
                </div>

            </div>

            <div class="priority-row">

                <div class="priority-name">
                    Urgent
                </div>

                <div class="priority-count">
                    {{ $urgentEmails }}
                </div>

            </div>

        </div>

        <div class="section">

            <h3>👥 Top Senders</h3>

            @forelse($topSenders as $sender)

                <div class="sender-row">

                    <div>
                        {{ $sender->from_email }}
                    </div>

                    <div class="badge">
                        {{ $sender->total }} emails
                    </div>

                </div>

            @empty

                <div class="empty">
                    No sender statistics available yet.
                </div>

            @endforelse

        </div>

    </div>

    <div class="section">

        <h3>🕐 Recent Emails</h3>

        @forelse($recentEmails as $email)

            <div class="recent-mail">

                <div>
                    <a href="{{ route('support.show', $email->id) }}">
                        {{ $email->subject ?: '(No Subject)' }}
                    </a>
                </div>

                <div class="sender">
                    From: {{ $email->from_email }}
                </div>

                <div class="date">
                    {{ $email->created_at->format('d M Y, h:i A') }}
                </div>

            </div>

        @empty

            <div class="empty">
                No emails received yet.
            </div>

        @endforelse

    </div>

</div>

</body>

</html>