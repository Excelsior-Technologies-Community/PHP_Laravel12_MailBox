<!DOCTYPE html>
<html>
<head>
<title>Email Detail</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:70%;
    margin:40px auto;
}

.card{
    background:white;
    padding:25px;
    border-radius:8px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

h2{
    background:#4f46e5;
    color:white;
    padding:15px;
    border-radius:8px;
}

.label{
    font-weight:bold;
    color:#374151;
}

.message{
    margin-top:20px;
    padding:15px;
    background:#f9fafb;
    border-radius:6px;
}

.back{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    background:#4f46e5;
    color:white;
    padding:8px 15px;
    border-radius:5px;
}
</style>
</head>

<body>

<div class="container">

<h2>📧 Email Detail</h2>

<div class="card">

<p><span class="label">From:</span> {{ $message->from_email }}</p>
<p><span class="label">Subject:</span> {{ $message->subject }}</p>

<hr>

<div class="message">
{{ $message->message }}
</div>

<a class="back" href="/support">⬅ Back</a>

</div>

</div>

</body>
</html>