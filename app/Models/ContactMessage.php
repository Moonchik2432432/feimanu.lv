<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'reply',
        'replied_at',
        'replied_by',
        'user_archived_at',
        'admin_archived_at',
        'user_deleted_at',
        'admin_deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'replied_at' => 'datetime',
        'user_archived_at' => 'datetime',
        'admin_archived_at' => 'datetime',
        'user_deleted_at' => 'datetime',
        'admin_deleted_at' => 'datetime',
    ];

    public const STATUS_NEW = 'new';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_ANSWERED = 'answered';
    
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Jauns',
            self::STATUS_OVERDUE => 'Novēlots',
            self::STATUS_ANSWERED => 'Atbildēts',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function isArchivedForUser(): bool
    {
        return !is_null($this->user_archived_at);
    }

    public function isArchivedForAdmin(): bool
    {
        return !is_null($this->admin_archived_at);
    }

    public function isDeletedForUser(): bool
    {
        return !is_null($this->user_deleted_at);
    }

    public function isDeletedForAdmin(): bool
    {
        return !is_null($this->admin_deleted_at);
    }
}
