<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'from_email',
        'subject',
        'message',
        'is_read',
        'is_starred',
        'priority',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
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