<!DOCTYPE html>
<html>

<head>

    <title>Email Detail</title>

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
            width: 75%;
            max-width: 1000px;
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

        .management {
            margin-top: 25px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .management h3 {
            margin-top: 0;
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

        .read {
            background: #dcfce7;
            color: #166534;
        }

        .unread {
            background: #ede9fe;
            color: #5b21b6;
        }

        .starred {
            background: #fef3c7;
            color: #92400e;
        }

        .normal {
            background: #e5e7eb;
            color: #374151;
        }

        .high {
            background: #fef3c7;
            color: #92400e;
        }

        .urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        select {
            padding: 9px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        button {
            border: none;
            color: white;
            padding: 9px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .toggle-btn {
            background: #374151;
        }

        .star-btn {
            background: #f59e0b;
        }

        .delete-btn {
            background: #dc2626;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #4f46e5;
            color: white;
            padding: 9px 15px;
            border-radius: 6px;
        }

        @media(max-width: 700px) {

            .container {
                width: 92%;
            }

            .email-info {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>
            📧 Email Detail
        </h2>

        <a href="{{ route('support.index') }}">
            📥 Inbox
        </a>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <div class="card">

        <div class="email-info">

            <div class="label">
                From:
            </div>

            <div class="value">
                {{ $message->from_email }}
            </div>

            <div class="label">
                Subject:
            </div>

            <div class="value">
                {{ $message->subject ?: '(No Subject)' }}
            </div>

            <div class="label">
                Received:
            </div>

            <div class="value">
                {{ $message->created_at->format(
                    'd M Y, h:i A'
                ) }}
            </div>

            <div class="label">
                Status:
            </div>

            <div class="value">

                @if($message->is_read)

                    <span class="badge read">
                        ✓ Read
                    </span>

                @else

                    <span class="badge unread">
                        ● Unread
                    </span>

                @endif

            </div>

            <div class="label">
                Star:
            </div>

            <div class="value">

                @if($message->is_starred)

                    <span class="badge starred">
                        ⭐ Starred
                    </span>

                @else

                    <span class="badge">
                        ☆ Not Starred
                    </span>

                @endif

            </div>

            <div class="label">
                Priority:
            </div>

            <div class="value">

                <span
                    class="badge {{ $message->priority }}"
                >
                    {{ ucfirst($message->priority) }}
                </span>

            </div>

        </div>

        <hr>

        <div class="message">
            {{ $message->message }}
        </div>

        <div class="management">

            <h3>
                ⚙ Email Management
            </h3>

            <div class="management-row">

                <form
                    method="POST"
                    action="{{ route(
                        'support.toggle-star',
                        $message->id
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="star-btn"
                    >

                        @if($message->is_starred)
                            ☆ Remove Star
                        @else
                            ⭐ Star Email
                        @endif

                    </button>

                </form>

                <form
                    method="POST"
                    action="{{ route(
                        'support.toggle-read',
                        $message->id
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="toggle-btn"
                    >

                        @if($message->is_read)
                            Mark as Unread
                        @else
                            Mark as Read
                        @endif

                    </button>

                </form>

                <form
                    method="POST"
                    action="{{ route(
                        'support.priority',
                        $message->id
                    ) }}"
                >

                    @csrf

                    <select name="priority">

                        <option
                            value="normal"
                            {{
                                $message->priority === 'normal'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Normal Priority
                        </option>

                        <option
                            value="high"
                            {{
                                $message->priority === 'high'
                                ? 'selected'
                                : ''
                            }}
                        >
                            High Priority
                        </option>

                        <option
                            value="urgent"
                            {{
                                $message->priority === 'urgent'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Urgent Priority
                        </option>

                    </select>

                    <button type="submit">
                        Update Priority
                    </button>

                </form>

                <form
                    method="POST"
                    action="{{ route(
                        'support.destroy',
                        $message->id
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
                        🗑️ Move to Trash
                    </button>

                </form>

            </div>

        </div>

        <a
            class="back"
            href="{{ route('support.index') }}"
        >
            ⬅ Back to Inbox
        </a>

    </div>

</div>

</body>

</html>