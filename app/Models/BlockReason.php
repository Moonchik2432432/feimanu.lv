<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockReason extends Model
{
    protected $table = 'block_reasons';

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    public function userBlocks()
    {
        return $this->hasMany(UserBlock::class, 'block_reason_id');
    }
}