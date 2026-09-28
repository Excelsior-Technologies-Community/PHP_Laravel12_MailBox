<!DOCTYPE html>
<html>

<head>

    <title>Email Detail & Conversation Thread</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
            width: 80%;
            max-width: 1050px;
            margin: 40px auto;
        }

        .header {
            background: #4f46e5;
            color: white;
            padding: 18px;
            border-radius: 9px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .header a {
            background: white;
            color: #4f46e5;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            margin-top: 20px;
            padding: 12px 15px;
            background: #dcfce7;
            color: #166534;
            border-radius: 7px;
        }

        .card {
            background: white;
            padding: 25px;
            margin-top: 20px;
            border-radius: 9px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .email-info {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            color: #374151;
        }

        .value {
            color: #111827;
        }

        .message {
            margin-top: 20px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 7px;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        /* Thread History Timeline */
        .thread-section {
            margin-top: 30px;
        }

        .thread-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 15px;
            background: #ffffff;
        }

        .thread-item.staff-reply {
            border-left: 5px solid #4f46e5;
            background: #f0fdf4;
        }

        .thread-item.customer-msg {
            border-left: 5px solid #0284c7;
            background: #f8fafc;
        }

        .thread-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .badge-type {
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .badge-inbound { background: #e0f2fe; color: #0369a1; }
        .badge-outbound { background: #dcfce7; color: #15803d; }
        .badge-scheduled { background: #fef3c7; color: #92400e; }

        .reply-box, .reminder-box {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            padding: 20px;
            margin-top: 25px;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 12px;
            font-family: inherit;
        }

        input[type="datetime-local"], input[type="file"], input[type="text"], select {
            padding: 9px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .management-row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .read { background: #dcfce7; color: #166534; }
        .unread { background: #ede9fe; color: #5b21b6; }
        .starred { background: #fef3c7; color: #92400e; }

        button {
            border: none;
            color: white;
            padding: 9px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary { background: #4f46e5; }
        .btn-schedule { background: #d97706; }
        .btn-reminder { background: #0284c7; }
        .toggle-btn { background: #374151; }
        .star-btn { background: #f59e0b; }
        .delete-btn { background: #dc2626; }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #4f46e5;
            color: white;
            padding: 9px 15px;
            border-radius: 6px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">
        <h2>📧 Email Detail & Conversation Thread</h2>
        <a href="{{ route('support.index') }}">📥 Back to Inbox</a>
    </div>

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    <div class="card">

        <div class="email-info">

            <div class="label">From:</div>
            <div class="value">{{ $message->from_email }}</div>

            <div class="label">Subject:</div>
            <div class="value"><b>{{ $message->subject ?: '(No Subject)' }}</b></div>

            <div class="label">Received:</div>
            <div class="value">{{ $message->created_at->format('d M Y, h:i A') }}</div>

            <div class="label">Status:</div>
            <div class="value">
                @if($message->is_read)
                    <span class="badge read">✓ Read</span>
                @else
                    <span class="badge unread">● Unread</span>
                @endif

                @if($message->is_scheduled)
                    <span class="badge badge-scheduled">⏰ Scheduled (Send Later)</span>
                @endif
            </div>

            <div class="label">Priority:</div>
            <div class="value">
                <span class="badge {{ $message->priority }}">{{ ucfirst($message->priority) }}</span>
            </div>

            @if($message->reminder_at)
                <div class="label">Reminder:</div>
                <div class="value" style="color: #0284c7; font-weight: bold;">
                    🔔 Set for {{ $message->reminder_at->format('d M Y, h:i A') }}
                    @if($message->reminder_note)
                        <br><small style="color: #6b7280;">Note: {{ $message->reminder_note }}</small>
                    @endif
                </div>
            @endif

        </div>

        <hr>

        <!-- 🧵 Multi-Threaded Conversation History -->
        <div class="thread-section">
            <h3>🧵 Conversation History ({{ count($threadMessages) }} Messages)</h3>

            @foreach($threadMessages as $index => $item)
                <div class="thread-item {{ $item->is_sent_reply ? 'staff-reply' : 'customer-msg' }}">
                    <div class="thread-meta">
                        <div>
                            @if($item->is_sent_reply)
                                <span class="badge-type badge-outbound">📤 OUTBOUND STAFF REPLY</span>
                                <b>support@company.com</b> → {{ $item->to_email ?? $message->from_email }}
                            @else
                                <span class="badge-type badge-inbound">📥 INBOUND CUSTOMER</span>
                                <b>{{ $item->from_email }}</b>
                            @endif
                        </div>
                        <div>
                            @if($item->is_scheduled)
                                <span class="badge-type badge-scheduled">⏰ SCHEDULED FOR {{ $item->scheduled_at ? $item->scheduled_at->format('d M Y, h:i A') : '' }}</span>
                            @else
                                🕒 {{ $item->created_at->format('d M Y, h:i A') }}
                            @endif
                        </div>
                    </div>

                    <div style="margin-top: 8px; line-height: 1.6; white-space: pre-wrap;">{{ $item->message }}</div>

                    @if($item->attachment)
                        <div style="margin-top: 10px; font-size: 13px;">
                            📎 <b>Attachment:</b>
                            <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank" style="color: #4f46e5;">View Attached File</a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- 💬 Direct Reply & Send Later Studio -->
        <div class="reply-box">
            <h3>💬 Direct Reply & Send Later Studio</h3>
            <form method="POST" action="{{ route('support.reply', $message->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Reply Message:</label>
                    <textarea name="reply_message" placeholder="Type your response to customer..." required></textarea>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight:bold; display:block; margin-bottom:5px;">Attach File:</label>
                        <input type="file" name="attachment">
                    </div>

                    <div>
                        <label style="font-weight:bold; display:block; margin-bottom:5px;">⏰ Send Later (Schedule):</label>
                        <input type="datetime-local" name="scheduled_at">
                    </div>
                </div>

                <button type="submit" class="btn-primary">✉ Send Reply Now / Schedule</button>
            </form>
        </div>

        <!-- ⏰ Follow-Up Reminder Engine -->
        <div class="reminder-box">
            <h3>⏰ Set Follow-Up Reminder</h3>
            <form method="POST" action="{{ route('support.reminder', $message->id) }}">
                @csrf
                <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                    <div>
                        <label style="font-weight:bold; display:block; margin-bottom:5px;">Reminder Date & Time:</label>
                        <input type="datetime-local" name="reminder_at" required>
                    </div>

                    <div style="flex:1;">
                        <label style="font-weight:bold; display:block; margin-bottom:5px;">Reminder Note:</label>
                        <input type="text" name="reminder_note" placeholder="e.g. Check if customer responded to refund query" style="width:100%;">
                    </div>

                    <button type="submit" class="btn-reminder">🔔 Save Reminder</button>
                </div>
            </form>
        </div>

        <div class="management" style="margin-top:30px;">
            <h3>⚙ Email Management</h3>
            <div class="management-row">
                <form method="POST" action="{{ route('support.toggle-star', $message->id) }}">
                    @csrf
                    <button type="submit" class="star-btn">
                        @if($message->is_starred) ☆ Remove Star @else ⭐ Star Email @endif
                    </button>
                </form>

                <form method="POST" action="{{ route('support.toggle-read', $message->id) }}">
                    @csrf
                    <button type="submit" class="toggle-btn">
                        @if($message->is_read) Mark as Unread @else Mark as Read @endif
                    </button>
                </form>

                <form method="POST" action="{{ route('support.priority', $message->id) }}">
                    @csrf
                    <select name="priority">
                        <option value="normal" {{ $message->priority === 'normal' ? 'selected' : '' }}>Normal Priority</option>
                        <option value="high" {{ $message->priority === 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="urgent" {{ $message->priority === 'urgent' ? 'selected' : '' }}>Urgent Priority</option>
                    </select>
                    <button type="submit">Update Priority</button>
                </form>

                <form method="POST" action="{{ route('support.destroy', $message->id) }}" onsubmit="return confirm('Move this email to Trash?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">🗑️ Move to Trash</button>
                </form>
            </div>
        </div>

        <a class="back" href="{{ route('support.index') }}">⬅ Back to Inbox</a>

    </div>

</div>

</body>
</html>