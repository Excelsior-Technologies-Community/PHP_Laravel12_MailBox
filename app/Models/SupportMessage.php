<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'thread_id',
        'from_email',
        'to_email',
        'subject',
        'message',
        'attachment',
        'is_read',
        'is_starred',
        'is_sent_reply',
        'priority',
        'scheduled_at',
        'is_scheduled',
        'reminder_at',
        'reminder_note',
        'status',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_sent_reply' => 'boolean',
        'is_scheduled' => 'boolean',
        'scheduled_at' => 'datetime',
        'reminder_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SupportMessage::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function getThreadMessages()
    {
        $rootId = $this->thread_id ?? $this->id;

        return self::where('id', $rootId)
            ->orWhere('thread_id', $rootId)
            ->orWhere('parent_id', $rootId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Mark email as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
        ]);
    }

    /**
     * Mark email as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
        ]);
    }

    /**
     * Check if email is unread.
     */
    public function isUnread(): bool
    {
        return !$this->is_read;
    }

    /**
     * Toggle starred status.
     */
    public function toggleStar(): void
    {
        $this->update([
            'is_starred' => !$this->is_starred,
        ]);
    }

    /**
     * Get priority badge class.
     */
    public function priorityClass(): string
    {
        return match ($this->priority) {
            'urgent' => 'priority-urgent',
            'high' => 'priority-high',
            default => 'priority-normal',
        };
    }
}