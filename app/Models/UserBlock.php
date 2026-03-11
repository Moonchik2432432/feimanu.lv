<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    protected $table = 'user_blocks';

    protected $fillable = [
        'user_id',
        'blocked_by',
        'block_reason_id',
        'custom_reason',
        'blocked_from',
        'blocked_until',
        'unblocked_at',
        'unblocked_by',
    ];

    protected $casts = [
        'blocked_from' => 'datetime',
        'blocked_until' => 'datetime',
        'unblocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function unblockedBy()
    {
        return $this->belongsTo(User::class, 'unblocked_by');
    }

    public function reason()
    {
        return $this->belongsTo(BlockReason::class, 'block_reason_id');
    }
}