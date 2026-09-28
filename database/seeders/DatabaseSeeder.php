<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@company.com'],
            ['name' => 'Support Admin', 'password' => bcrypt('password')]
        );

        // 1. Root Customer Query
        $msg1 = SupportMessage::create([
            'from_email' => 'customer.john@example.com',
            'to_email' => 'support@company.com',
            'subject' => 'Issue with Enterprise License Renewal',
            'message' => 'Hello Support Team, We attempted to renew our Enterprise license card ending in 4242, but received payment gateway timeout code #503.',
            'priority' => 'urgent',
            'is_read' => true,
            'is_starred' => true,
            'status' => 'open',
        ]);
        $msg1->update(['thread_id' => $msg1->id]);

        // Outbound Staff Reply in Thread
        $reply1 = SupportMessage::create([
            'parent_id' => $msg1->id,
            'thread_id' => $msg1->id,
            'from_email' => 'support@company.com',
            'to_email' => 'customer.john@example.com',
            'subject' => 'Re: Issue with Enterprise License Renewal',
            'message' => 'Hi John, Thanks for reaching out. We have cleared the gateway lock on your account. Please try checking out again now.',
            'priority' => 'urgent',
            'is_read' => true,
            'is_sent_reply' => true,
            'status' => 'open',
        ]);

        // Second Customer Followup in same Thread
        $msg1_sub = SupportMessage::create([
            'parent_id' => $reply1->id,
            'thread_id' => $msg1->id,
            'from_email' => 'customer.john@example.com',
            'to_email' => 'support@company.com',
            'subject' => 'Re: Issue with Enterprise License Renewal',
            'message' => 'Thanks! That worked perfectly. Could you send the tax invoice PDF copy as well?',
            'priority' => 'urgent',
            'is_read' => false,
            'is_sent_reply' => false,
            'status' => 'open',
            'reminder_at' => Carbon::now()->addDays(1),
            'reminder_note' => 'Send tax invoice PDF copy to John',
        ]);

        // 2. Scheduled Mail (Send Later)
        $msg2 = SupportMessage::create([
            'from_email' => 'sarah.tech@example.com',
            'to_email' => 'support@company.com',
            'subject' => 'API v2 Documentation Request',
            'message' => 'Hi Team, Is there a preview sandbox available for testing API v2 endpoints?',
            'priority' => 'high',
            'is_read' => true,
            'is_starred' => false,
            'status' => 'open',
        ]);
        $msg2->update(['thread_id' => $msg2->id]);

        SupportMessage::create([
            'parent_id' => $msg2->id,
            'thread_id' => $msg2->id,
            'from_email' => 'support@company.com',
            'to_email' => 'sarah.tech@example.com',
            'subject' => 'Re: API v2 Documentation Request',
            'message' => 'Hello Sarah, The sandbox environment will be provisioned tomorrow morning at 09:00 AM UTC.',
            'priority' => 'high',
            'is_read' => true,
            'is_sent_reply' => true,
            'is_scheduled' => true,
            'scheduled_at' => Carbon::now()->addHours(12),
            'status' => 'scheduled',
        ]);
    }
}
