<!DOCTYPE html>
<html>
<head>
<title>Send Email</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:40%;
    margin:60px auto;
}

.card{
    background:white;
    padding:25px;
    border-radius:8px;
    box-shadow:0 3px 12px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    background:#4f46e5;
    color:white;
    padding:12px;
    border-radius:8px;
}

input, textarea{
    width:100%;
    padding:10px;
    margin-top:8px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    width:100%;
    background:#4f46e5;
    color:white;
    border:none;
    padding:12px;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#4338ca;
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>✉ Send Fake Email</h2>

<form method="POST" action="/mail-test">
@csrf

<label>From</label>
<input name="from" value="test@gmail.com">

<label>Subject</label>
<input name="subject" value="Hello">

<label>Message</label>
<textarea name="body-plain">Mailbox working!</textarea>

<button type="submit">Send Email</button>

</form>

</div>
</div>

</body>
</html>