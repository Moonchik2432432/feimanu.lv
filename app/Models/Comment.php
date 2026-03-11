<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'komentari';
    protected $primaryKey = 'komentars_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ieraksts_id',
        'text',
        'izveidots_datums',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'ieraksts_id', 'ieraksts_id');
    }

    protected $casts = [
    'izveidots_datums' => 'datetime',
    ];
}