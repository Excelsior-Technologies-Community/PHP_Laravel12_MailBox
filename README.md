#  PHP_Laravel12_MailBox

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8%2B-blue)
![Package](https://img.shields.io/badge/BeyondCode-Mailbox-green)

---

##  Overview

**PHP_Laravel12_MailBox** is a Laravel 12 project demonstrating how to build a **Mailbox (Inbox) System** using the `beyondcode/laravel-mailbox` package.

This application simulates incoming emails locally and processes them using a Mailbox handler. Emails are stored in the database and displayed in an Inbox-style UI where users can view individual messages.

For local development, incoming emails are simulated using a **FakeInboundEmail** class. In production, the same handler can process real emails via webhook providers such as **Mailgun** or **SendGrid**.

---

##  Features

*  Laravel 12 compatible
*  Mailbox handler architecture
*  Inbox listing with pagination
*  Email detail view
*  Clean MVC structure
*  Web-based email testing (no Postman required)
*  Database email storage
*  Production-ready mailbox handler
*  Supports real inbound email providers

---

##  Folder Structure

```
PHP_Laravel12_MailBox/
│
├── app/
│   ├── Http/Controllers/
│   │   ├── MailController.php
│   │   └── SupportController.php
│   │
│   ├── Mailboxes/
│   │   └── SupportMailbox.php
│   │
│   ├── Models/
│   │   └── SupportMessage.php
│   │
│   └── Support/
│       └── FakeInboundEmail.php
│
├── database/migrations/
│   └── create_support_messages_table.php
│
├── resources/views/
│   ├── mailtest.blade.php
│   └── support/
│       ├── index.blade.php
│       └── show.blade.php
│
├── routes/
│   └── web.php
│
└── .env
```

---


## 1. Project Installation

```bash
composer create-project laravel/laravel PHP_Laravel12_MailBox
```

Start server:

```bash
php artisan serve
```

---

## 2. Install Mailbox Package

```bash
composer require beyondcode/laravel-mailbox
```

---

## 3. Publish Package Files

```bash
php artisan vendor:publish --provider="BeyondCode\\Mailbox\\MailboxServiceProvider" --tag="migrations"

php artisan vendor:publish --provider="BeyondCode\\Mailbox\\MailboxServiceProvider" --tag="config"
```

---

## 4. Database Setup

Edit `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

---

## 5. Create SupportMessage Model

```bash
php artisan make:model SupportMessage -m
```

### database/migrations/*_create_support_messages_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->string('from_email');
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};
```

Run:

```bash
php artisan migrate
```

---

## 6. Model

### app/Models/SupportMessage.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'from_email',
        'subject',
        'message'
    ];
}
```

---

## 7. Mailbox Handler

Create:

```bash
php artisan make:class Mailboxes/SupportMailbox
```

### app/Mailboxes/SupportMailbox.php

```php
<?php

namespace App\Mailboxes;

use App\Models\SupportMessage;

class SupportMailbox
{
    public function __invoke($email)
    {
        SupportMessage::create([
            'from_email' => $email->from(),
            'subject'    => $email->subject(),
            'message'    => $email->text(),
        ]);
    }
}
```

---

## 8. Register Mailbox

### app/Providers/AppServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BeyondCode\Mailbox\Facades\Mailbox;
use App\Mailboxes\SupportMailbox;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // emails sent to support@yourapp.com
        Mailbox::to('[email protected]', SupportMailbox::class);
    }
}
```

---

## 9. FakeInboundEmail (Local Testing)

### app/Support/FakeInboundEmail.php

```php
<?php

namespace App\Support;

class FakeInboundEmail
{
    protected $from;
    protected $subject;
    protected $text;

    public function __construct($from, $subject, $text)
    {
        $this->from = $from;
        $this->subject = $subject;
        $this->text = $text;
    }

    public function from()
    {
        return $this->from;
    }

    public function subject()
    {
        return $this->subject;
    }

    public function text()
    {
        return $this->text;
    }
}
```

---

## 10. MailController

```bash
php artisan make:controller MailController
```

### app/Http/Controllers/MailController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mailboxes\SupportMailbox;
use App\Support\FakeInboundEmail;

class MailController extends Controller
{
    public function form()
    {
        return view('mailtest');
    }

    public function send(Request $request)
    {
        // create fake email (SAFE)
        $email = new FakeInboundEmail(
            $request->from,
            $request->subject,
            $request->input('body-plain')
        );

        // call mailbox handler
        app(SupportMailbox::class)($email);

        return redirect('/support');
    }
}
```

---

## 11. SupportController

```bash
php artisan make:controller SupportController
```

### app/Http/Controllers/SupportController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;

class SupportController extends Controller
{
    // Display inbox messages with pagination
    public function index()
    {
        $messages = SupportMessage::latest()->paginate(10);

        return view('support.index', compact('messages'));
    }

    // Show single email message details
    public function show($id)
    {
        $message = SupportMessage::findOrFail($id);

        return view('support.show', compact('message'));
    }
}
```

---

## 12. Routes

### routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\MailController;

Route::get('/', fn () => view('welcome'));

/* Mail Test */
Route::get('/mail-test', [MailController::class,'form']);
Route::post('/mail-test', [MailController::class,'send']);

/* Inbox */
Route::get('/support', [SupportController::class,'index']);
Route::get('/support/{id}', [SupportController::class,'show']);
```

---

# 13. Blade Views (FULL CODE)

---

## resources/views/mailtest.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Send Email</title>

<style>
body{font-family:Arial;background:#f4f6f9;}
.container{width:40%;margin:60px auto;}
.card{background:white;padding:25px;border-radius:8px;box-shadow:0 3px 12px rgba(0,0,0,0.1);}
h2{text-align:center;background:#4f46e5;color:white;padding:12px;border-radius:8px;}
input, textarea{width:100%;padding:10px;margin-top:8px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;}
button{width:100%;background:#4f46e5;color:white;border:none;padding:12px;border-radius:6px;cursor:pointer;font-size:16px;}
button:hover{background:#4338ca;}
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
```

---

## resources/views/support/index.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Inbox</title>

<style>
body{font-family:Arial;background:#f4f6f9;margin:0;}
.container{width:80%;margin:40px auto;}
h2{background:#4f46e5;color:white;padding:15px;border-radius:8px;}
.mail-card{background:white;padding:15px;margin-top:15px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:0.3s;}
.mail-card:hover{transform:translateY(-3px);box-shadow:0 5px 15px rgba(0,0,0,0.15);}
.email{font-weight:bold;color:#111827;}
.subject{color:#6b7280;margin:5px 0;}
.view-btn{display:inline-block;padding:6px 12px;background:#4f46e5;color:white;text-decoration:none;border-radius:5px;font-size:14px;}
.view-btn:hover{background:#4338ca;}
.pagination{margin-top:20px;}
</style>
</head>

<body>

<div class="container">

<h2> Inbox</h2>

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
```

---

## resources/views/support/show.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Email Detail</title>

<style>
body{font-family:Arial;background:#f4f6f9;}
.container{width:70%;margin:40px auto;}
.card{background:white;padding:25px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
h2{background:#4f46e5;color:white;padding:15px;border-radius:8px;}
.label{font-weight:bold;color:#374151;}
.message{margin-top:20px;padding:15px;background:#f9fafb;border-radius:6px;}
.back{display:inline-block;margin-top:20px;text-decoration:none;background:#4f46e5;color:white;padding:8px 15px;border-radius:5px;}
</style>
</head>

<body>

<div class="container">

<h2> Email Detail</h2>

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
```

---

## 14. Environment Settings

```
MAILBOX_DRIVER=log
MAIL_MAILER=log
```

---

## 15. Clear Cache

```bash
php artisan optimize:clear
composer dump-autoload
```

---

## 16. Run & Test

Open:

```
http://127.0.0.1:8000/mail-test
```

Steps:

1. Fill form
2. Click Send
   
   <img width="761" height="443" alt="Screenshot 2026-02-23 144140" src="https://github.com/user-attachments/assets/5e48e416-0ec9-4142-bd8c-2aa29ffd49a4" />

3. Open Inbox → `http://127.0.0.1:8000/support`

   <img width="1542" height="192" alt="Screenshot 2026-02-23 144207" src="https://github.com/user-attachments/assets/4e5eb8b4-0c71-4eca-8551-15dcf0d13e18" />

   <img width="1330" height="347" alt="Screenshot 2026-02-23 144254" src="https://github.com/user-attachments/assets/174aa4e2-048b-4ccf-a1b8-29f52b2c3a53" />


---

## Production Notes

* Replace FakeInboundEmail with real webhook provider
* Configure Mailgun / SendGrid inbound routes
* `/laravel-mailbox` endpoint will receive real emails

---

