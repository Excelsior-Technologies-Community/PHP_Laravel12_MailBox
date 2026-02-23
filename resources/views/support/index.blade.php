<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
        }

        .container{
            width:80%;
            margin:40px auto;
        }

        h2{
            background:#4f46e5;
            color:white;
            padding:15px;
            border-radius:8px;
        }

        .mail-card{
            background:white;
            padding:15px;
            margin-top:15px;
            border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
            transition:0.3s;
        }

        .mail-card:hover{
            transform:translateY(-3px);
            box-shadow:0 5px 15px rgba(0,0,0,0.15);
        }

        .email{
            font-weight:bold;
            color:#111827;
        }

        .subject{
            color:#6b7280;
            margin:5px 0;
        }

        .view-btn{
            display:inline-block;
            padding:6px 12px;
            background:#4f46e5;
            color:white;
            text-decoration:none;
            border-radius:5px;
            font-size:14px;
        }

        .view-btn:hover{
            background:#4338ca;
        }

        .pagination{
            margin-top:20px;
        }
    </style>
</head>

<body>

<div class="container">

<h2>📥 Inbox</h2>

@foreach($messages as $msg)
<div class="mail-card">
    <div class="email">{{ $msg->from_email }}</div>
    <div class="subject">{{ $msg->subject }}</div>

    <a class="view-btn" href="/support/{{ $msg->id }}">View</a>
</div>
@endforeach

<div class="pagination">
{{ $messages->links() }}
</div>

</div>

</body>
</html>