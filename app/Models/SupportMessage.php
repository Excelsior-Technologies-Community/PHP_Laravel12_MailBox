<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'from_email',
        'subject',
        'message',
        'is_read',
        'priority',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

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