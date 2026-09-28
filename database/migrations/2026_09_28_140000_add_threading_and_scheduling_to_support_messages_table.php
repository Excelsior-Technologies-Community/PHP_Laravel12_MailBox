<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->unsignedBigInteger('thread_id')->nullable()->after('parent_id');
            $table->string('to_email')->nullable()->after('from_email');
            $table->string('attachment')->nullable()->after('message');
            $table->boolean('is_sent_reply')->default(false)->after('attachment');
            $table->timestamp('scheduled_at')->nullable()->after('is_sent_reply');
            $table->boolean('is_scheduled')->default(false)->after('scheduled_at');
            $table->timestamp('reminder_at')->nullable()->after('is_scheduled');
            $table->text('reminder_note')->nullable()->after('reminder_at');
            $table->string('status')->default('open')->after('reminder_note'); // open, pending_reply, resolved, scheduled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn([
                'parent_id',
                'thread_id',
                'to_email',
                'attachment',
                'is_sent_reply',
                'scheduled_at',
                'is_scheduled',
                'reminder_at',
                'reminder_note',
                'status',
            ]);
        });
    }
};
