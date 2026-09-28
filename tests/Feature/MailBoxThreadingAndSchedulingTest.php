<?php

namespace Tests\Feature;

use App\Models\SupportMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MailBoxThreadingAndSchedulingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_it_can_render_threaded_conversation_history()
    {
        $message = SupportMessage::where('from_email', 'customer.john@example.com')->first();

        $response = $this->get(route('support.show', $message->id));

        $response->assertStatus(200);
        $response->assertSee('Conversation History');
        $response->assertSee('Issue with Enterprise License Renewal');
    }

    public function test_it_can_send_direct_reply_to_email_thread()
    {
        Storage::fake('public');
        $parent = SupportMessage::where('from_email', 'customer.john@example.com')->first();

        $file = UploadedFile::fake()->create('invoice.pdf', 100);

        $response = $this->post(route('support.reply', $parent->id), [
            'reply_message' => 'Here is your requested tax invoice.',
            'attachment' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_messages', [
            'parent_id' => $parent->id,
            'is_sent_reply' => true,
            'to_email' => $parent->from_email,
        ]);
    }

    public function test_it_can_schedule_reply_for_future_delivery()
    {
        $parent = SupportMessage::where('from_email', 'sarah.tech@example.com')->first();
        $futureTime = Carbon::now()->addHours(24)->format('Y-m-d\TH:i');

        $response = $this->post(route('support.reply', $parent->id), [
            'reply_message' => 'Your access token will be generated at 9 AM tomorrow.',
            'scheduled_at' => $futureTime,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_messages', [
            'parent_id' => $parent->id,
            'is_scheduled' => true,
            'status' => 'scheduled',
        ]);
    }

    public function test_it_can_set_follow_up_reminder()
    {
        $message = SupportMessage::where('from_email', 'customer.john@example.com')->first();
        $reminderTime = Carbon::now()->addDays(2)->format('Y-m-d\TH:i');

        $response = $this->post(route('support.reminder', $message->id), [
            'reminder_at' => $reminderTime,
            'reminder_note' => 'Call client to verify renewal completion',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_messages', [
            'id' => $message->id,
            'reminder_note' => 'Call client to verify renewal completion',
        ]);
    }
}
