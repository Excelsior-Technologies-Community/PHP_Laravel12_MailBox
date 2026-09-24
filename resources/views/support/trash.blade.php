<!DOCTYPE html>
<html>

<head>

    <title>Mailbox Trash</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #111827;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 35px auto;
        }

        .header {
            background: #374151;
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

        .header a {
            background: white;
            color: #374151;
            padding: 9px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .success {
            margin-top: 20px;
            padding: 12px 15px;
            background: #dcfce7;
            color: #166534;
            border-radius: 7px;
        }

        .mail-card {
            background: white;
            padding: 20px;
            margin-top: 15px;
            border-radius: 9px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .email {
            font-weight: bold;
        }

        .subject {
            margin-top: 7px;
            color: #4b5563;
        }

        .date {
            margin-top: 7px;
            color: #9ca3af;
            font-size: 13px;
        }

        .deleted {
            margin-top: 7px;
            color: #dc2626;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        button {
            border: none;
            color: white;
            padding: 8px 13px;
            border-radius: 6px;
            cursor: pointer;
        }

        .restore {
            background: #16a34a;
        }

        .permanent {
            background: #dc2626;
        }

        .empty {
            background: white;
            padding: 40px;
            margin-top: 20px;
            text-align: center;
            border-radius: 10px;
            color: #6b7280;
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
            background: #374151;
            color: white;
        }

        @media(max-width: 700px) {

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .actions {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>
            🗑️ Mailbox Trash
        </h2>

        <a href="{{ route('support.index') }}">
            📥 Back to Inbox
        </a>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    @forelse($messages as $message)

        <div class="mail-card">

            <div class="email">
                {{ $message->from_email }}
            </div>

            <div class="subject">
                {{ $message->subject ?: '(No Subject)' }}
            </div>

            <div class="date">
                Received:
                {{ $message->created_at->format(
                    'd M Y, h:i A'
                ) }}
            </div>

            <div class="deleted">
                Deleted:
                {{ $message->deleted_at->format(
                    'd M Y, h:i A'
                ) }}
            </div>

            <div class="actions">

                <form
                    method="POST"
                    action="{{ route(
                        'support.restore',
                        $message->id
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="restore"
                    >
                        ♻️ Restore
                    </button>

                </form>

                <form
                    method="POST"
                    action="{{ route(
                        'support.force-delete',
                        $message->id
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Permanently delete this email? This cannot be undone.'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="permanent"
                    >
                        ❌ Permanent Delete
                    </button>

                </form>

            </div>

        </div>

    @empty

        <div class="empty">

            <h3>
                🗑️ Trash is Empty
            </h3>

            <p>
                No deleted emails are currently in Trash.
            </p>

        </div>

    @endforelse

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

</body>

</html>